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

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Estate::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/estate');
//        CRUD::setEntityNameStrings('գույք', 'Անշարժ գույք');
        CRUD::setEntityNameStrings('estate', 'Անշարժ գույք');
    }

    protected function setupShowOperation()
    {
//        $this->authorize('create', Estate::class);
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
        $this->crud->removeButton('create' );
        $this->crud->addButton('line', 'archive', 'view', 'crud::buttons.archive');
        $this->crud->addButton('line', 'photo', 'view', 'crud::buttons.photo');
        $this->crud->addButton('line', 'message', 'view', 'crud::buttons.message');
        $this->crud->addButton('line', 'star', 'view', 'crud::buttons.star');

//        CRUD::addColumn([
//            'name' => 'id',
//            'label' => "ID",
//            'searchLogic' => function ($query, $column, $searchTerm) {
//                $query->orWhere('id', 'like', '%' . $searchTerm . '%')->orWhere('code', 'like', '%' . $searchTerm . '%');
//            },
//        ]);

        CRUD::addColumn([
            'name' => 'estate_status_id',
            'type' => "custom_html",
            'label' => "",
            'limit' => 100,
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('id', 'like', '%' . $searchTerm . '%')->orWhere('code', 'like', '%' . $searchTerm . '%');
            },
            'value' => function ($entry) {
                if ($entry->estate_status_id === 1) {
                    return '<i class="las la-file" style="font-size: 24px; color: #C00"  title="Սևագիր"></i>';
                }

                if ($entry->estate_status_id === 2) {
                    return '<i class="las la-file-alt" style="font-size: 24px; color: #939309"  title="Թերի Լրացված"></i>';
                }

                if ($entry->estate_status_id === 3) {
                    return '<i class="las la-camera-retro" style="font-size: 24px; color: #00a2d6"  title="Տեղազնված"></i>';
                }

                if ($entry->estate_status_id === 4) {
                    return '<i class="las la-check-square" style="font-size: 24px; color: #066c3c"  title="Հաստատված"></i>';
                }

                if ($entry->estate_status_id === 6) {
                    return '<i class="las la-tag" style="font-size: 24px; color: #066c3c" title="Վարձակալված"></i>';
                }


                if ($entry->estate_status_id === 7) {
                    return '<i class="las la-calendar-check" style="font-size: 24px; color: #9369aa" title="Վաճառված"></i>';
                }

                if ($entry->estate_status_id === 8) {
                    return '<i class="las la-file-download" style="font-size: 24px; color: #222f3e" title="Արխիվացված"></i>';
                }


                return $entry->estate_status_id;

            },
            'wrapper' => [
                'element' => 'span',
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('article/' . $related_key . '/show');
                },
            ],
        ]);

        CRUD::addColumn([
            'name' => 'full_code',
            'type' => "markdown",
            'value' => function ($entry) {
                return '<div style="text-align: center"><a href="/admin/estate/' . $entry->id . '/show">' . $entry->full_code . '</a></div>';
            },
            'label' => "Կոդ",
            'limit' => 100,
        ]);

        CRUD::addColumn([
            'name' => 'full_address',
            'type' => "text",
            'label' => "Հասցե",
            'limit' => 500,
        ]);

        CRUD::addColumn([
            'name' => 'full_price_with_change',
            'type' => "markdown",
            'label' => "Գին",
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('price', $columnDirection);
            }
        ]);

        CRUD::addColumn([
            'name' => 'area_total',
            'type' => "text",
            'label' => "Մակերես",
            'escaped' => false,
            'suffix' => ' m<sup>2</sup>',
        ]);

        CRUD::addColumn([
            'name' => 'price_per_square',
            'type' => "text",
            'label' => "$/S",
            'suffix' => ' $',
        ]);

        CRUD::addColumn([
            'name' => 'contact',
            'type' => "relationship",
            'attribute' => "full_contact",
            'label' => "Վաճառող",
            'limit' => 150,
        ]);


        CRUD::addColumn([
            'name' => 'main_image_file_path_thumb', // The db column name
            'label' => 'Նկար', // Table column heading
            'type' => 'image',
            'prefix' => '/estate/photos/',
            'disk' => 'S3',
            'height' => '70px',
            'width' => '90px',
        ]);

        CRUD::addColumn([
            'name' => 'created_at', // The db column name
            'label' => 'Ստեղծված', // Table column heading
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'updated_at', // The db column name
            'label' => 'Թարմացված', // Table column heading
            'type' => 'text',
        ]);


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
        Widget::add()->type('script')->content('assets/js/admin/forms/estate.js');

