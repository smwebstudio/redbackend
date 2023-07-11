<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class ContactCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ContactCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Contact::class);
        $this->crud->addClause('where', 'contact_type_id', '!=', 6);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contact');
        CRUD::setEntityNameStrings('կոնտակտ', 'կոնտակտներ');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('full_name');

        CRUD::addColumn([
            'name' => 'contact_type',
            'type' => "relationship",
            'label' => "Կոնտակտի տեսակը",
            'attribute' => "name_arm",
            'limit' => 100,
            'orderable'  => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->orderBy('contact_type_id', $columnDirection);
            }
        ]);

        CRUD::addColumn([
            'name' => 'phone_mobile_1',
            'type' => "text",
            'label' => "Հեռախոս",
        ]);

        CRUD::addColumn([
            'name' => 'email',
            'type' => "text",
            'label' => "Էլ․ հասցե",
        ]);

        CRUD::addColumn([
            'name' => 'created_on',
            'type' => "text",
            'label' => "Ստեղծված",
        ]);

        CRUD::addColumn([
            'name' => 'last_modified_on',
            'type' => "text",
            'label' => "Թարմացված",
        ]);

    }


    protected function setupShowOperation()
    {

        $this->authorize('create', Contact::class);
        CRUD::addColumn([
            'name' => 'contactType',
            'type' => "relationship",
            'label' => "Կոնտակտի տեսակը",
            'attribute' => "name_arm",
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
        CRUD::setValidation(ContactRequest::class);

        $this->authorize('create', Contact::class);

        CRUD::field('is_deleted');
        CRUD::field('last_modified_on');
        CRUD::field('version');
        CRUD::field('email');
        CRUD::field('organization');
        CRUD::field('contact_type_id');
        CRUD::field('phone_mobile_1');
        CRUD::field('phone_mobile_2');
        CRUD::field('phone_office');
        CRUD::field('phone_home');
        CRUD::field('fax');
        CRUD::field('comment_arm');
        CRUD::field('comment_eng');
        CRUD::field('comment_ru');
        CRUD::field('comment_ar');
        CRUD::field('last_modified_by');
        CRUD::field('name_arm');
        CRUD::field('name_eng');
        CRUD::field('name_ru');
        CRUD::field('name_ar');
        CRUD::field('last_name_arm');
        CRUD::field('last_name_eng');
        CRUD::field('last_name_ru');
        CRUD::field('last_name_ar');
        CRUD::field('is_seller');
        CRUD::field('is_buyer');
        CRUD::field('is_rent_owner');
        CRUD::field('is_renter');
        CRUD::field('is_inner_agent');
        CRUD::field('created_on');
        CRUD::field('created_by');
        CRUD::field('is_from_public');
        CRUD::field('estate_type_id');
        CRUD::field('estate_contract_type_id');
        CRUD::field('location_province_id');
        CRUD::field('location_city_id');
        CRUD::field('location_community_id');
        CRUD::field('location_street_id');
        CRUD::field('currency_id');
        CRUD::field('price_from');
        CRUD::field('price_from_usd');
        CRUD::field('price_to');
        CRUD::field('price_to_usd');
        CRUD::field('area_from');
        CRUD::field('area_to');
        CRUD::field('room_count_from');
        CRUD::field('room_count_to');
        CRUD::field('building_type_id');
        CRUD::field('repairing_type_id');
        CRUD::field('new_construction');
        CRUD::field('broker_id');
        CRUD::field('info_source_id');
        CRUD::field('location_building');
        CRUD::field('contact_status_id');
        CRUD::field('is_urgent');
        CRUD::field('web_site');
        CRUD::field('phone_mobile_3');
        CRUD::field('phone_mobile_4');
        CRUD::field('viber');
        CRUD::field('whatsapp');

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
