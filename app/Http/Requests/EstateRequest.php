<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
             'name' => 'required|min:5|max:255',
             'contract_type' => 'required',
             'location_province' => 'required_if:estate_status,Incomplete',
             'location_community' => 'required_if:location_province,Yerevan',
             'location_city' => 'required',
             'location_street' => 'required',
             'address_building' => 'required_if:location_province,1',
             'floor' => 'required',
             'building_floor_count' => 'required_if:floor,5',
             'is_advertised' => 'required_if:estate_status,Incomplete',
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
            //
        ];
    }
}