//        if(empty(request()->estateType)) {
//            abort(403);
//        }
        $estateType = request()->estateType;

        $isApartment = request()->estateType === 'apartment';
        $isHouse = request()->estateType === 'house';



        CRUD::addField([
            'name' => 'temporary_photos',
            'label' => 'Photos',
            'type' => "dropzone",
            'configuration' => [
                'parallelUploads' => 2,
                'uploadMultiple' => true,
                'createImageThumbnails' => true,
                'maxFilesize' => 1680000,

            ],
            'withFiles' => ([
                'disk' => 'S3Public',
                'path' => 'uploads/photos',
                'uploader' => 'App\Services\RedAjaxUploader',
            ]),
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
            'tab' => 'Նկարներ',

        ]);
        CRUD::addField([
            'name' => 'estate_type',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Գույքի տեսակ",
            'default' => $estateType,
            'attributes' => [
                'readonly'    => 'readonly',
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
            'name' => 'contact',
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
            'name' => 'room_count',
            'type' => "number",
            'label' => "Սենյակներ",
            'wrapper' => [
                'class' => 'form-group col-md-3'
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
            'value' => '<br/>'
        ]);


        CRUD::addField([
            'name' => 'price',
            'type' => "number",
            'label' => "Գին",
            'wrapper' => [
                'class' => 'form-group col-md-2'
            ],
        ]);

        CRUD::addField([
            'name' => 'currency',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "<br/>",
            'allows_null' => false,
            'default' => 1,
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-1'
            ],
        ]);

        CRUD::addField([
            'name' => 'separator12',
            'type' => 'custom_html',
            'value' => '<h4>Շենք/Բնակարան</h4>',
            'tab' => 'Հիմնական',
        ]);


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

//            'communication_type',
//            'fence_type',
//            'front_with_street',
//            'house_building_type',
//            'house_floors_type',
//            'land_structure_type',
//            'land_type',
//            'land_use_type',
//            'parking_type',
//            'registered_right',
//            'road_way_type',
//            'roof_material_type',
//            'roof_type',
//            'separate_entrance_type',
//            'vitrage_type',
        ];

        $addBuildingList = [];

        foreach ($building_attributes as $buildingAttribute) {
            $addBuildingList[] = [
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


        CRUD::addFields($addBuildingList);

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


        $featuresList = [
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
        $addFeaturesList = [];


        foreach ($featuresList as $feature) {
            $addFeaturesList[] = [
                'name' => $feature,
                'type' => 'switch',
                'label' => trans('estate.' . $feature),
                'tab' => 'Հիմնական',
                'wrapper' => [
                    'class' => 'form-group col-md-3'
                ],
            ];
        }


        CRUD::addFields($addFeaturesList);

        CRUD::addField([
            'name' => 'location',
            'label' => 'Տեղը քարտեզի վրա',
            'type' => 'google_map',
            'tab' => 'Քարտեզ',
            'value' => '{"lat":40.179674748428745,"lng":44.504069898889185}',
            // optionals
            'map_options' => [
                'default_lat' => 40.1783632,
                'default_lng' => 44.51106509999999,
                'locate' => true,
                'height' => 400,
            ]
        ]);


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
            'name' => 'comment_arm',
            'type' => "textarea",
            'row' => 12,
            'label' => "Այլ նոթեր",
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
            'attribute' => "name_arm",
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
                'class' => 'form-group col-md-12'
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
                'class' => 'form-group col-md-12'
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

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected
    function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        $estate = $this->crud->getCurrentEntry();
        CRUD::removeField('estate_type');
        if(!empty($estate->estate_longitude) && !empty($estate->estate_latitude)) {
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


    public function fetchAgent()
    {
        return $this->fetch([
            'model' => Contact::class,
            'searchable_attributes' => [],
            'paginate' => 30, // items to show per page
            'query' => function ($model) {
                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->where('contact_type_id', '=', 1)->whereRaw('CONCAT(`name_eng`," ",`last_name_eng`," ",`name_arm`," ",`last_name_arm`," ",`id`) LIKE "%' . $search . '%"');
                } else {
                    return $model->where('contact_type_id', '=', 1);
                }
            }
        ]);
    }

    public function fetchSeller()
    {
        return $this->fetch([
            'model' => Contact::class,
            'searchable_attributes' => [],
            'paginate' => 30, // items to show per page
            'query' => function ($model) {
                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->where('contact_type_id', '=', 1)->whereRaw('CONCAT(`name_eng`," ",`last_name_eng`," ",`name_arm`," ",`last_name_arm`," ",`id`) LIKE "%' . $search . '%"');
                } else {
                    return $model->where('contact_type_id', '=', 1);
                }
            }
        ]);
    }

    public function fetchPropertyAgent()
    {
        return $this->fetch([
            'model' => Contact::class,
            'searchable_attributes' => [],
            'paginate' => 30, // items to show per page
            'searchOperator' => 'LIKE',
            'query' => function ($model) {
                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->where('contact_type_id', '=', 3)->whereRaw('CONCAT(`name_arm`," ",`last_name_arm`) LIKE "%' . $search . '%"');
                } else {
                    return $model->where('contact_type_id', '=', 3);
                }
            }
        ]);
    }


    public function fetchInfoSource()
    {
        return $this->fetch([
            'model' => Contact::class,
            'searchable_attributes' => [],
            'paginate' => 30, // items to show per page
            'searchOperator' => 'LIKE',
            'query' => function ($model) {
                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->where('contact_type_id', '=', 3)->whereRaw('CONCAT(`name_arm`," ",`last_name_arm`) LIKE "%' . $search . '%"');
                } else {
                    return $model->where('contact_type_id', '=', 3);
                }
            }
        ]);
    }

    public function fetchLocationCity()
    {
        return $this->fetch([
            'model' => CLocationCity::class,
            'searchable_attributes' => [],
            'paginate' => 30, // items to show per page
            'searchOperator' => 'LIKE',
            'query' => function ($model) {
                $params = collect(request()->input('form'))->pluck('value', 'name');
                $provinceId = $params['location_province'];

                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->where('parent_id', '=', $provinceId)->whereRaw('CONCAT(`name_eng`," ",`name_arm`) LIKE "%' . $search . '%"');
                } else {
                    return $model->where('parent_id', '=', $provinceId);
                }
            }
        ]);
    }

    public function fetchLocationCommunity()
    {
        return $this->fetch([
            'model' => CLocationCommunity::class,
            'searchable_attributes' => [],
            'paginate' => 30, // items to show per page
            'searchOperator' => 'LIKE',
            'query' => function ($model) {
                $params = collect(request()->input('form'))->pluck('value', 'name');
                $provinceId = $params['location_province'];
                $search = request()->input('q') ?? false;
                if ($search) {
                    return $model->where('parent_id', '=', $provinceId)->whereRaw('CONCAT(`name_eng`," ",`name_arm`) LIKE "%' . $search . '%"');
                } else {
                    return $model->where('parent_id', '=', $provinceId);
                }
            }
        ]);
    }

    public function fetchLocationStreet()
    {
        return $this->fetch([
            'model' => CLocationStreet::class,
            'searchable_attributes' => [],
            'paginate' => 30, // items to show per page
            'searchOperator' => 'LIKE',
            'query' => function ($model) {
                $params = collect(request()->input('form'))->pluck('value', 'name');
                $cityId = $params['location_city'];
                $communityId = $params['location_community'];
                $search = request()->input('q') ?? false;
                if ($search && $communityId) {
                    return $model->where('parent_is_community', true)->where('community_id', '=', $communityId)->whereRaw('CONCAT(`name_eng`," ",`name_arm`) LIKE "%' . $search . '%"');
                } elseif($search && $cityId) {
                    return $model->where('parent_is_community', false)->where('parent_id', '=', $cityId)->whereRaw('CONCAT(`name_eng`," ",`name_arm`) LIKE "%' . $search . '%"');
                } elseif($communityId) {
                    return $model->where('parent_is_community', true)->where('community_id', '=', $communityId);
                } elseif($cityId) {
                    return $model->where('parent_is_community', false)->where('parent_id', '=', $cityId);
                } else {
                    return $model->where('id', '<', 0);
                }
            }
        ]);
    }

    private function addListFilters(): void
    {


        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'apartment',
            'label' => 'ԲՆԱԿԱՐԱՆ'
        ],
            false,
            function () {
                $this->crud->addClause('apartment');
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'house',
            'label' => 'ԱՌԱՆՁՆԱՏՈՒՆ'
        ],
            false,
            function () {
                $this->crud->addClause('house');
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'Commercial',
            'label' => 'ԿՈՄԵՐՑԻՈՆ'
        ],
            false,
            function () {
                $this->crud->addClause('commercial');
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'land',
            'label' => 'ՀՈՂ'
        ],
            false,
            function () {
                $this->crud->removeAllFilters();
                $this->crud->addClause('land');
            });
        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_types',
        ]);

        // select2 filter
        $this->crud->addFilter([
            'name' => 'contract_type',
            'type' => 'select2',
            'label' => 'Գործարքային տիպը',
        ], function () {
            return [
                1 => 'Վաճառք',
                2 => 'Վարձակալություն',
                3 => 'Օրավարձ',
            ];
        }, function ($value) {
            $this->crud->addClause('where', 'contract_type_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'location_province',
            'type' => 'select2',
            'label' => 'Մարզ',
        ], function () {
            return \App\Models\CLocationProvince::all()->pluck('name_arm', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'location_province_id', $value);
        });

        if (request('location_province')) {
            $province = request('location_province');

            if ($province == 1) {
                $this->crud->addFilter([
                    'name' => 'location_community',
                    'type' => 'select2_multiple_red',
                    'label' => 'Համայնք',
                ], function () {
                    return \App\Models\CLocationCommunity::all()->pluck('name_arm', 'id')->toArray();
                }, function ($values) {
                    $this->crud->addClause('whereIn', 'location_community_id', json_decode($values));
                });
            } else {
                $this->crud->addFilter([
                    'name' => 'location_city',
                    'type' => 'select2',
                    'label' => 'Քաղաք',
                ], function () {
                    $province = request('location_province');
                    return \App\Models\CLocationCity::where('parent_id', '=', json_decode($province))->pluck('name_arm', 'id')->toArray();
                }, function ($value) {
                    $this->crud->addClause('where', 'location_city_id', $value);
                });
            }
        }


        $this->crud->addFilter([
            'name' => 'location_street',
            'type' => 'select2',
            'label' => 'Փողոց',
        ], function () {
            return \App\Models\CLocationStreet::all()->pluck('name_arm', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'location_street_id', $value);
        });

        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'address_building',
            'label' => 'Շենք',
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'address_building', '=', $value);
            });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_9',
        ]);

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'isExclusive',
            'label' => 'Միայն էքսկլուզիվ գույքերը'
        ],
            false,
            function () {
                $this->crud->addClause('isExclusive');
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'isNotExclusive',
            'label' => 'Բացի էքսկլյուզիվ գույքերից'
        ],
            false,
            function () {
                $this->crud->addClause('isNotExclusive');
            });


        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_1',
        ]);




        $this->crud->addFilter([
            'name' => 'price',
            'type' => 'range',
            'label' => 'Գին',
            'label_from' => 'min value',
            'label_to' => 'max value',
        ],
            false,
            function ($value) {
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'price', '>=', (float)$range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'price', '<=', (float)$range->to);
                }
            });

        $this->crud->addFilter([
            'name' => 'currency',
            'type' => 'select2',
            'label' => 'Արժույթ',
        ], function () {
            return \App\Models\CCurrency::all()->pluck('name_arm', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'currency_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'area',
            'type' => 'range',
            'label' => 'Մակերես',
            'label_from' => 'min value',
            'label_to' => 'max value',
        ],
            false,
            function ($value) {
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'area_total', '>=', (float)$range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'area_total', '<=', (float)$range->to);
                }
            });

