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

/**
 * Class EstateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CommercialCrudController extends CrudController
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
    use AddEstateCreateCommonFields;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Estate::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/commercial');
        CRUD::setEntityNameStrings('estate', 'Անշարժ գույք');
    }

    protected function setupShowOperation()
    {
        $this->authorize('create', Estate::class);
        CRUD::setShowView('redg.estate.show');
        Widget::add()->type('script')
            ->content('https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js')
            ->crossorigin('anonymous');
        $estate = $this->crud->getCurrentEntry();

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
//        $this->authorize('create', Estate::class);
        CRUD::setValidation(EstateRequest::class);

        CRUD::setOperationSetting('strippedRequest', function ($request) {
            $input = $request->all();
            return $input;
        });

        Widget::add()->type('script')->content('assets/js/admin/forms/estate.js');


        $this->addCommercialFields();
        $this->addCreateCommonFields();

        CRUD::addField([
            'name' => 'public_text_en',
            'type' => "textarea",
            'attributes' => [
                'rows' => 7,
            ],
            'label' => "Հայտարարության տեքստ (ENG)",
            'tab' => 'Թարգմանություն',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::addField([
            'name' => 'public_text_ru',
            'type' => "textarea",
            'attributes' => [
                'rows' => 7,
            ],
            'label' => "Հայտարարության տեքստ (RU)",
            'tab' => 'Թարգմանություն',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);


        CRUD::addField([
            'name' => 'separator77776788',
            'type' => 'custom_html',
            'value' => '<h4>SEO</h4>',
            'tab' => 'Թարգմանություն',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);

        CRUD::addField([
            'name' => 'meta_title_en',
            'type' => "textarea",
            'label' => "Վերնագիր SEO ENG",
            'tab' => 'Թարգմանություն',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::addField([
            'name' => 'meta_description_en',
            'type' => "textarea",
            'label' => "Նկարագրություն SEO ENG",
            'tab' => 'Թարգմանություն',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::addField([
            'name' => 'meta_title_ru',
            'type' => "textarea",
            'label' => "Վերնագիր SEO RU",
            'tab' => 'Թարգմանություն',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::addField([
            'name' => 'meta_description_ru',
            'type' => "textarea",
            'label' => "Նկարագրություն SEO RU",
            'tab' => 'Թարգմանություն',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);
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


    private function addCreateCommonFields(): void
    {

        /*Basic fields*/

        CRUD::addField([
            'name' => 'estate_type_id',
            'type' => 'number',
            'attribute' => "name_arm",
            'label' => "Գույքի տեսակ",
            'default' => 3,
            'attributes' => [
                'readonly' => 'readonly',
            ],
            'placeholder' => '-Ընտրել մեկը-',
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
            'name' => 'is_separate_building',
            'type' => "switch",
            'label' => "Առանձին շինություն",
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ],
        ]);


        CRUD::addField([
            'name' => 'address_apartment',
            'type' => "text",
            'label' => "Տարածքի համար",
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
            'label' => "Սրահներ",
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ],
        ]);


        CRUD::addField([
            'name' => 'room_count_modified',
            'type' => "number",
            'label' => "Սրահներ (mod)",
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






        /*Մասնագիտական tab fields*/






        /*Լրացուցիչ tab fields*/
        /*Նկարներ tab fields*/

        $this->addCreateDropzoneField();

        /*Քարտեզ tab fields*/

        $this->addCreateMapField();

        /*Մասնագիտական tab fields*/

        $this->addCreateProfessionalFields();

        /*Լրացուցիչ tab fields*/

        $this->addCreateAdditionalFields();

    }

    private function addCommercialFields(): void
    {


        /*Apartment building attribute*/

        $building_attributes = [
            'commercial_purpose_type',
            'building_structure_type',
            'building_type',
            'building_floor_type',
            'exterior_design_type',
            'courtyard_improvement',
            'distance_public_objects',
            'year',
            'parking_type',
            'entrance_door_type',
            'windows_view',
            'building_window_count',
            'repairing_type',
            'heating_system_type',
            'separate_entrance_type',
            'vitrage_type'
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
        ]);

        CRUD::addFields($addApartmentBuildingList);

        CRUD::addField([
            'name' => 'is_estate_commercial_land',
            'type' => "switch",
            'label' => "Հող",
            'tab' => 'Հիմնական',
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);


        $land_attributes = [
            'land_type',
            'land_use_type',
            'land_structure_type',
            'communication_type',
            'fence_type',
            'road_way_type',
            'front_with_street',
        ];

        foreach ($land_attributes as $landAttribute) {
            $addLandList[] = [
                'name' => $landAttribute,
                'type' => 'relationship',
                'attribute' => "name_arm",
                'label' => trans('estate.' . $landAttribute),
                'placeholder' => '-Ընտրել մեկը-',
                'tab' => 'Հիմնական',
                'wrapper' => [
                    'class' => 'form-group col-md-3 apartment_building_attribute'
                ],
            ];
        }

        CRUD::addFields($addLandList);

        CRUD::addField([
            'name' => 'front_length',
            'type' => "number",
            'label' => "Ճակատային դիրքի երկարություն",
            'tab' => 'Հիմնական',
            'wrapper' => [
                'class' => 'form-group col-md-3 apartment_building_attribute'
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

}
