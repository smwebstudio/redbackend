<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Admin\Operations\RedDropZoneOperation;
use App\Http\Requests\EstateRequest;
use App\Models\CLocationCity;
use App\Models\CLocationCommunity;
use App\Models\CLocationStreet;
use App\Models\Contact;
use App\Models\Estate;
use App\Models\RealtorUser;
use App\Traits\Controllers\AddEstateFetchMethods;
use App\Traits\Controllers\AddEstateListColumns;
use App\Traits\Controllers\HasEstateFilters;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Backpack\Pro\Http\Controllers\Operations\FetchOperation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CloneOperation;
    use RedDropZoneOperation;
    use FetchOperation;
    use AuthorizesRequests;
    use HasEstateFilters;
    use AddEstateListColumns;
    use AddEstateFetchMethods;

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
        Widget::add()->type('script')
            ->content('https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js')
            ->crossorigin('anonymous');
        $estate = $this->crud->getCurrentEntry();

        $this->addApartmentColumns();
        $this->addProfessinalTabColumns();
        $this->addAdditionalTabColumns();
        $this->addSellerTabColumns();
        $this->crud->data['estate'] = $estate;
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
        $this->crud->addButton('top', 'estate_create_buttons_set', 'view', 'crud::buttons.estate_create_buttons_set');
        $this->crud->removeButton('create');
        $this->crud->addButton('line', 'archive', 'view', 'crud::buttons.archive');
        $this->crud->addButton('line', 'photo', 'view', 'crud::buttons.photo');
        $this->crud->addButton('line', 'message', 'view', 'crud::buttons.message');
        $this->crud->addButton('line', 'star', 'view', 'crud::buttons.star');


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

        CRUD::setOperationSetting('strippedRequest', function ($request) {
            $input = $request->all();
            return $input;
        });

        Widget::add()->type('script')->content('assets/js/admin/forms/estate.js');

        $estateType = $this->crud->getRequest()->get('estateType');

        $this->addApartmentFields();
        $this->addCreateCommonFields($estateType);

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        $estate = $this->crud->getCurrentEntry();
        CRUD::setOperationSetting('strippedRequest', function ($request) {
            $input = $request->all();
            return $input;
        });
        $estateType = request()->estateType;
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


    private function addCreateCommonFields($estateType): void
    {

        /*Basic fields*/

        CRUD::addField([
            'name' => 'estate_type_id',
            'type' => 'number',
            'attribute' => "name_arm",
            'label' => "Գույքի տեսակ",
            'default' => 1,
            'attributes' => [
                'readonly' => 'readonly',
            ],
            'wrapper' => [
                'class' => 'form-group col-md-3 d-none'
            ],
        ]);

        CRUD::addField([
            'name' => 'contract_type',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Կոնտրակտի տեսակ",
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'seller',
            'entity' => 'seller',
            'type' => "relationship",
            'attribute' => "fullContact",
            'placeholder' => '-Ընտրել մեկը-',
            'ajax' => true,
            'minimum_input_length' => 0,
            'label' => "Վաճառող",
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::addField([
            'name' => 'location_province',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Մարզ",
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-3 '
            ],
        ]);

        CRUD::addField([
            'name' => 'location_city',
            'type' => "relationship",
            'attribute' => "name_arm",
            'ajax' => true,
            'minimum_input_length' => 0,
            'include_all_form_fields' => true,
            'label' => "Քաղաք",
            'dependencies' => ['location_province'],
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-3 '
            ],
        ]);

        CRUD::addField([
            'name' => 'location_community',
            'type' => "relationship",
            'attribute' => "name_arm",
            'ajax' => true,
            'minimum_input_length' => 0,
            'dependencies' => ['location_province'],
            'label' => "Համայնք",
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        CRUD::addField([
            'name' => 'location_street',
            'type' => "relationship",
            'attribute' => "name_arm",
            'ajax' => true,
            'minimum_input_length' => 0,
            'dependencies' => ['location_province'],
            'label' => "Փողոց",
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);


        CRUD::addField([
            'name' => 'address_building',
            'type' => "text",
            'label' => "Շենք",
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        CRUD::addField([
            'name' => 'address_apartment',
            'type' => "text",
            'label' => "Բնակարան",
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        CRUD::addField([
            'name' => 'floor_separator',
            'type' => 'custom_html',
            'value' => '<br/>',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);

        CRUD::addField([
            'name' => 'floor',
            'type' => "number",
            'label' => "Հարկ",
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        CRUD::addField([
            'name' => 'building_floor_count',
            'type' => "number",
            'label' => "Շենքի հարկ",
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);


        CRUD::addField([
            'name' => 'ceiling_height_type',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Առաստաղի բարձրություն",
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);


        CRUD::addField([
            'name' => 'rooms_separator',
            'type' => 'custom_html',
            'value' => '<br/>',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);


        CRUD::addField([
            'name' => 'room_count',
            'type' => "number",
            'label' => "Սենյակներ",
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ],
        ]);


        CRUD::addField([
            'name' => 'room_count_modified',
            'type' => "number",
            'label' => "Սենյակներ (mod)",
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ],
        ]);


        CRUD::addField([
            'name' => 'area_total',
            'type' => "number",
            'label' => "Ընդհանուր մակերես",
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);


        CRUD::addField([
            'name' => 'separator123',
            'type' => 'custom_html',
            'value' => '<br/>',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);


        CRUD::addField([
            'name' => 'price_amd',
            'type' => "number",
            'label' => "Գին",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
            "suffix" => 'AMD'
        ]);

//        CRUD::addField([
//            'name' => 'currency',
//            'type' => "relationship",
//            'attribute' => "name_arm",
//            'label' => "<br/>",
//            'allows_null' => false,
//            'default' => 1,
//            'placeholder' => '-Ընտրել մեկը-',
//            'wrapper' => [
//                'class' => 'form-group col-md-1'
//            ],
//        ]);


        /*Նկարներ tab fields*/

        CRUD::addField([
            'name' => 'temporary_photos',
            'label' => 'Նկարներ',
            'type' => "dropzone",
            'configuration' => [
                'parallelUploads' => 2,
                'uploadMultiple' => true,
                'createImageThumbnails' => true,
                'maxFilesize' => 1680000,

            ],
            'withFiles' => ([
                'disk' => 'S3Public',
                'path' => 'uploads/tmp',
                'uploader' => 'App\Services\RedAjaxUploader',
            ]),
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
            'tab' => 'Նկարներ',

        ]);

        /*Քարտեզ tab fields*/

        CRUD::addField([
            'name' => 'location',
            'label' => 'Տեղը քարտեզի վրա',
            'type' => 'google_map',
            'tab' => 'Քարտեզ',
            'value' => '{"lat":40.179674748428745,"lng":44.504069898889185}',
            'map_options' => [
                'default_lat' => 40.1783632,
                'default_lng' => 44.51106509999999,
                'locate' => true,
                'height' => 400,
            ]
        ]);

        /*Մասնագիտական tab fields*/

        CRUD::addField([
            'name' => 'name_arm',
            'type' => "textarea",
            'row' => 12,
            'label' => "Մասնագիտական կարծիք, Վերլուծություն",
            'tab' => 'Մասնագիտական',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::addField([
            'name' => 'additional_info_arm',
            'type' => "textarea",
            'row' => 12,
            'label' => "Ինչու ես ձեռք չէի բերի այս գույքը",
            'tab' => 'Մասնագիտական',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);


        CRUD::addField([
            'name' => 'comment_arm',
            'type' => "textarea",
            'row' => 12,
            'label' => "Այլ նոթեր",
            'tab' => 'Մասնագիտական',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);


        CRUD::addField([
            'name' => 'is_public_text_generation',
            'type' => "switch",
            'label' => "Ավտո տեքստ",
            'tab' => 'Մասնագիտական',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::addField([
            'name' => 'public_text_arm',
            'type' => "textarea",
            'attributes' => [
                'rows' => 7,
            ],
            'label' => "Հայտարարության տեքստ (հայերեն)",
            'tab' => 'Մասնագիտական',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);


        /*Լրացուցիչ tab fields*/

        CRUD::addField([
            'name' => 'propertyAgent',
            'entity' => 'propertyAgent',
            'type' => "relationship",
            'ajax' => true,
            'minimum_input_length' => 0,
            'attribute' => "name_arm",
            'label' => "Տեղազննող գործակալ",
            'tab' => 'Լրացուցիչ',
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);


        CRUD::addField([
            'name' => 'infoSource',
            'type' => "relationship",
            'ajax' => true,
            'minimum_input_length' => 0,
            'attribute' => "contactFullName",
            'label' => "Ինֆորմացիայի աղբյուր",
            'tab' => 'Լրացուցիչ',
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        CRUD::addField([
            'name' => 'intercom',
            'type' => "text",
            'label' => "Դոմոֆոն",
            'tab' => 'Լրացուցիչ',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        CRUD::addField([
            'name' => 'separator77777',
            'type' => 'custom_html',
            'tab' => 'Լրացուցիչ',
            'value' => '<hr/>',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);

        CRUD::addField([
            'name' => 'is_advertised',
            'type' => 'switch',
            'label' => 'Գովազդված',
            'tab' => 'Լրացուցիչ',
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ],
        ]);

        CRUD::addField([
            'name' => 'is_urgent',
            'type' => 'switch',
            'label' => 'Շտապ',
            'tab' => 'Լրացուցիչ',
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ],
        ]);

        CRUD::addField([
            'name' => 'is_hot_offer',
            'type' => 'switch',
            'label' => 'Թոփ առաջարկներ',
            'tab' => 'Լրացուցիչ',
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ],
        ]);

        CRUD::addField([
            'name' => 'estate_status',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Կարգավիճակ",
            'tab' => 'Լրացուցիչ',
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        CRUD::addField([
            'name' => 'separator77776',
            'type' => 'custom_html',
            'tab' => 'Լրացուցիչ',
            'value' => '<h4>SEO</h4>',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);

        CRUD::addField([
            'name' => 'meta_title_arm',
            'type' => "textarea",
            'label' => "Վերնագիր SEO",
            'tab' => 'Լրացուցիչ',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::addField([
            'name' => 'meta_description_arm',
            'type' => "textarea",
            'label' => "Նկարագրություն SEO",
            'tab' => 'Լրացուցիչ',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

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
                'class' => 'form-group col-md-2 apartment_building_attribute'
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
            'type' => "textarea",
            'row' => 12,
            'label' => "Մասնագիտական կարծիք, Վերլուծություն",
            'tab' => 'Մասնագիտական',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'additional_info_arm',
            'type' => "textarea",
            'row' => 12,
            'label' => "Ինչու ես ձեռք չէի բերի այս գույքը",
            'tab' => 'Մասնագիտական',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);


        CRUD::addColumn([
            'name' => 'comment_arm',
            'type' => "text",
            'row' => 12,
            'label' => "Այլ նոթեր",
            'tab' => 'Մասնագիտական',
            'limit' => 10000,
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);


        CRUD::addColumn([
            'name' => 'is_public_text_generation',
            'type' => "switch",
            'label' => "Ավտո տեքստ",
            'tab' => 'Մասնագիտական',
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);

        CRUD::addColumn([
            'name' => 'public_text_arm',
            'type' => "text",
            'label' => "Հայտարարության տեքստ (հայերեն)",
            'tab' => 'Մասնագիտական',
            'limit' => 10000,
            'className' => 'form-group col-md-12 apartment_building_attribute mt-4 pt-4 mb-4 border-solid  border-t-4'
        ]);
    }

    private function addAdditionalTabColumns(): void
    {

        /*Լրացուցիչ tab fields*/

        CRUD::addColumn([
            'name' => 'propertyAgent',
            'entity' => 'propertyAgent',
            'type' => "relationship",
            'ajax' => true,
            'minimum_input_length' => 0,
            'attribute' => "name_arm",
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

}
