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
             'contract_type' => 'required',
             'estate_status' => 'required',
             'location_province' => 'required',
//             'location_community' => 'required_if:location_province,Yerevan',
//             'location_city' => 'required',
             'location_street' => 'required',
             'address_building' => 'required',
             'address_apartment' => 'required',
//             'floor' => 'required',
            'archive_till_date' => 'required_if:estate_status,8',
            'archive_comment_arm' => 'required_if:estate_status,8',
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
