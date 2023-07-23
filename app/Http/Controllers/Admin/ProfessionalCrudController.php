<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RealtorUserRequest;
use App\Models\CRole;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/professional');
        CRUD::setEntityNameStrings('professional', 'professionals');
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
            'name' => 'c_role',
            'type' => 'select2_multiple',
            'label' => 'Role',
        ], function() { // the options that show up in the select2
            return CRole::all()->pluck('name_arm', 'id')->toArray();
        }, function($values) { // if the filter is active
            foreach (json_decode($values) as $key => $value) {
                $this->crud->query = $this->crud->query->whereHas('roles', function ($query) use ($value) {
                    $query->where('role_id', $value);
                })->whereHas('professions', function ($query) use ($value) {
                    $query->whereIn('professon_id', []);
                });
            }
        });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_4',
        ]);

        CRUD::column('id');
        CRUD::column('contact_id');
        CRUD::column('username');
        CRUD::column('profession_type_id');
        CRUD::column('is_from_public');
        CRUD::column('is_active');
        CRUD::column('is_blocked');
//        CRUD::column('profile_picture_name');
//        CRUD::column('profile_picture_path');
//        CRUD::column('view_count');
        CRUD::column('party_type_id');
//        CRUD::column('screened_count');
        CRUD::column('packet_type_id');
        CRUD::column('packet_start_date');
        CRUD::column('packet_end_date');
        CRUD::column('menu_location_province_id');
        CRUD::column('permission_menu_packet_type_id');
        CRUD::column('permission_menu_packet_start_date');
        CRUD::column('permission_menu_packet_end_date');
        CRUD::column('permission_menu_location_province_id');

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

        CRUD::field('contact_id');
        CRUD::field('username');
        CRUD::field('password');
        CRUD::field('version');
        CRUD::field('is_deleted');
        CRUD::field('profession_type_id');
        CRUD::field('last_modified_on');
        CRUD::field('last_modified_by');
        CRUD::field('created_on');
        CRUD::field('created_by');
        CRUD::field('is_from_public');
        CRUD::field('is_active');
        CRUD::field('is_blocked');
        CRUD::field('profile_picture_name');
        CRUD::field('profile_picture_path');
        CRUD::field('activation_code');
        CRUD::field('view_count');
        CRUD::field('party_type_id');
        CRUD::field('contact_visits_count');
        CRUD::field('screened_count');
        CRUD::field('packet_type_id');
        CRUD::field('packet_start_date');
        CRUD::field('packet_end_date');
        CRUD::field('menu_location_province_id');
        CRUD::field('meta_title_eng');
        CRUD::field('meta_title_arm');
        CRUD::field('meta_title_ru');
        CRUD::field('meta_description_eng');
        CRUD::field('meta_description_arm');
        CRUD::field('meta_description_ru');
        CRUD::field('permission_menu_packet_type_id');
        CRUD::field('permission_menu_packet_start_date');
        CRUD::field('permission_menu_packet_end_date');
        CRUD::field('permission_menu_location_province_id');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
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
