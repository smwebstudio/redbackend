<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EstateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

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

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Estate::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/estate');
        CRUD::setEntityNameStrings('estate', 'estates');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('location_country_id');
        CRUD::column('location_province_id');
        CRUD::column('location_city_id');
        CRUD::column('location_community_id');
        CRUD::column('location_street_id');
        CRUD::column('estate_type_id');
        CRUD::column('area_total');
        CRUD::column('old_price');
        CRUD::column('price');
        CRUD::column('currency_id');
        CRUD::column('seller_id');
        CRUD::column('area_residential');
        CRUD::column('registered_right_id');
        CRUD::column('repairing_type_id');
        CRUD::column('room_count');
        CRUD::column('building_type_id');
        CRUD::column('building_project_type_id');
        CRUD::column('conditioner');
        CRUD::column('room_count_modified');
        CRUD::column('exterior_design_type_id');
        CRUD::column('elevator_type_id');
        CRUD::column('year_id');
        CRUD::column('heating_system_type_id');
        CRUD::column('parking_type_id');
        CRUD::column('service_fee_type_id');
        CRUD::column('service_amount');
        CRUD::column('service_amount_currency_id');
        CRUD::column('furniture');
        CRUD::column('kitchen_furniture');
        CRUD::column('gas_heater');
        CRUD::column('persistent_water');
        CRUD::column('natural_gas');
        CRUD::column('gas_possibility');
        CRUD::column('internet');
        CRUD::column('satellite_tv');
        CRUD::column('cable_tv');
        CRUD::column('sunny');
        CRUD::column('exclusive_design');
        CRUD::column('expanding_possible');
        CRUD::column('open_balcony');
        CRUD::column('oriel');
        CRUD::column('new_wiring');
        CRUD::column('new_water_tubes');
        CRUD::column('heating_ground');
        CRUD::column('plastic_windows');
        CRUD::column('parquet');
        CRUD::column('laminat');
        CRUD::column('equipped');
        CRUD::column('roof_type_id');
        CRUD::column('floor_count_id');
        CRUD::column('house_building_type_id');
        CRUD::column('roof_repaired');
        CRUD::column('roof_material_type_id');
        CRUD::column('fence_type_id');
        CRUD::column('communication_type_id');
        CRUD::column('front_with_street_id');
        CRUD::column('road_way_type_id');
        CRUD::column('commercial_purpose_type_id');
        CRUD::column('communication_id');
        CRUD::column('land_structure_type_id');
        CRUD::column('land_type_id');
        CRUD::column('land_use_type_id');
        CRUD::column('front_length');
        CRUD::column('version');
        CRUD::column('address_building');
        CRUD::column('address_apartment');
        CRUD::column('contract_type_id');
        CRUD::column('entrance_door_type_id');
        CRUD::column('entrance_door_position_id');
        CRUD::column('windows_view_id');
        CRUD::column('building_floor_count');
        CRUD::column('house_floors_type_id');
        CRUD::column('roof_drainage');
        CRUD::column('new_doors');
        CRUD::column('new_windows');
        CRUD::column('new_bathroom');
        CRUD::column('new_floor');
        CRUD::column('new_roof');
        CRUD::column('can_be_used_as_commercial');
        CRUD::column('is_published');
        CRUD::column('estate_status_id');
        CRUD::column('status_changed_on');
        CRUD::column('status_id_before_archive');
        CRUD::column('buyer_id');
        CRUD::column('agent_id');
        CRUD::column('selling_price_init');
        CRUD::column('selling_price_final');
        CRUD::column('selling_price_final_currency_id');
        CRUD::column('selling_price_init_currency_id');
        CRUD::column('created_on');
        CRUD::column('new_construction');
        CRUD::column('floor');
        CRUD::column('comment_arm');
        CRUD::column('comment_eng');
        CRUD::column('comment_ru');
        CRUD::column('last_modified_by');
        CRUD::column('additional_info_arm');
        CRUD::column('additional_info_eng');
        CRUD::column('additional_info_ru');
        CRUD::column('name_arm');
        CRUD::column('name_eng');
        CRUD::column('name_ru');
        CRUD::column('name_ar');
        CRUD::column('selling_comment_arm');
        CRUD::column('selling_comment_eng');
        CRUD::column('selling_comment_ru');
        CRUD::column('last_modified_on');
        CRUD::column('created_by');
        CRUD::column('garage');
        CRUD::column('cellar');
        CRUD::column('land');
        CRUD::column('niche');
        CRUD::column('pantry');
        CRUD::column('jacuzzi');
        CRUD::column('possible_extension');
        CRUD::column('separate_room');
        CRUD::column('exchange');
        CRUD::column('has_intercom');
        CRUD::column('uninhabited');
        CRUD::column('balcony');
        CRUD::column('tv');
        CRUD::column('computer');
        CRUD::column('refrigirator');
        CRUD::column('hot_water');
        CRUD::column('washer');
        CRUD::column('dish_washer');
        CRUD::column('property_agent_id');
        CRUD::column('code');
        CRUD::column('appointment_date_start');
        CRUD::column('appointment_date_end');
        CRUD::column('appointment_comment_arm');
        CRUD::column('appointment_comment_eng');
        CRUD::column('appointment_comment_ru');
        CRUD::column('ceiling_height_type_id');
        CRUD::column('building_structure_type_id');
        CRUD::column('building_floor_type_id');
        CRUD::column('vitrage_type_id');
        CRUD::column('separate_entrance_type_id');
        CRUD::column('intercom');
        CRUD::column('filled_by');
        CRUD::column('filled_on');
        CRUD::column('verified_by');
        CRUD::column('verified_on');
        CRUD::column('entrance_type_id');
        CRUD::column('has_neighbour');
        CRUD::column('is_advertised');
        CRUD::column('info_source_id');
        CRUD::column('price_usd');
        CRUD::column('archive_till_date');
        CRUD::column('archive_comment_arm');
        CRUD::column('archive_comment_eng');
        CRUD::column('archive_comment_ru');
        CRUD::column('public_text_arm');
        CRUD::column('public_text_eng');
        CRUD::column('public_text_rus');
        CRUD::column('estate_latitude');
        CRUD::column('estate_longitude');
        CRUD::column('is_urgent');
        CRUD::column('urgent_start_date');
        CRUD::column('is_exchangeable');
        CRUD::column('is_first_floor');
        CRUD::column('is_last_floor');
        CRUD::column('main_image_file_name');
        CRUD::column('main_image_file_path');
        CRUD::column('main_image_file_path_thumb');
        CRUD::column('is_mansard_floor');
        CRUD::column('is_duplex');
        CRUD::column('is_basement');
        CRUD::column('visits_count');
        CRUD::column('is_from_public');
        CRUD::column('is_hot_offer');
        CRUD::column('hot_offer_start_date');
        CRUD::column('is_on_main_page');
        CRUD::column('on_main_page_start_date');
        CRUD::column('is_separate_building');
        CRUD::column('is_estate_commercial_land');
        CRUD::column('courtyard_improvement_id');
        CRUD::column('distance_public_objects_id');
        CRUD::column('building_window_count_id');
        CRUD::column('temporary_agent_id');
        CRUD::column('temporary_agent_date');
        CRUD::column('temporary_visits_count');
        CRUD::column('apartment_construction');
        CRUD::column('meta_description_eng');
        CRUD::column('meta_description_arm');
        CRUD::column('meta_description_ru');
        CRUD::column('meta_title_eng');
        CRUD::column('meta_title_arm');
        CRUD::column('meta_title_ru');
        CRUD::column('price_down_on');
        CRUD::column('is_public_text_generation');

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
        CRUD::setValidation(EstateRequest::class);

        CRUD::field('location_country_id');
        CRUD::field('location_province_id');
        CRUD::field('location_city_id');
        CRUD::field('location_community_id');
        CRUD::field('location_street_id');
        CRUD::field('estate_type_id');
        CRUD::field('area_total');
        CRUD::field('old_price');
        CRUD::field('price');
        CRUD::field('currency_id');
        CRUD::field('seller_id');
        CRUD::field('area_residential');
        CRUD::field('registered_right_id');
        CRUD::field('repairing_type_id');
        CRUD::field('room_count');
        CRUD::field('building_type_id');
        CRUD::field('building_project_type_id');
        CRUD::field('conditioner');
        CRUD::field('room_count_modified');
        CRUD::field('exterior_design_type_id');
        CRUD::field('elevator_type_id');
        CRUD::field('year_id');
        CRUD::field('heating_system_type_id');
        CRUD::field('parking_type_id');
        CRUD::field('service_fee_type_id');
        CRUD::field('service_amount');
        CRUD::field('service_amount_currency_id');
        CRUD::field('furniture');
        CRUD::field('kitchen_furniture');
        CRUD::field('gas_heater');
        CRUD::field('persistent_water');
        CRUD::field('natural_gas');
        CRUD::field('gas_possibility');
        CRUD::field('internet');
        CRUD::field('satellite_tv');
        CRUD::field('cable_tv');
        CRUD::field('sunny');
        CRUD::field('exclusive_design');
        CRUD::field('expanding_possible');
        CRUD::field('open_balcony');
        CRUD::field('oriel');
        CRUD::field('new_wiring');
        CRUD::field('new_water_tubes');
        CRUD::field('heating_ground');
        CRUD::field('plastic_windows');
        CRUD::field('parquet');
        CRUD::field('laminat');
        CRUD::field('equipped');
        CRUD::field('roof_type_id');
        CRUD::field('floor_count_id');
        CRUD::field('house_building_type_id');
        CRUD::field('roof_repaired');
        CRUD::field('roof_material_type_id');
        CRUD::field('fence_type_id');
        CRUD::field('communication_type_id');
        CRUD::field('front_with_street_id');
        CRUD::field('road_way_type_id');
        CRUD::field('commercial_purpose_type_id');
        CRUD::field('communication_id');
        CRUD::field('land_structure_type_id');
        CRUD::field('land_type_id');
        CRUD::field('land_use_type_id');
        CRUD::field('front_length');
        CRUD::field('version');
        CRUD::field('address_building');
        CRUD::field('address_apartment');
        CRUD::field('contract_type_id');
        CRUD::field('entrance_door_type_id');
        CRUD::field('entrance_door_position_id');
        CRUD::field('windows_view_id');
        CRUD::field('building_floor_count');
        CRUD::field('house_floors_type_id');
        CRUD::field('roof_drainage');
        CRUD::field('new_doors');
        CRUD::field('new_windows');
        CRUD::field('new_bathroom');
        CRUD::field('new_floor');
        CRUD::field('new_roof');
        CRUD::field('can_be_used_as_commercial');
        CRUD::field('is_published');
        CRUD::field('estate_status_id');
        CRUD::field('status_changed_on');
        CRUD::field('status_id_before_archive');
        CRUD::field('buyer_id');
        CRUD::field('agent_id');
        CRUD::field('selling_price_init');
        CRUD::field('selling_price_final');
        CRUD::field('selling_price_final_currency_id');
        CRUD::field('selling_price_init_currency_id');
        CRUD::field('created_on');
        CRUD::field('new_construction');
        CRUD::field('floor');
        CRUD::field('comment_arm');
        CRUD::field('comment_eng');
        CRUD::field('comment_ru');
        CRUD::field('last_modified_by');
        CRUD::field('additional_info_arm');
        CRUD::field('additional_info_eng');
        CRUD::field('additional_info_ru');
        CRUD::field('name_arm');
        CRUD::field('name_eng');
        CRUD::field('name_ru');
        CRUD::field('name_ar');
        CRUD::field('selling_comment_arm');
        CRUD::field('selling_comment_eng');
        CRUD::field('selling_comment_ru');
        CRUD::field('last_modified_on');
        CRUD::field('created_by');
        CRUD::field('garage');
        CRUD::field('cellar');
        CRUD::field('land');
        CRUD::field('niche');
        CRUD::field('pantry');
        CRUD::field('jacuzzi');
        CRUD::field('possible_extension');
        CRUD::field('separate_room');
        CRUD::field('exchange');
        CRUD::field('has_intercom');
        CRUD::field('uninhabited');
        CRUD::field('balcony');
        CRUD::field('tv');
        CRUD::field('computer');
        CRUD::field('refrigirator');
        CRUD::field('hot_water');
        CRUD::field('washer');
        CRUD::field('dish_washer');
        CRUD::field('property_agent_id');
        CRUD::field('code');
        CRUD::field('appointment_date_start');
        CRUD::field('appointment_date_end');
        CRUD::field('appointment_comment_arm');
        CRUD::field('appointment_comment_eng');
        CRUD::field('appointment_comment_ru');
        CRUD::field('ceiling_height_type_id');
        CRUD::field('building_structure_type_id');
        CRUD::field('building_floor_type_id');
        CRUD::field('vitrage_type_id');
        CRUD::field('separate_entrance_type_id');
        CRUD::field('intercom');
        CRUD::field('filled_by');
        CRUD::field('filled_on');
        CRUD::field('verified_by');
        CRUD::field('verified_on');
        CRUD::field('entrance_type_id');
        CRUD::field('has_neighbour');
        CRUD::field('is_advertised');
        CRUD::field('info_source_id');
        CRUD::field('price_usd');
        CRUD::field('archive_till_date');
        CRUD::field('archive_comment_arm');
        CRUD::field('archive_comment_eng');
        CRUD::field('archive_comment_ru');
        CRUD::field('public_text_arm');
        CRUD::field('public_text_eng');
        CRUD::field('public_text_rus');
        CRUD::field('estate_latitude');
        CRUD::field('estate_longitude');
        CRUD::field('is_urgent');
        CRUD::field('urgent_start_date');
        CRUD::field('is_exchangeable');
        CRUD::field('is_first_floor');
        CRUD::field('is_last_floor');
        CRUD::field('main_image_file_name');
        CRUD::field('main_image_file_path');
        CRUD::field('main_image_file_path_thumb');
        CRUD::field('is_mansard_floor');
        CRUD::field('is_duplex');
        CRUD::field('is_basement');
        CRUD::field('visits_count');
        CRUD::field('is_from_public');
        CRUD::field('is_hot_offer');
        CRUD::field('hot_offer_start_date');
        CRUD::field('is_on_main_page');
        CRUD::field('on_main_page_start_date');
        CRUD::field('is_separate_building');
        CRUD::field('is_estate_commercial_land');
        CRUD::field('courtyard_improvement_id');
        CRUD::field('distance_public_objects_id');
        CRUD::field('building_window_count_id');
        CRUD::field('temporary_agent_id');
        CRUD::field('temporary_agent_date');
        CRUD::field('temporary_visits_count');
        CRUD::field('apartment_construction');
        CRUD::field('meta_description_eng');
        CRUD::field('meta_description_arm');
        CRUD::field('meta_description_ru');
        CRUD::field('meta_title_eng');
        CRUD::field('meta_title_arm');
        CRUD::field('meta_title_ru');
        CRUD::field('price_down_on');
        CRUD::field('is_public_text_generation');

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
