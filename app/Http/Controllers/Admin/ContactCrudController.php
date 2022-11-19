<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ContactRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Contact::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/contact');
        CRUD::setEntityNameStrings('contact', 'contacts');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('is_deleted');
        CRUD::column('last_modified_on');
        CRUD::column('version');
        CRUD::column('email');
        CRUD::column('organization');
        CRUD::column('contact_type_id');
        CRUD::column('phone_mobile_1');
        CRUD::column('phone_mobile_2');
        CRUD::column('phone_office');
        CRUD::column('phone_home');
        CRUD::column('fax');
        CRUD::column('comment_arm');
        CRUD::column('comment_eng');
        CRUD::column('comment_ru');
        CRUD::column('comment_ar');
        CRUD::column('last_modified_by');
        CRUD::column('name_arm');
        CRUD::column('name_eng');
        CRUD::column('name_ru');
        CRUD::column('name_ar');
        CRUD::column('last_name_arm');
        CRUD::column('last_name_eng');
        CRUD::column('last_name_ru');
        CRUD::column('last_name_ar');
        CRUD::column('is_seller');
        CRUD::column('is_buyer');
        CRUD::column('is_rent_owner');
        CRUD::column('is_renter');
        CRUD::column('is_inner_agent');
        CRUD::column('created_on');
        CRUD::column('created_by');
        CRUD::column('is_from_public');
        CRUD::column('estate_type_id');
        CRUD::column('estate_contract_type_id');
        CRUD::column('location_province_id');
        CRUD::column('location_city_id');
        CRUD::column('location_community_id');
        CRUD::column('location_street_id');
        CRUD::column('currency_id');
        CRUD::column('price_from');
        CRUD::column('price_from_usd');
        CRUD::column('price_to');
        CRUD::column('price_to_usd');
        CRUD::column('area_from');
        CRUD::column('area_to');
        CRUD::column('room_count_from');
        CRUD::column('room_count_to');
        CRUD::column('building_type_id');
        CRUD::column('repairing_type_id');
        CRUD::column('new_construction');
        CRUD::column('broker_id');
        CRUD::column('info_source_id');
        CRUD::column('location_building');
        CRUD::column('contact_status_id');
        CRUD::column('is_urgent');
        CRUD::column('web_site');
        CRUD::column('phone_mobile_3');
        CRUD::column('phone_mobile_4');
        CRUD::column('viber');
        CRUD::column('whatsapp');

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
        CRUD::setValidation(ContactRequest::class);

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
