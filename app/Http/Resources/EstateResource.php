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
            'floor' => $this->floor,
            'building_floor_count' => $this->building_floor_count,
            'name_arm' => $this->name_arm,
//            'image' => $this->main_image_file_path ? 'https://proinfo.am/uploadsWithWaterMark/'.$this->main_image_file_path : 'https://i0.wp.com/lanecdr.org/wp-content/uploads/2019/08/placeholder.png',
            'image' => 'https://picsum.photos/id/1019/1000/600/',
        ];
    }
}
