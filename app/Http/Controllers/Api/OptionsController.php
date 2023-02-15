<?php

namespace App\Http\Controllers\Api;
use App\Http\Resources\OptionTypeResource;
use App\Http\Resources\LocationResource;
use App\Models\CBuildingProjectType;
use App\Models\CBuildingType;
use App\Models\CBuildingWindowCount;
use App\Models\CCourtyardImprovement;
use App\Models\CDistancePublicObject;
use App\Models\CEntranceDoorType;
use App\Models\CExteriorDesignType;
use App\Models\CHeatingSystemType;
use App\Models\CLocationProvince;
use App\Models\CParkingType;
use App\Models\CRepairingType;
use App\Models\CServiceFeeType;
use App\Models\CWindowsView;
use App\Models\CYear;
use App\Models\EstateOptionType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use stdClass;

class OptionsController extends Controller
{
    public function getOptions(Request $request)
    {

        $optionsData = new StdClass;
        $data = new StdClass;
        $buildingOptionsData = new StdClass;

        $buildingOptionsData->buildingType =  OptionTypeResource::collection((CBuildingType::all()));
        $buildingOptionsData->buildingProjectType =  OptionTypeResource::collection((CBuildingProjectType::all()));
        $buildingOptionsData->repairingType =  OptionTypeResource::collection((CRepairingType::all()));
        $buildingOptionsData->exteriorDesignType =  OptionTypeResource::collection((CExteriorDesignType::all()));
        $buildingOptionsData->courtyardImprovement =  OptionTypeResource::collection((CCourtyardImprovement::all()));
        $buildingOptionsData->distancePublicObjects =  OptionTypeResource::collection((CDistancePublicObject::all()));
        $buildingOptionsData->year =  OptionTypeResource::collection((CYear::all()));
        $buildingOptionsData->heatingSystemType =  OptionTypeResource::collection((CHeatingSystemType::all()));
        $buildingOptionsData->entranceDoorType =  OptionTypeResource::collection((CEntranceDoorType::all()));
        $buildingOptionsData->parkingType =  OptionTypeResource::collection((CParkingType::all()));
        $buildingOptionsData->serviceFeeType =  OptionTypeResource::collection((CServiceFeeType::all()));
        $buildingOptionsData->windowsView =  OptionTypeResource::collection((CWindowsView::all()));
        $buildingOptionsData->buildingWindowCount =  OptionTypeResource::collection((CBuildingWindowCount::all()));

        $data->locationData =   LocationResource::collection((CLocationProvince::all()));
        $data->estateOptionsData =  OptionTypeResource::collection((EstateOptionType::all()));
        $data->buildingOptionsData = $buildingOptionsData;

        $optionsData->data = $data;
        return $optionsData;


    }


}
