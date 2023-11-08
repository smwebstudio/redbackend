<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Admin\Operations\DownloadEstateImagesOperation;
use App\Http\Controllers\Admin\Operations\EntryActivityOperation;
use App\Http\Controllers\Admin\Operations\RedDropZoneOperation;
use App\Http\Requests\EstateRequest;
use App\Models\CLocationCity;
use App\Models\CLocationCommunity;
use App\Models\CLocationStreet;
use App\Models\Contact;
use App\Models\Estate;
use App\Models\RealtorUser;
use App\Traits\Controllers\AddEstateCreateCommonFields;
use App\Traits\Controllers\AddEstateFetchMethods;
use App\Traits\Controllers\AddEstateListColumns;
use App\Traits\Controllers\HasEstateFilters;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Backpack\Pro\Http\Controllers\Operations\FetchOperation;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

/**
 * Class EstateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class EstateCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CloneOperation ;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use EntryActivityOperation;
    use RedDropZoneOperation;
    use FetchOperation;
    use AuthorizesRequests;
    use HasEstateFilters;
    use AddEstateCreateCommonFields;
    use AddEstateListColumns;
    use AddEstateFetchMethods;
    use DownloadEstateImagesOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Estate::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/estate');
        CRUD::setEntityNameStrings('estate', 'Անշարժ գույք');
    }

    protected function setupShowOperation()
    {

        CRUD::setShowView('redg.estate.showTabs');
        $this->crud->addButton('line', 'download_estate_images', 'view', 'crud::buttons.download', 'end');
        Widget::add()->type('script')
            ->content('https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js')
            ->crossorigin('anonymous');
        $estate = $this->crud->getCurrentEntry();

        $this->addApartmentColumns();
        $this->addProfessinalTabColumns();
        $this->addAdditionalTabColumns();
        $this->addSellerTabColumns();

        $this->crud->data['estate'] = $estate;
        $this->crud->viewType = request()->type;

        $viewType = request()->type;
        if($viewType === 'viewOnly') {
            unset($this->crud->data['estate']->address_building);
            unset($this->crud->data['estate']->address_apartment);
        }




    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        Widget::add()->type('script')->content('assets/js/admin/lists/estate.js');
        Widget::add()->type('script')->content('assets/js/admin/lists/sweetalert.js');

        $this->crud->addButton('top', 'estate_create_buttons_set', 'view', 'crud::buttons.estate_create_buttons_set');
        $this->crud->addButton('line', 'estate_clone', 'view', 'crud::buttons.estate_clone');
        $this->crud->removeButton('clone');
        $this->crud->removeButton('create');
//        $this->crud->addButton('line', 'archive', 'view', 'crud::buttons.archive');
//        $this->crud->addButton('line', 'photo', 'view', 'crud::buttons.photo');
//        $this->crud->addButton('line', 'message', 'view', 'crud::buttons.message');
//        $this->crud->addButton('line', 'star', 'view', 'crud::buttons.star');


        /*Columns*/
        $this->addListColumns();

        /*Filters*/
        $this->addListFilters();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(EstateRequest::class);
        Widget::add()->type('script')->content('assets/js/admin/forms/estate.js');

        $this->addApartmentFields();
        $this->addCreateCommonFields(1);

    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(EstateRequest::class);
        $this->setupCreateOperation();
        $estate = $this->crud->getCurrentEntry();

        CRUD::removeField('estate_type');
        if (!empty($estate->estate_longitude) && !empty($estate->estate_latitude)) {
            CRUD::removeField('location');

            CRUD::addField([
                'name' => 'location',
                'label' => 'Տեղը քարտեզի վրա',
                'type' => 'google_map',
                'tab' => 'Քարտեզ',
                'value' => '{"lat":' . $estate->estate_latitude . ',"lng":' . $estate->estate_longitude . '}',

                'map_options' => [
                    'default_lat' => $estate->estate_latitude,
                    'default_lng' => $estate->estate_longitude,
                    'locate' => true,
                    'height' => 400,
                ]
            ]);
        }

    }


    private function addApartmentFields(): void
    {
        /*Apartment building attribute*/

        $building_attributes = [
            'building_structure_type',
            'building_type',
            'building_project_type',
            'building_floor_type',
            'exterior_design_type',
            'courtyard_improvement',
            'distance_public_objects',
            'elevator_type',
            'year',
            'parking_type',
            'entrance_type',
            'entrance_door_position',
            'entrance_door_type',
            'windows_view',
            'building_window_count',
            'repairing_type',
            'heating_system_type',
            'service_fee_type',
        ];

        $addApartmentBuildingList = [];

        foreach ($building_attributes as $buildingAttribute) {
            $addApartmentBuildingList[] = [
                'name' => $buildingAttribute,
                'type' => 'relationship',
                'attribute' => "name_arm",
                'label' => trans('estate.' . $buildingAttribute),
                'placeholder' => '-Ընտրել մեկը-',
                'tab' => 'Հիմնական',
                'wrapper' => [
                    'class' => 'form-group col-md-3 apartment_building_attribute'
                ],
            ];
        }


        $apartmentFeaturesList = [
            "new_construction",
            "apartment_construction",
            "exclusive_design",
            "possible_extension",
            "new_roof",
            "separate_room",
            "balcony",
            "oriel",
            "open_balcony",
            "uninhabited",
            "new_water_tubes",
            "new_wiring",
            "new_windows",
            "new_doors",
            "new_floor",
            "laminat",
            "parquet",
            "heating_ground",
            "new_bathroom",
            "jacuzzi",
            "persistent_water",
            "natural_gas",
            "gas_heater",
            "refrigirator",
            "washer",
            "dish_washer",
            "tv",
            "conditioner",
            "cable_tv",
            "internet",
            "kitchen_furniture",
            "furniture",
            "pantry",
            "niche",
            "cellar",
            "garage",
            "land",
            "has_intercom",
            "sunny",
            "is_basement",
            "is_duplex",
            "is_mansard_floor",
            "can_be_used_as_commercial",
            "is_exchangeable"
        ];
        $addAppartmentFeaturesList = [];


        foreach ($apartmentFeaturesList as $feature) {
            $addAppartmentFeaturesList[] = [
                'name' => $feature,
                'type' => 'switch',
                'label' => trans('estate.' . $feature),
                'tab' => 'Հիմնական',
                'wrapper' => [
                    'class' => 'form-group col-md-3'
                ],
            ];
        }


        CRUD::addField([
            'name' => 'separator12',
            'type' => 'custom_html',
            'value' => '<h4>Շենք/Բնակարան</h4>',
            'tab' => 'Հիմնական',
            'wrapper' => [
                'class' => 'form-group col-md-12 apartment_building_attribute'
            ],
        ]);

        CRUD::addFields($addApartmentBuildingList);

        CRUD::addField([
            'name' => 'service_amount',
            'type' => "number",
            'label' => "Սպասարկման վճար",
            'tab' => 'Հիմնական',
            'wrapper' => [
                'class' => 'form-group col-md-2 apartment_building_attribute'
            ],
        ]);

        CRUD::addField([
            'name' => 'service_amount_currency',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "<br/>",
            'allows_null' => false,
            'default' => 1,
            'placeholder' => '-Ընտրել մեկը-',
            'tab' => 'Հիմնական',
            'wrapper' => [
                'class' => 'form-group col-md-1 apartment_building_attribute'
            ],
        ]);
        CRUD::addField([
            'name' => 'separator1',
            'type' => 'custom_html',
            'value' => '<hr>',
            'tab' => 'Հիմնական',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);
        CRUD::addField([
            'name' => 'separator',
            'type' => 'custom_html',
            'value' => '<h4>Կոմունալ հարմարություններ</h4>',
            'tab' => 'Հիմնական',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::addFields($addAppartmentFeaturesList);
    }

    private function addApartmentColumns(): void
    {

        /*Apartment building attribute*/

        $building_attributes = [
            'building_structure_type',
            'building_type',
            'building_project_type',
            'building_floor_type',
            'exterior_design_type',
            'courtyard_improvement',
            'distance_public_objects',
            'elevator_type',
            'year',
            'parking_type',
            'entrance_type',
            'entrance_door_position',
            'entrance_door_type',
            'windows_view',
            'building_window_count',
            'repairing_type',
            'heating_system_type',
            'service_fee_type',
        ];

        $addApartmentBuildingList = [];

        foreach ($building_attributes as $buildingAttribute) {
            $addApartmentBuildingList[] = [
                'name' => $buildingAttribute,
                'type' => 'relationship',
                'attribute' => "name_arm",
                'label' => trans('estate.' . $buildingAttribute),
                'tab' => 'Հիմնական',
                'className' => 'form-group col-md-6 apartment_building_attribute',
            ];
        }


        $apartmentFeaturesList = [
            "new_construction",
            "apartment_construction",
            "exclusive_design",
            "possible_extension",
            "new_roof",
            "separate_room",
            "balcony",
            "oriel",
            "open_balcony",
            "uninhabited",
            "new_water_tubes",
            "new_wiring",
            "new_windows",
            "new_doors",
            "new_floor",
            "laminat",
            "parquet",
            "heating_ground",
            "new_bathroom",
            "jacuzzi",
            "persistent_water",
            "natural_gas",
            "gas_heater",
            "refrigirator",
            "washer",
            "dish_washer",
            "tv",
            "conditioner",
            "cable_tv",
            "internet",
            "kitchen_furniture",
            "furniture",
            "pantry",
            "niche",
            "cellar",
            "garage",
            "land",
            "has_intercom",
            "sunny",
            "is_basement",
            "is_duplex",
            "is_mansard_floor",
            "can_be_used_as_commercial",
            "is_exchangeable"
        ];
        $addAppartmentFeaturesList = [];


        foreach ($apartmentFeaturesList as $feature) {
            $addAppartmentFeaturesList[] = [
                'name' => $feature,
                'type' => 'check',
                'label' => trans('estate.' . $feature),
                'tab' => 'Հիմնական',
                 'className' => 'form-group col-md-3'
            ];
        }


        CRUD::addColumn([
            'name' => '<h2 class="text-xl">Շենք/Բնակարան</h2>',
            'type' => 'custom_html',
            'value' => ' ',
            'tab' => 'Հիմնական',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumns($addApartmentBuildingList);

        CRUD::addColumn([
            'name' => 'service_amount',
            'type' => "number",
            'label' => "Սպասարկման վճար",
            'tab' => 'Հիմնական',
            'className' => 'form-group col-md-3'
        ]);


        CRUD::addColumn([
            'name' => '<h2 class="text-xl">Կոմունալ հարմարություններ</h4>',
            'type' => 'custom_html',
            'value' => ' ',
            'tab' => 'Հիմնական',
            'className' => 'col-md-12 mt-4 pt-4 mb-4 border-solid  border-t-4  '
        ]);

        CRUD::addColumns($addAppartmentFeaturesList);
    }

    private function addProfessinalTabColumns(): void
    {

        /*Մասնագիտական tab fields*/

        CRUD::addColumn([
            'name' => 'name_arm',
            'type' => "collapse",
            'hideLabel' => true,
            'limit' => 5000,
            'row' => 12,
            'label' => "Մասնագիտական կարծիք, Վերլուծություն",
            'tab' => 'Մասնագիտական',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'additional_info_arm',
            'type' => "collapse",
            'hideLabel' => true,
            'limit' => 5000,
            'row' => 12,
            'label' => "Ինչու ես ձեռք չէի բերի այս գույքը",
            'tab' => 'Մասնագիտական',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);


        CRUD::addColumn([
            'name' => 'comment_arm',
            'type' => "collapse",
            'hideLabel' => true,
            'row' => 12,
            'label' => "Այլ նոթեր",
            'tab' => 'Մասնագիտական',
            'limit' => 10000,
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);




        CRUD::addColumn([
            'name' => 'public_text_arm',
            'type' => "collapse",
            'hideLabel' => true,
            'label' => "Հայտարարության տեքստ (հայերեն)",
            'tab' => 'Մասնագիտական',
            'limit' => 10000,
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);
    }

    private function addAdditionalTabColumns(): void
    {
        $estate = $this->crud->getCurrentEntry();

        /*Լրացուցիչ tab fields*/

        CRUD::addColumn([
            'name' => 'propertyAgent',
            'entity' => 'propertyAgent',
            'type' => "relationship",
            'attribute' => "contactFullName",
            'label' => "Տեղազննող գործակալ",
            'tab' => 'Լրացուցիչ',
            'placeholder' => '-Ընտրել մեկը-',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);


        CRUD::addColumn([
            'name' => 'infoSource',
            'type' => "relationship",
            'ajax' => true,
            'minimum_input_length' => 0,
            'attribute' => "contactFullName",
            'label' => "Ինֆորմացիայի աղբյուր",
            'tab' => 'Լրացուցիչ',
            'placeholder' => '-Ընտրել մեկը-',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'intercom',
            'type' => "text",
            'label' => "Դոմոֆոն",
            'tab' => 'Լրացուցիչ',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'is_advertised',
            'type' => 'switch',
            'label' => 'Գովազդված',
            'tab' => 'Լրացուցիչ',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'address_apartment',
            'type' => 'text',
            'label' => 'Բնակարան',
            'tab' => 'Լրացուցիչ',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'is_urgent',
            'type' => 'switch',
            'label' => 'Շտապ',
            'tab' => 'Լրացուցիչ',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'is_hot_offer',
            'type' => 'switch',
            'label' => 'Թոփ առաջարկներ',
            'tab' => 'Լրացուցիչ',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'estate_status',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Կարգավիճակ",
            'tab' => 'Լրացուցիչ',
            'placeholder' => '-Ընտրել մեկը-',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'archive_till_date',
            'type' => "text",
            'label' => "Արխիվացնել մինչև",
            'tab' => 'Լրացուցիչ',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'archive_comment_arm',
            'type' => "text",
            'label' => "Արխիվացման նշումներ",
            'tab' => 'Լրացուցիչ',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);


        CRUD::addColumn([
            'name' => 'meta_title_arm',
            'type' => "textarea",
            'label' => "Վերնագիր SEO",
            'tab' => 'Լրացուցիչ',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'meta_description_arm',
            'type' => "textarea",
            'label' => "Նկարագրություն SEO",
            'tab' => 'Լրացուցիչ',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        if (in_array($estate->contract_type_id, [2, 3])) {


            Widget::add([
                'type' => 'relation_table',
                'name' => 'rentContracts',
                'label' => 'Վարձակալություն',
                'backpack_crud' => 'rentContracts',
                'relation_attribute' => 'estate_id',
                'buttons' => false,
                'button_create' => false,
                'button_delete' => false,
                'columns' => [
                    [
                        'label' => 'ID',
                        'name' => 'id',
                    ],
                    [
                        'label' => 'Նախնական գին',
                        'name' => 'initial_price',
                    ],
                    [
                        'label' => 'Վերջնական գին',
                        'name' => 'final_price',
                    ],
                    [
                        'label' => 'Սկիզբ',
                        'closure' => function($entry){
                            if(!empty($entry->start_date)) {
                                return Carbon::parse($entry->start_date)->format('d/n/Y');
                            }
                            return null;
                        }
                    ],
                    [
                        'label' => 'Ավարտ',
                        'closure' => function($entry){
                            if(!empty($entry->end_date)) {
                                return Carbon::parse($entry->end_date)->format('d/n/Y');
                            }
                            return null;
                        }
                    ],
                    [
                        'label' => 'Վարձակալ',
                        'name' => 'renter.full_name',
                    ],
                    [
                        'label' => 'Գործակալ',
                        'name' => 'agent.contactFullName',
                    ],
                    [
                        'label' => 'Մեկնաբանություն',
                        'name' => 'comment_arm',
                    ],
                ],
            ])->to('after_content');
        }
    }

    private function addSellerTabColumns(): void
    {

        /*Լրացուցիչ tab fields*/

        CRUD::addColumn([
            'name' => 'seller.name_arm',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Անուն",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);
        CRUD::addColumn([
            'name' => 'seller.last_name_arm',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "last_name_arm",
            'label' => "Ազգանուն",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.email',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "email",
            'label' => "Էլ. հասցե",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.phone_mobile_1',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "phone_mobile_1",
            'label' => "Բջջ. հեռ. 1",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.phone_mobile_2',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "phone_mobile_2",
            'label' => "Բջջ. հեռ. 2",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.phone_mobile_3',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "phone_mobile_3",
            'label' => "Բջջ. հեռ. 3",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.phone_mobile_4',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "phone_mobile_4",
            'label' => "Բջջ. հեռ. 4",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.phone_home',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "phone_home",
            'label' => "Տան հեռ.",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.phone_office',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "phone_office",
            'label' => "Գրասենյակի հեռ.",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.viber',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "viber",
            'label' => "Viber",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.skype',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "skype",
            'label' => "Skype",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.whatsapp',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "whatsapp",
            'label' => "Whatsapp",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.comment_arm',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "comment_arm",
            'label' => "Մեկանբանություն",
            'tab' => 'Վաճառող',
            'limit' => 10000,
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);

        CRUD::addColumn([
            'name' => 'seller.id',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "id",
            'label' => "ID",
            'tab' => 'Վաճառող',
            'className' => 'form-group col-md-4 apartment_building_attribute mt-4 pt-4 mb-4'
        ]);




    }

    public function fetchRenter()
    {
        return $this->fetch([
            'model' => Contact::class,
            'searchable_attributes' => [],
            'paginate' => 100, // items to show per page
            'query' => function ($model) {
                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->whereIn('contact_type_id', [4,5])->whereRaw('CONCAT(`name_eng`," ",`last_name_eng`," ",`name_arm`," ",`last_name_arm`," ",`id`) LIKE "%' . $search . '%"');
                } else {
                    return $model->whereIn('contact_type_id', [4,5]);
                }
            }
        ]);
    }

    public function fetchAgent()
    {
        return $this->fetch([
            'model' => RealtorUser::class,
            'searchable_attributes' => [],
            'paginate' => 1500, // items to show per page
            'query' => function ($model) {
                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->whereHas('contact', function ($query) use($search) {
                        $query->where('contact_type_id', 3)
                            ->whereNotNull('name_arm')
                            ->whereRaw('CONCAT(`name_eng`," ",`last_name_eng`," ",`name_arm`," ",`last_name_arm`," ",`id`) LIKE "%' . $search . '%"');
                    })
                        ->whereHas('roles', function ($query) {
                            $query->whereIn('role_id', [4, 6, 7, 8]);
                        })
                        ->select('realtor_user.*');
                } else {
                    return $model->whereHas('contact', function ($query) {
                        $query->where('contact_type_id', 3)
                            ->whereNotNull('name_arm');
                    })
                        ->whereHas('roles', function ($query) {
                            $query->whereIn('role_id', [4, 6, 7, 8]);
                        })
                        ->select('realtor_user.*');
                }
            }
        ]);
    }

    public function fetchSeller()
    {
        return $this->fetch([
            'model' => Contact::class,
            'searchable_attributes' => [],
            'paginate' => 100,
            'query' => function ($model) {
                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->where('contact_type_id', 1)->where(function ($query) use ($search) {
                        $searchWithSpaces = str_replace(' ', '', $search);
                        $query->whereRaw('CONCAT(`name_eng`, " ", `last_name_eng`, " ", `name_arm`, " ", `last_name_arm`, " ", `id`, " ", REPLACE(`phone_mobile_1`, " ", "")) LIKE ?', ["%$searchWithSpaces%"]);
                    });
                } else {
                    return $model->where('contact_type_id', 1);
                }
            }
        ]);
    }

    public function fetchOwner()
    {
        return $this->fetch([
            'model' => Contact::class,
            'searchable_attributes' => [],
            'paginate' => 100,
            'query' => function ($model) {
                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->where('contact_type_id', 2)->where(function ($query) use ($search) {
                        $searchWithSpaces = str_replace(' ', '', $search);
                        $query->whereRaw('CONCAT(`name_eng`, " ", `last_name_eng`, " ", `name_arm`, " ", `last_name_arm`, " ", `id`, " ", REPLACE(`phone_mobile_1`, " ", "")) LIKE ?', ["%$searchWithSpaces%"]);
                    });
                } else {
                    return $model->where('contact_type_id', 2);
                }
            }
        ]);
    }


    public function downloadEstateImages()
    {
        $estate = $this->crud->getCurrentEntry();
        $directory = $estate->id;
        // Create a temporary directory to store downloaded files
        $tempDir = storage_path('temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // List files in the S3 directory
        $files = Storage::disk('S3')->files('/estate/photos/'.$directory);

        $zipFileName = $directory . '.zip';
        $publicDir = public_path();

        $files = Storage::disk('S3')->files('/estate/photos/' . $directory);

        $zip = new ZipArchive;
        $zip->open($publicDir . '/downloads/' . $zipFileName, ZipArchive::CREATE);

        foreach ($files as $file) {
            $fileContent = Storage::disk('S3')->get($file);
            $zip->addFromString(basename($file), $fileContent);
        }

        $zip->close();

        $zipFileUrl = url($zipFileName);
        return response()->json(['zipFileUrl' => $zipFileUrl]);

    }

    public function clone($id)
    {
        CRUD::hasAccessOrFail('clone');
        CRUD::setOperation('clone');

        // whatever you want

        // if you still want to call the old clone method
        $this->crud->hasAccessOrFail('clone');

        $contract_type = request()->contract_type;

        $currentEntry = $this->crud->model->findOrFail($id);

        $clonedEntry = $this->crud->model->findOrFail($id)->replicate();

        $clonedEntry->contract_type_id = (int)$contract_type;
        $clonedEntry->save();

        $currentEntry->estateDocuments->each(function ($document) use ($clonedEntry) {
            $clonedDocument = $document->replicate();
            $clonedDocument->estate_id = $clonedEntry->id;
            $clonedDocument->save();
        });
        return (string) $clonedEntry->push();
    }

}
