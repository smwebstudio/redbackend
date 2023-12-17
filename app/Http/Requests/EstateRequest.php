<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EstateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'contract_type' => 'required',
             'estate_status' => 'required',
             'location_province' => 'required',
             'location_city' => 'required_unless:location_province,1',
             'location_community' => 'required_if:location_province,1',
             'location_street' => 'required',
             'address_building' => 'required_if:estate_status,2,3,4,5,6,7,8',
            'address_apartment' => [
                function ($attribute, $value, $fail) {
                    $estate_status = (int)$this->input('estate_status');
                    $estate_type = (int)$this->input('estate_type_id');

                    if (($estate_status === 4) && ($estate_type === 1) && empty($value)) {
                        $fail($attribute . ' is required.');
                    }
                },
            ],
             'floor' => 'required_if:estate_status,4',
             'building_floor_count' => 'required_if:estate_status,4',
             'ceiling_height_type' => 'required_if:estate_status,4',
             'room_count' => 'required_if:estate_status,4',
             'area_total' => 'required_if:estate_status,4',
             'price_amd' => 'required_if:estate_status,4',
            'archive_till_date' => 'required_if:estate_status,8',
            'archive_comment_arm' => 'required_if:estate_status,8',
            'building_structure_type' => 'required_if:estate_status,4',
            'building_type' => 'required_if:estate_status,4',
            'building_project_type' => 'required_if:estate_status,4',
            'building_floor_type' => 'required_if:estate_status,4',
            'exterior_design_type' => 'required_if:estate_status,4',
            'courtyard_improvement' => 'required_if:estate_status,4',
            'distance_public_objects' => 'required_if:estate_status,4',
            'elevator_type' => 'required_if:estate_status,4',
            'year' => 'required_if:estate_status,4',
            'parking_type' => 'required_if:estate_status,4',
            'entrance_type' => 'required_if:estate_status,4',
            'entrance_door_position' => 'required_if:estate_status,4',
            'entrance_door_type' => 'required_if:estate_status,4',
            'windows_view' => 'required_if:estate_status,4',
            'building_window_count' => 'required_if:estate_status,4',
            'repairing_type' => 'required_if:estate_status,4',
            'heating_system_type' => 'required_if:estate_status,4',

        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required_if' => 'Պարտադիր է լրացման համար։',
            'required_unless' => 'Պարտադիր է լրացման համար։',
            'archive_till_date.required_if' => 'Արխիվացված կարգավիճակում անհրաժեշտ է լրացնել։',
            'archive_comment_arm.required_if' => 'Արխիվացված կարգավիճակում անհրաժեշտ է լրացնել։',
        ];
    }
}
