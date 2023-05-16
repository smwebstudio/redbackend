<?php

namespace App\Http\Resources;

use App\Models\Contact;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class EstateDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $images = $this->estateDocuments;
        $imagesPaths = [];

        foreach ($images as $image) {
            $imagesPaths[] = $image->public_path;
        }

        return [
            'id' => $this->id,
            'price' => $this->full_price,
            'code' => $this->code,
            'native_coords' => $this->estate_latitude ? [$this->estate_latitude, $this->estate_longitude] : [],
            'room_count' => $this->room_count,
            'old_price' => $this->old_price,
            'full_address' => $this->full_address,
            'public_text_arm' => $this->public_text_arm,
            'name' => $this->name,
            'area_total' => $this->area_total,
            'floor' => $this->floor,
            'building_floor_count' => $this->building_floor_count,

            'building_attributes' => [
                'building_floor_type' => ['value' => $this->building_floor_type?->name_arm, 'label' => trans('estate.building_floor_type'),],
                'building_structure_type' => ['value' => $this->building_structure_type?->name_arm, 'label' => trans('estate.building_structure_type'),],
                'building_type' => ['value' => $this->building_type?->name_arm, 'label' => trans('estate.building_type'),],
                'building_project_type' => ['value' => $this->building_project_type?->name_arm, 'label' => trans('estate.building_project_type'),],
                'ceiling_height_type' => ['value' => $this->ceiling_height_type?->name_arm, 'label' => trans('estate.ceiling_height_type'),],
                'commercial_purpose_type' => ['value' => $this->commercial_purpose_type?->name_arm, 'label' => trans('estate.commercial_purpose_type'),],
                'communication_type' => ['value' => $this->communication_type?->name_arm, 'label' => trans('estate.communication_type'),],
                'elevator_type' => ['value' => $this->elevator_type?->name_arm, 'label' => trans('estate.elevator_type'),],
                'entrance_door_position' => ['value' => $this->entrance_door_position?->name_arm, 'label' => trans('estate.entrance_door_position'),],
                'entrance_door_type' => ['value' => $this->entrance_door_type?->name_arm, 'label' => trans('estate.entrance_door_type'),],
                'entrance_type' => ['value' => $this->entrance_type?->name_arm, 'label' => trans('estate.entrance_type'),],
                'exterior_design_type' => ['value' => $this->exterior_design_type?->name_arm, 'label' => trans('estate.exterior_design_type'),],
                'courtyard_improvement' => ['value' => $this->courtyard_improvement?->name_arm, 'label' => trans('estate.courtyard_improvement'),],
                'distance_public_objects' => ['value' => $this->distance_public_objects?->name_arm, 'label' => trans('estate.distance_public_objects'),],
                'fence_type' => ['value' => $this->fence_type?->name_arm, 'label' => trans('estate.fence_type'),],
                'front_with_street' => ['value' => $this->front_with_street?->name_arm, 'label' => trans('estate.front_with_street'),],
                'heating_system_type' => ['value' => $this->heating_system_type?->name_arm, 'label' => trans('estate.heating_system_type'),],
                'house_building_type' => ['value' => $this->house_building_type?->name_arm, 'label' => trans('estate.house_building_type'),],
                'house_floors_type' => ['value' => $this->house_floors_type?->name_arm, 'label' => trans('estate.house_floors_type'),],
                'land_structure_type' => ['value' => $this->land_structure_type?->name_arm, 'label' => trans('estate.land_structure_type'),],
                'land_type' => ['value' => $this->land_type?->name_arm, 'label' => trans('estate.land_type'),],
                'land_use_type' => ['value' => $this->land_use_type?->name_arm, 'label' => trans('estate.land_use_type'),],
                'parking_type' => ['value' => $this->parking_type?->name_arm, 'label' => trans('estate.parking_type'),],
                'registered_right' => ['value' => $this->registered_right?->name_arm, 'label' => trans('estate.registered_right'),],
                'repairing_type' => ['value' => $this->repairing_type?->name_arm, 'label' => trans('estate.repairing_type'),],
                'road_way_type' => ['value' => $this->road_way_type?->name_arm, 'label' => trans('estate.road_way_type'),],
                'roof_material_type' => ['value' => $this->roof_material_type?->name_arm, 'label' => trans('estate.roof_material_type'),],
                'roof_type' => ['value' => $this->roof_type?->name_arm, 'label' => trans('estate.roof_type'),],
                'separate_entrance_type' => ['value' => $this->separate_entrance_type?->name_arm, 'label' => trans('estate.separate_entrance_type'),],
                'service_fee_type' => ['value' => $this->service_fee_type?->name_arm, 'label' => trans('estate.service_fee_type'),],
                'vitrage_type' => ['value' => $this->vitrage_type?->name_arm, 'label' => trans('estate.vitrage_type'),],
                'windows_view' => ['value' => $this->windows_view?->name_arm, 'label' => trans('estate.windows_view'),],
                'building_window_count' => ['value' => $this->building_window_count?->name_arm, 'label' => trans('estate.building_window_count'),],

            ],

            'estate_facilities' => [
                'hasFurniture' => ['value' => $this->furniture, 'label' => trans('estate.furniture')],
                'hasKitchenFurniture' => ['value' => $this->kitchen_furniture, 'label' => trans('estate.kitchen_furniture')],
                'hasGasHeater' => ['value' => $this->gas_heater, 'label' => trans('estate.gas_heater')],
                'hasPersistentWater' => ['value' => $this->persistent_water, 'label' => trans('estate.persistent_water')],
                'hasNaturalGas' => ['value' => $this->natural_gas, 'label' => trans('estate.natural_gas')],
                'hasGasPossibility' => ['value' => $this->gas_possibility, 'label' => trans('estate.gas_possibility')],
                'hasInternet' => ['value' => $this->internet, 'label' => trans('estate.internet')],
                'hasSatelliteTv' => ['value' => $this->satellite_tv, 'label' => trans('estate.satellite_tv')],
                'hasCableTv' => ['value' => $this->cable_tv, 'label' => trans('estate.cable_tv')],
                'sunny' => ['value' => $this->sunny, 'label' => trans('estate.sunny')],
                'hasExclusiveDesign' => ['value' => $this->exclusive_design, 'label' => trans('estate.exclusive_design')],
                'expandingPossible' => ['value' => $this->expanding_possible, 'label' => trans('estate.expanding_possible')],
                'hasOpenBalcony' => ['value' => $this->open_balcony, 'label' => trans('estate.open_balcony')],
                'hasOriel' => ['value' => $this->oriel, 'label' => trans('estate.oriel')],
                'hasNewWiring' => ['value' => $this->new_wiring, 'label' => trans('estate.new_wiring')],
                'hasNewWaterTubes' => ['value' => $this->new_water_tubes, 'label' => trans('estate.new_water_tubes')],
                'hasHeatingGround' => ['value' => $this->heating_ground, 'label' => trans('estate.heating_ground')],
                'hasParquet' => ['value' => $this->parquet, 'label' => trans('estate.parquet')],
                'newDoors' => ['value' => $this->new_doors, 'label' => trans('estate.new_doors')],
                'newWindows' => ['value' => $this->new_windows, 'label' => trans('estate.new_windows')],
                'newBathroom' => ['value' => $this->new_bathroom, 'label' => trans('estate.new_bathroom')],
                'newFloor' => ['value' => $this->new_floor, 'label' => trans('estate.new_floor')],
                'newRoof' => ['value' => $this->new_roof, 'label' => trans('estate.new_roof')],
                'canBeUsedAsCommercial' => ['value' => $this->can_be_used_as_commercial, 'label' => trans('estate.can_be_used_as_commercial')],
                'isNewConstruction' => ['value' => $this->new_construction, 'label' => trans('estate.new_construction')],
                'isApartmentConstruction' => ['value' => $this->apartment_construction, 'label' => trans('estate.apartment_construction')],
            ],


            'year' => $this->year?->name_arm,
            'name_arm' => $this->name_arm,
            'images' => $imagesPaths,
            'image' => $this->main_image_file_path_thumb ? $this->public_path : 'https://i0.wp.com/lanecdr.org/wp-content/uploads/2019/08/placeholder.png',

            'contact' => $this->agent ? new ContactEstatesResource((Contact::findOrfail($this->agent?->contact_id))) : '',
        ];
    }
}
