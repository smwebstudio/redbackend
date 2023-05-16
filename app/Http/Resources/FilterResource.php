<?php

namespace App\Http\Resources;

use App\Models\CAreaForBuilding;
use App\Models\CBuildingProjectType;
use App\Models\CBuildingType;
use App\Models\CContactType;
use App\Models\CContractType;
use App\Models\CDailyPriceInAmd;
use App\Models\CDailyPriceInRur;
use App\Models\CDailyPriceInUsd;
use App\Models\CEstateType;
use App\Models\CLocationCommunity;
use App\Models\CLocationProvince;
use App\Models\CPricePerQwdMeterArm;
use App\Models\CPricePerQwdMeterRur;
use App\Models\CPricePerQwdMeterUsd;
use App\Models\CRentPriceInAmd;
use App\Models\CRentPriceInRur;
use App\Models\CRentPriceInUsd;
use App\Models\CRepairingType;
use App\Models\CRoomsQuantity;
use App\Models\CSellPriceInAmd;
use App\Models\CSellPriceInRur;
use App\Models\CSellPriceInUsd;
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
            'contract_types' => OptionTypeResource::collection(CContractType::all()),
            'locations' => LocationResource::collection((CLocationProvince::all())),
            'prices' => [
                'sale' => [
                    'USD' => OptionTypeResource::collection(CSellPriceInUsd::all()),
                    'AMD' => OptionTypeResource::collection(CSellPriceInAmd::all()),
                    'RUR' => OptionTypeResource::collection(CSellPriceInRur::all()),
                ],
                'rent' => [
                    'USD' => OptionTypeResource::collection(CRentPriceInUsd::all()),
                    'AMD' => OptionTypeResource::collection(CRentPriceInAmd::all()),
                    'RUR' => OptionTypeResource::collection(CRentPriceInRur::all()),
                ],
                'daily' => [
                    'USD' => OptionTypeResource::collection(CDailyPriceInUsd::all()),
                    'AMD' => OptionTypeResource::collection(CDailyPriceInAmd::all()),
                    'RUR' => OptionTypeResource::collection(CDailyPriceInRur::all()),
                ],
            ],
            'location_community' => OptionTypeResource::collection(CLocationCommunity::all()),
            'rooms' => OptionTypeResource::collection(CRoomsQuantity::all()),
            'area_total' => OptionTypeResource::collection(CAreaForBuilding::all()),
            'prece_per_qwd' => OptionTypeResource::collection(CPricePerQwdMeterArm::all()),
            'building_project_type' => OptionTypeResource::collection(CBuildingProjectType::all()),
            'building_type' => OptionTypeResource::collection(CBuildingType::all()),
            'repairing_type' => OptionTypeResource::collection(CRepairingType::all()),
        ];
    }
}
