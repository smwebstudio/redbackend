<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RealtorUserRequest;
use App\Models\CProfessionType;
use App\Models\CRole;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class RealtorUserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProfessionalCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\RealtorUser::class);
        $this->crud->addClause('whereHas', 'contact');
        $this->crud->addClause('whereHas', 'professions');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/professional');
        CRUD::setEntityNameStrings('Մասնագետ', 'Մասնագետներ');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $this->crud->addFilter([
            'name' => 'role',
            'type' => 'select2_multiple_red',
            'label' => 'Դեր',
        ], function() { // the options that show up in the select2
            return CRole::all()->pluck('name_arm', 'id')->toArray();
        }, function($values) { // if the filter is active
//            foreach (json_decode($values) as $key => $value) {

                $this->crud->query = $this->crud->query->whereHas('roles', function ($query) use ($values) {
                    $query->whereIn('role_id', json_decode($values));
                })->whereHas('contact');
//            }
        });

        $this->crud->addFilter([
            'name' => 'profession_type',
            'type' => 'select2_multiple_red',
            'label' => 'Մասնագիտության տեսակը',
        ], function() { // the options that show up in the select2
            return CProfessionType::all()->pluck('name_arm', 'id')->toArray();
        }, function($values) { // if the filter is active
                $this->crud->query = $this->crud->query->whereHas('professions', function ($query) use ($values) {
                    $query->whereIn('profession_id', json_decode($values));
                });
        });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_active',
            'label' => 'Ակտիվ'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_active', '=', 1);
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_blocked',
            'label' => 'Արգելափակված'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_blocked', '=', 1);
            });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_4',
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

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_end',
        ]);

        CRUD::column('id');

        CRUD::addColumn([
            'name' => 'professional_info',
            'type' => "markdown",
            'value' => function ($entry) {
            if($entry->profile_picture_path) {
                return '<div>
                    <a style="text-align: left; display: flex; flex-direction: column" href="/admin/professional/' . $entry->id . '/show">
                        <img style="margin-bottom:10px" height="100px" width="100px" src="' . Storage::disk('S3')->temporaryUrl('estate/photos/' . $entry->profile_picture_path, now()->addMinutes(10)) . '" />'
                    . $entry->contact?->name_arm .' '. $entry->contact?->last_name_arm . ' '.$entry->contact?->organization. '
                    </a>
                </div>
                ';
            } else {
                return '<div>
                    <a style="text-align: left; display: flex; flex-direction: column" href="/admin/professional/' . $entry->id . '/show">'
                    . $entry->contact?->name_arm .' '. $entry->contact?->last_name_arm .' '.$entry->contact?->organization. '
                    </a>
                </div>
                ';
            }

            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('contact', function ($q) use ($column, $searchTerm) {
                    $q->where(DB::raw("concat(name_arm, ' ', last_name_arm, ' ', phone_mobile_1, ' ', phone_mobile_2)"), 'LIKE', "%" . $searchTerm . "%");
                });
            },
            'label' => "Կոդ",
            'limit' => 100,
        ]);

//        CRUD::addColumn([
//            'name' => 'contact',
//            'entity' => 'contact',
//            'type' => "select",
//            'model' => "App\Models\Contact",
//            'attribute' => "full_contact",
//            'label' => "Մասնագետներ",
//            'limit' => 150,
//        ]);

        CRUD::addColumn([
            'name' => 'professions',
            'entity' => 'professions',
            'type' => "select_multiple",
            'model' => 'App\Models\CProfessionType',
            'attribute' => "name_arm",
            'label' => "Մասնագիտություններ",
            'limit' => 150,
            'pivot' => true,
        ]);

        CRUD::addColumn([
            'name' => 'roles',
            'entity' => 'roles',
            'type' => "select_multiple",
            'model' => 'App\Models\CRole',
            'attribute' => "name_arm",
            'label' => "Դերեր",
            'limit' => 150,
            'pivot' => true,
        ]);

        CRUD::addColumn([
            'name' => 'phone',
            'entity' => 'contact',
            'type' => "select",
            'model' => "App\Models\Contact",
            'attribute' => "phone_mobile_1",
            'label' => "Հեռ.",
            'limit' => 150,
        ]);
        CRUD::addColumn([
            'name' => 'email',
            'entity' => 'contact',
            'type' => "select",
            'model' => "App\Models\Contact",
            'attribute' => "email",
            'label' => "Էլ. հասցե",
            'limit' => 150,
        ]);

        CRUD::addColumn([
            'name' => 'is_active',
            'type' => "switch",
            'label' => "Ակտիվ",
            'limit' => 150,
        ]);



//        CRUD::column('profile_picture_name');
//        CRUD::column('profile_picture_path');
//        CRUD::column('view_count');
//        CRUD::column('screened_count');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(RealtorUserRequest::class);


        
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
}
