<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RealtorUserRequest;
use App\Models\Contact;
use App\Models\CProfessionType;
use App\Models\CRole;
use App\Traits\Controllers\AddContactListColumns;
use App\Traits\Controllers\HasContactFilters;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
    use AuthorizesRequests;
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

    protected function setupShowOperation()
    {
        CRUD::setShowView('redg.professional.showTabs');


        $professional = $this->crud->getCurrentEntry();
        $this->crud->data['professional'] = $professional;
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
        }, function($values) {
                $this->crud->query = $this->crud->query->whereHas('roles', function ($query) use ($values) {
                    $query->whereIn('role_id', json_decode($values));
                })->whereHas('contact');
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
        Widget::add()->type('script')->content('assets/js/admin/forms/estate.js');
        $this->crud->setCreateContentClass('col-md-8');

        CRUD::addField([
            'name' => 'is_organization',
            'type' => "switch",
            'label' => "Կազմակերպություն",
            'wrapper' => [
                'class' => 'form-group col-md-12'
            ],
        ]);

        CRUD::addField([
            'name' => 'name_arm',
            'type' => "text",
            'label' => "Անուն",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'last_name_arm',
            'type' => "text",
            'label' => "Ազգանուն",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'name_en',
            'type' => "text",
            'label' => "Անուն (ENG)",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'last_name_en',
            'type' => "text",
            'label' => "Ազգանուն (ENG)",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'name_ru',
            'type' => "text",
            'label' => "Անուն (RU)",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'last_name_ru',
            'type' => "text",
            'label' => "Ազգանուն (RU)",
            'wrapper' => [
                'class' => 'form-group col-md-6'
            ],
        ]);

        CRUD::addField([
            'name' => 'organization',
            'type' => "text",
            'label' => "Կազմակերպության անվանում",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'separator1',
            'type' => 'custom_html',
            'value' => '<br/>',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);

        CRUD::addField([
            'name' => 'email',
            'type' => "text",
            'label' => "Էլ. հասցե",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'password',
            'type' => "password",
            'label' => "Գաղտնաբառ",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'separator2',
            'type' => 'custom_html',
            'value' => '<br/>',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);

        CRUD::addField([
            'name' => 'phone_mobile_1',
            'type' => "text",
            'label' => "Բջջ. հեռ. 1",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'phone_mobile_2',
            'type' => "text",
            'label' => "Բջջ. հեռ. 2",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'phone_office',
            'type' => "text",
            'label' => "Գրասենյակի հեռ.",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'viber',
            'type' => "text",
            'label' => "Viber",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'whatsapp',
            'type' => "text",
            'label' => "WhatsApp",
            'wrapper' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        CRUD::addField([
            'name' => 'separator3',
            'type' => 'custom_html',
            'value' => '<br/>',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);

        CRUD::addField([   // Checklist
            'label'     => 'Մասնագիտության տեսակը',
            'type'      => 'checklist',
            'name'      => 'professions',
            'entity'    => 'professions',
            'attribute' => 'name_arm',
            'model'     => "App\Models\CProfessionType",
            'pivot'     => true,
             'number_of_columns' => 3,
        ]);

        CRUD::addField([
            'name' => 'separator4',
            'type' => 'custom_html',
            'value' => '<br/>',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);

        CRUD::addField([   // Checklist
            'label'     => 'Դեր',
            'type'      => 'select_multiple',
            'name'      => 'inner_roles',
            'entity'    => 'roles',
            'attribute' => 'name_arm',
            'model'     => "App\Models\CRole",
            'pivot'     => true,
            'options'   => (function ($query) {
                return $query->whereIn('id', [1,2,4,5])->get();
            }),
        ]);

        CRUD::addField([
            'name' => 'separator5',
            'type' => 'custom_html',
            'value' => '<br/>',
            'wrapper' => [
                'class' => 'form-group col-md-12 separator'
            ],
        ]);

        CRUD::addField([
            'name' => 'profile_picture_path',
            'label'        => "Profile Image",
            'type'         => 'image',
            'disk'         => 'S3',
            'aspect_ratio' => 1, // set to 0 to allow any aspect ratio
            'crop'         => true, // set to true to allow cropping, false to disable
            'withFiles' => ([
                'disk' => 'S3',
                'path' => 'professionals/',
            ]),
        ]);

    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->crud->setCreateContentClass('col-md-8');
        $this->setupCreateOperation();
    }
}
