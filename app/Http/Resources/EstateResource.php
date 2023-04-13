<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EstateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'price' => $this->full_price,
            'code' => $this->code,
            'old_price' => $this->old_price,
            'full_address' => $this->full_address,
            'area_total' => $this->area_total,
            'room_count' => $this->room_count,
            'floor' => $this->floor,
            'building_floor_count' => $this->building_floor_count,
            'building_structure_type' => $this->building_structure_type?->name_arm,
            'building_project_type' => $this->building_project_type?->name_arm,
            'entrance_door_position' => $this->entrance_door_position?->name_arm,
            'year' => $this->year?->name_arm,
            'name_arm' => $this->name_arm,
            'images' => $this->estateDocuments,
            'image' => $this->main_image_file_path_thumb ? $this->path : 'https://i0.wp.com/lanecdr.org/wp-content/uploads/2019/08/placeholder.png',
        ];
    }
}
