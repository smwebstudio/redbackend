<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\EstateRequest;
use App\Models\Contact;
use App\Models\RealtorUser;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\Pro\Http\Controllers\Operations\FetchOperation;

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
    use FetchOperation;


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Estate::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/estate');
        CRUD::setEntityNameStrings('Անշարժ Գույք', 'Անշարժ Գույք');
    }

    protected function setupShowOperation()
    {
        CRUD::addColumn([
            'name' => 'estateDocuments',
            'type' => "relationship",
            'attribute' => "path",
            'label' => "ID",
            'limit' => 100,
        ]);

        CRUD::addColumn([
            'name' => 'estate_type',
            'type' => "relationship",
            'label' => "EstateResource type",
            'attribute' => "name_arm",
            'limit' => 100,
        ]);

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'id',
            'type' => "text",
            'label' => "ID",
            'limit' => 100,
        ]);

        CRUD::addColumn([
            'name' => 'estate_status_id',
            'type' => "custom_html",
            'label' => "Status",
            'limit' => 100,
            'value' => function ($entry) {
                if ($entry->estate_status_id === 8) {
                    return '<i class="las la-file-archive"></i>';
                }

                if ($entry->estate_status_id === 4) {
                    return '<i class="las la-check-square"></i>';
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
            'name' => 'code',
            'type' => "text",
            'label' => "Code",
            'limit' => 100,
        ]);

        CRUD::addColumn([
            'name' => 'estate_type',
            'type' => "relationship",
            'label' => "EstateResource type",
            'attribute' => "name_arm",
            'limit' => 100,
        ]);

        CRUD::addColumn([
            'name' => 'contract_type',
            'type' => "relationship",
            'label' => "Contract type",
            'attribute' => "name_arm",
            'limit' => 100,
        ]);

        CRUD::addColumn([
            'name' => 'full_address',
            'type' => "text",
            'label' => "Address",
            'limit' => 500,
        ]);

        CRUD::addColumn([
            'name' => 'full_price',
            'type' => "text",
            'label' => "Price",
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('price', $columnDirection);
            }
        ]);

        CRUD::addColumn([
            'name' => 'area_total',
            'type' => "text",
            'label' => "Area",
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
            'label' => "Seller",
            'limit' => 150,
        ]);


        CRUD::addColumn([
            'name' => 'main_image_file_path_thumb', // The db column name
            'label' => 'Image', // Table column heading
            'type' => 'image',
            'disk' => 'S3',
            'height' => '70px',
            'width' => '90px',
        ]);

        CRUD::addColumn([
            'name' => 'created_on',
            'type' => "date",
            'label' => "Created at",
        ]);

        CRUD::addColumn([
            'name' => 'last_modified_on',
            'type' => "date",
            'label' => "Updated at",
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
        CRUD::setValidation(EstateRequest::class);

        CRUD::addField([
            'name' => 'building_structure_type',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Շենքի կառուցվածք",
            'placeholder' => '-Ընտրել մեկը-',
            'tab' => 'Main',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);

        CRUD::addField([
            'name' => 'building_floor_type',
            'type' => "relationship",
            'attribute' => "name_arm",
            'label' => "Արտաքին պատեր",
            'tab' => 'Main',
            'placeholder' => '-Ընտրել մեկը-',
            'wrapper' => [
                'class' => 'form-group col-md-3'
            ],
        ]);


        $this->crud->setFromDb();

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }


    public function fetchContact()
    {
        return $this->fetch([
            'model' => Contact::class,
            'searchable_attributes' => ['name_arm', 'last_name_arm'],
            'paginate' => 10, // items to show per page
            'searchOperator' => 'LIKE',
            'query' => function ($model) {
                return $model->where('contact_type_id', '=', 3);
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
                $this->crud->addClause('land');
            });
        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_types',
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


        // select2 filter
        $this->crud->addFilter([
            'name' => 'contract_type',
            'type' => 'select2',
            'label' => 'Տիպ',
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
        }, function ($value) { // if the filter is active
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
                }, function ($values) { // if the filter is active
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
                }, function ($value) { // if the filter is active
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
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'location_street_id', $value);
        });

        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'address_building',
            'label' => 'Շենք',
        ],
            false,
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'address_building', '=', $value);
            });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_2',
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
        }, function ($values) { // if the filter is active
            $this->crud->addClause('whereIn', 'building_project_type_id', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'building_type',
            'type' => 'select2_multiple_red',
            'label' => 'Արտաքին պատեր',
        ], function () {
            return \App\Models\CBuildingType::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) { // if the filter is active
            $this->crud->addClause('whereIn', 'building_type_id', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'repairing_type',
            'type' => 'select2_multiple_red',
            'label' => 'Վերանորոգման տեսակ',
        ], function () {
            return \App\Models\CRepairingType::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) { // if the filter is active
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
        }, function ($values) { // if the filter is active
            $this->crud->addClause('whereIn', 'estate_status_id', json_decode($values));
        });


        $this->crud->addFilter([
            'name' => 'agents',
            'type' => 'select2',
            'label' => 'Գործակալ',
        ], function () {
            return Contact::with('user')->where('contact_type_id', 3)->whereNotNull('name_arm')->get()->pluck('full_name', 'user.id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'agent_id', $value);
        });


        $this->crud->addFilter([
            'name' => 'info_source',
            'type' => 'select2',
            'label' => 'Ինֆորմացիայի աղբյուր',
        ], function () {
            return Contact::with('user')->where('contact_type_id', 3)->whereNotNull('name_arm')->get()->pluck('full_name', 'user.id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'info_source_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'property_agent',
            'type' => 'select2',
            'label' => 'Տեղազննող Գործակալ',
        ], function () {
            return Contact::with('user')->where('contact_type_id', 3)->whereNotNull('name_arm')->get()->pluck('full_name', 'user.id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'property_agent_id', $value);
        });


        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_5',
        ]);

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'created_on',
            'label' => 'Ստեղծված'
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'created_on', '>=', $dates->from);
                $this->crud->addClause('where', 'created_on', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'modifid_on',
            'label' => 'Թարմացված'
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'last_modified_on', '>=', $dates->from);
                $this->crud->addClause('where', 'last_modified_on', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'examined_on',
            'label' => 'Տեղազնված'
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'last_modified_on', '>=', $dates->from);
                $this->crud->addClause('where', 'last_modified_on', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addFilter([
            'type' => 'date_range',
            'name' => 'approved_on',
            'label' => 'Հաստատված'
        ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'last_modified_on', '>=', $dates->from);
                $this->crud->addClause('where', 'last_modified_on', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_6',
        ]);

    }
}