//        $this->crud->addFilter([
//            'type'  => 'simple',
//            'name'  => 'detailed_area',
//            'label' => 'Ընդլայնված'
//        ],
//            false,
//            function() { // if the filter is active
//                // $this->crud->addClause('active'); // apply the "active" eloquent scope
//            } );

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_3',
        ]);

        $this->crud->addFilter([
            'name' => 'building_project_type',
            'type' => 'select2_multiple_red',
            'label' => 'Շենքի նախագիծը',
        ], function () {
            return \App\Models\CBuildingProjectType::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'building_project_type_id', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'building_type',
            'type' => 'select2_multiple_red',
            'label' => 'Արտաքին պատեր',
        ], function () {
            return \App\Models\CBuildingType::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'building_type_id', json_decode($values));
        });


        $this->crud->addFilter([
            'name' => 'floor_count',
            'type' => 'select2_multiple_red',
            'label' => 'Արտաքին պատեր',
        ], function () {
            return \App\Models\CFloorsQuantity::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'floor_count_id', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'repairing_type',
            'type' => 'select2_multiple_red',
            'label' => 'Վերանորոգման տեսակ',
        ], function () {
            return \App\Models\CRepairingType::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'repairing_type_id', json_decode($values));
        });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_4',
        ]);


        $this->crud->addFilter([
            'name' => 'estate_status',
            'type' => 'select2_multiple_red',
            'label' => 'Կարգավիճակ',
        ], function () {
            return \App\Models\CEstateStatus::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'estate_status_id', json_decode($values));
        });


        $this->crud->addFilter([
            'name' => 'agents',
            'type' => 'select2',
            'label' => 'Գործակալ',
        ], function () {
            return Contact::with('user')->where('contact_type_id', 3)->whereNotNull('name_arm')->get()->pluck('full_name', 'user.id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'agent_id', $value);
        });


        $this->crud->addFilter([
            'name' => 'info_source',
            'type' => 'select2',
            'label' => 'Ինֆորմացիայի աղբյուր',
        ], function () {
            return Contact::with('user')->where('contact_type_id', 3)->whereNotNull('name_arm')->get()->pluck('full_name', 'user.id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'info_source_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'property_agent',
            'type' => 'select2',
            'label' => 'Տեղազննող Գործակալ',
        ], function () {
            return Contact::with('user')->where('contact_type_id', 3)->whereNotNull('name_arm')->get()->pluck('full_name', 'user.id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'property_agent_id', $value);
        });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_4_others',
        ]);


        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_from_public',
            'label' => 'Միայն հայտեր'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_from_public', 1);
            });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_5',
        ]);

        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'created_at_from',
            'label' => 'Ստեղծված սկսած'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'created_at', '>=', $value);
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'created_at_to',
            'label' => 'Ստեղծված մինչև'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'created_at', '<=', $value . ' 23:59:59');
            });

        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'updated_at_from',
            'label' => 'Թարմացված սկսած'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'updated_at', '>=', $value);
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'updated_at_to',
            'label' => 'Թարմացված մինչև'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'updated_at', '<=', $value . ' 23:59:59');
            });

//        $this->crud->addFilter([
//            'type' => 'date_range',
//            'name' => 'modifid_on',
//            'label' => 'Թարմացված'
//        ],
//            false,
//            function ($value) {
//                $dates = json_decode($value);
//                $this->crud->addClause('where', 'updated_at', '>=', $dates->from);
//                $this->crud->addClause('where', 'updated_at', '<=', $dates->to . ' 23:59:59');
//            });

        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'filled_on_from',
            'label' => 'Տեղազնված սկսած'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'filled_on', '>=', $value);
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'filled_on_to',
            'label' => 'Տեղազնված մինչև'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'filled_on', '<=', $value . ' 23:59:59');
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'verified_on_from',
            'label' => 'Հաստատված սկսած'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'verified_on', '>=', $value);
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'verified_on_to',
            'label' => 'Հաստատված մինչև'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'verified_on', '<=', $value . ' 23:59:59');
            });


    }


}
