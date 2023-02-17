<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EvaluationOptionResource;
use App\Models\CEvaluationBalconyAvailable;
use App\Models\CEvaluationBuildingArea;
use App\Models\CEvaluationBuildingFloor;
use App\Models\CEvaluationBuildingProject;
use App\Models\CEvaluationBuildingWindowCount;
use App\Models\CEvaluationBuildingWindowPosition;
use App\Models\CEvaluationCourtyardImprovement;
use App\Models\CEvaluationDistancePublicObject;
use App\Models\CEvaluationExternalDesign;
use App\Models\CEvaluationInteriorDesign;
use App\Models\CEvaluationLastFloorAvailability;
use App\Models\CEvaluationLayoutRoom;
use App\Models\CEvaluationLocation;
use App\Models\CEvaluationLocationType;
use App\Models\CEvaluationSunOrientation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use stdClass;

class EvaluationController extends Controller
{
    public function getEvaluationOptions(Request $request)
    {

        $optionsData = new StdClass;
        $data = new StdClass;
        $evaluationOptionsData = new StdClass;

        $evaluationOptionsData->locationCommunity = EvaluationOptionResource::collection((CEvaluationLocation::all()));
        $evaluationOptionsData->buildingProject = EvaluationOptionResource::collection((
            CEvaluationBuildingProject::where('evaluation_building_type_id', '=', 1)->get())
        );
        $evaluationOptionsData->courtyardImprovement = EvaluationOptionResource::collection((
            CEvaluationCourtyardImprovement::where('evaluation_location_type_id', '=', 1)->get()));
        $evaluationOptionsData->distanceObject = EvaluationOptionResource::collection((
            CEvaluationDistancePublicObject::where('evaluation_location_type_id', '=', 1)->get()
        ));
        $evaluationOptionsData->buildingFloor = EvaluationOptionResource::collection((
            CEvaluationBuildingFloor::where('evaluation_building_type_id', '=', 1)->get()));
        $evaluationOptionsData->lastFloorAvailability = EvaluationOptionResource::collection((CEvaluationLastFloorAvailability::all()));
        $evaluationOptionsData->buildingArea = EvaluationOptionResource::collection((CEvaluationBuildingArea::all()));
        $evaluationOptionsData->windowCount = EvaluationOptionResource::collection((CEvaluationBuildingWindowCount::all()));
        $evaluationOptionsData->windowPosition = EvaluationOptionResource::collection((CEvaluationBuildingWindowPosition::all()));
        $evaluationOptionsData->layoutRoom = EvaluationOptionResource::collection((
            CEvaluationLayoutRoom::where('evaluation_location_type_id', '=', 1)->get()));
        $evaluationOptionsData->balconyAvailable = EvaluationOptionResource::collection((CEvaluationBalconyAvailable::all()));
        $evaluationOptionsData->internalDesign = EvaluationOptionResource::collection((CEvaluationInteriorDesign::all()));
        $evaluationOptionsData->externalDesign = EvaluationOptionResource::collection((CEvaluationExternalDesign::all()));
        $evaluationOptionsData->sunOrientation = EvaluationOptionResource::collection((CEvaluationSunOrientation::all()));
        $data->evaluationOptionsData = $evaluationOptionsData;

        $optionsData->data = $data;
        return $optionsData;


    }

    public function evaluate(Request $request)
    {


        $value = 0;
        $locationCoefficient = $request->input('locationCommunity');
        $buildingProjectCoefficient = $request->input('buildingProject');
        $courtyardImprovementCoefficient = $request->input('courtyardImprovement');
        $distanceObjectCoefficient = $request->input('distanceObject');
        $buildingFloorCoefficient = $request->input('buildingFloor');
        $lastFloorAvailabilityCoefficient = $request->input('lastFloorAvailability');
        $windowCountCoefficient = $request->input('windowCount');
        $windowPositionCoefficient = $request->input('windowPosition');
        $layoutRoomCoefficient = $request->input('layoutRoom');
        $balconyAvailableCoefficient = $request->input('balconyAvailable');
        $internalDesignCoefficient = $request->input('internalDesign');
        $externalDesignCoefficient = $request->input('externalDesign');
        $sunOrientationCoefficient = $request->input('area');
        $area = $request->input('area');
        $buildingAreaCoefficient = $this->getBuildingAreaCoefficient($area);
        $buildingAreaNumberCoefficient = 0.99;

        if ($locationCoefficient && $locationCoefficient != 0) {
            $value = $buildingProjectCoefficient != null ? $locationCoefficient * $buildingProjectCoefficient : $locationCoefficient;
            $value = $courtyardImprovementCoefficient != null ? $value * $courtyardImprovementCoefficient : $value;
            $value = $distanceObjectCoefficient != null ? $value * $distanceObjectCoefficient : $value;
            $value = $buildingFloorCoefficient != null ? $value * $buildingFloorCoefficient : $value;
            $value = $lastFloorAvailabilityCoefficient != null ? $value * $lastFloorAvailabilityCoefficient : $value;
            $value = $buildingAreaCoefficient != null ? $value * $buildingAreaCoefficient : $value;
            $value = $windowCountCoefficient != null ? $value * $windowCountCoefficient : $value;
            $value = $windowPositionCoefficient != null ? $value * $windowPositionCoefficient : $value;
            $value = $layoutRoomCoefficient != null ? $value * $layoutRoomCoefficient : $value;
            $value = $balconyAvailableCoefficient != null ? $value * $balconyAvailableCoefficient : $value;
            $value = $internalDesignCoefficient != null ? $value * $internalDesignCoefficient : $value;
            $value = $externalDesignCoefficient != null ? $value * $externalDesignCoefficient : $value;
            $value = $sunOrientationCoefficient != null ? $value * $sunOrientationCoefficient : $value;
            $value = $buildingAreaNumberCoefficient != null ? $value * $buildingAreaNumberCoefficient : $value;
            $value = ceil($value * 1.02);
        }

        return $value;
    }

    private function getBuildingAreaCoefficient($area)
    {

        abort_if($area < 15, '404');

        $areaSet = CEvaluationBuildingArea::all()->pluck('name_eng', 'id');

        $areaMinMax = [];
        foreach ($areaSet as $key => $areaName) {
            $areas = explode('-', $areaName);
            $areaMinMax[$key] = $areas;
        }

        $areaId = null;

        foreach ($areaMinMax as $key => $areaRange) {
            if ($key < 8) {
                if ($area >= $areaRange[0] && $area <= $areaRange[1] && $area < 120) {
                    $areaId = $key;
                }
            } else if ($area > 120) {
                $areaId = 8;
            }
        }

        $coefficient = CEvaluationBuildingArea::findOrfail($areaId)->coefficient;

        return $coefficient;
    }


}
