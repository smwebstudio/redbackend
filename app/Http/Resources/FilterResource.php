<?php

namespace App\Http\Resources;

use App\Models\CEstateType;
use App\Models\CLocationProvince;
use App\Models\CPricePerQwdMeterArm;
use App\Models\CRoomsQuantity;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        return [
            'estate_types' => OptionTypeResource::collection(CEstateType::all()),
            'locations' => LocationResource::collection((CLocationProvince::all())),
            'prices' => OptionTypeResource::collection(CPricePerQwdMeterArm::all()),
            'rooms' => OptionTypeResource::collection(CRoomsQuantity::all()),
        ];
    }
}
