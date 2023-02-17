<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $languageApi = $request->server('HTTP_ACCEPT_LANGUAGE');
        $language = config('constants.'.$languageApi);
        $name = 'name_'.$language;

        return [
            'id' => $this->id,
            'name' => $this->$name,
            'cities' => CityResource::collection($this->cities),
            ];
    }
}
