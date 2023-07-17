<?php

namespace App\Observers;

use App\Events\EstateDocumentUploaded;
use App\Models\Estate;
use App\Services\FileService;

class EstateObserver
{


    /**
     * Handle the Estate "creating" event.
     */
    public function creating(Estate $estate): void
    {
        $estate->public_text_arm = $this->setPublicTextGeneratorByApartment($estate);
    }


    /**
     * Handle the Estate "created" event.
     */
    public function created(Estate $estate): void
    {

        $code = '';
        if ($estate->contract_type_id != null) {
            $code .= $estate->contract_type->id === 1 ? 0 : 1;
        } else {
            $code .= "-";
        }
        if ($estate->estate_type_id != null) {
            $code .= $estate->estate_type_id;
        } else {
            $code .= "-";
        }

        if ($estate->room_count != null && $estate->room_count != 0) {
            $code .= $estate->room_count;
        } else {
            $code .= "-";
        }

        $estate->code = $code . '-' . $estate->id;

        if (!empty(request()->input('location'))) {
            $location = json_decode(request()->input('location'));
            $estate->estate_latitude = $location->lat;
            $estate->estate_longitude = $location->lng;
        }

        $estate->save();

    }

    /**
     * Handle the Estate "updating" event.
     */
    public function updating(Estate $estate): void
    {
        if (!empty(request()->input('location'))) {
            $location = json_decode(request()->input('location'));
            $estate->estate_latitude = $location->lat;
            $estate->estate_longitude = $location->lng;
        }

        $estate->public_text_arm = $this->setPublicTextGeneratorByApartment($estate);
    }

    /**
     * Handle the Estate "updated" event.
     */
    public function updated(Estate $estate): void
    {

    }

    /**
     * Handle the Estate "deleted" event.
     */
    public function deleted(Estate $estate): void
    {
        //
    }

    /**
     * Handle the Estate "restored" event.
     */
    public function restored(Estate $estate): void
    {
        //
    }

    /**
     * Handle the Estate "force deleted" event.
     */
    public function forceDeleted(Estate $estate): void
    {
        //
    }

    public function saved(Estate $estate)
    {
        $code = '';
        if ($estate->contract_type_id != null) {
            $code .= $estate->contract_type->id === 1 ? 0 : 1;
        } else {
            $code .= "-";
        }
        if ($estate->estate_type_id != null) {
            $code .= $estate->estate_type_id;
        } else {
            $code .= "-";
        }

        if ($estate->room_count != null && $estate->room_count != 0) {
            $code .= $estate->room_count;
        } else {
            $code .= "-";
        }


        $estate->code = $code . '-' . $estate->id;

        if (!empty(request()->input('location'))) {
            $location = json_decode(request()->input('location'));
            $estate->estate_latitude = $location->lat;
            $estate->estate_longitude = $location->lng;
        }

    }


    private function setPublicTextGeneratorByApartment($estate)
    {
        if ($estate->estate_type_id === 1) {

            $sale1 = "";
            if ($estate->contract_type_id === 1) {
                $sale1 = trans('estate.' . "label.generating.text.apartment.sale.1");
            } else if ($estate->contract_type_id === 2) {
                $sale1 = trans('estate.' . "label.generating.text.apartment.rent.1");
            } else {
                $sale1 = trans('estate.' . "label.generating.text.apartment.fee.1");
            }

            $sale2 = trans('estate.' . "label.generating.text.apartment.sale.2");
            $sale3 = trans('estate.' . "label.generating.text.apartment.sale.3");
            $sale4 = trans('estate.' . "label.generating.text.apartment.sale.4");
            $sale5 = trans('estate.' . "label.generating.text.apartment.sale.5");
            $sale5One = trans('estate.' . "label.generating.text.apartment.sale.5.1");
            $sale6 = trans('estate.' . "label.generating.text.apartment.sale.6");
            $sale7 = trans('estate.' . "label.generating.text.apartment.sale.7");


            $sale7One = ($estate->entrance_type_id == null || $estate->entrance_type_id == 3)
                ? " " . trans('estate.' . "label.generating.text.apartment.sale.7.1") : "";


            $sale8 = trans('estate.' . "label.generating.text.apartment.sale.8");


            $sale8One = $estate->courtyard_improvement_id == 8 ? trans('estate.' . "label.generating.text.apartment.sale.8.1") . " " . $estate->courtyard_improvement->name_arm
                . trans('estate.' . "label.generating.text.apartment.sale.8.2") : "";


            $sale9 = trans('estate.' . "label.generating.text.apartment.sale.9");
            $sale10 = trans('estate.' . "label.generating.text.apartment.sale.10");
            $sale11 = trans('estate.' . "label.generating.text.apartment.sale.11");
            $sale12 = trans('estate.' . "label.generating.text.apartment.sale.12");
            $sale13 = trans('estate.' . "label.generating.text.apartment.sale.13");
            $sale14 = trans('estate.' . "label.generating.text.apartment.sale.14");


            $sale14One = $estate->uninhabited ? " " . trans('estate.' . "label.generating.text.apartment.sale.14.1") : "";


            $sale15 = trans('estate.' . "label.generating.text.apartment.sale.15");
            $sale15One = $estate->sunny ? " " . trans('estate.' . "label.generating.text.apartment.sale.15.1") : "";


            $sale15Two = trans('estate.' . "label.generating.text.apartment.sale.15.2");
            $sale16 = trans('estate.' . "label.generating.text.apartment.sale.16");
            $sale16One = "";
            $sale16Five = "";


            $sale16Nine = $estate->can_be_used_as_commercial ? " " . trans('estate.' . "label.generating.text.apartment.sale.16.9") : "";
            $sale17 = trans('estate.' . "label.generating.text.apartment.sale.17");


            $sale18 = '';


            if ($estate->contract_type_id === 1) {
                $sale18 = trans('estate.' . "label.generating.text.apartment.sale.18");
            } else if ($estate->contract_type_id === 2) {
                $sale18 = trans('estate.' . "label.generating.text.apartment.rent.3");
            } else {
                $sale18 = trans('estate.' . "label.generating.text.apartment.fee.2");
            }

            $sale19 = trans('estate.' . "label.generating.text.apartment.sale.19");

            $sale20 = sprintf(trans('estate.' . "label.generating.text.apartment.sale.20"),
                $estate->contract_type_id === 1 ? trans('estate.' . "label.generating.text.apartment.sale.20.1") :
                    trans('estate.' . "label.generating.text.apartment.sale.20.2"));

            $sale = trans('estate.' . "label.generating.text.1");

            $roomCount = $estate->room_count != null ? $estate->room_count : "...";
            $roomCountModified = ($estate->room_count_modified != null && $estate->room_count_modified != 0)
                ? ($sale . " " . strval($estate->room_count_modified) . " ") : "";
            $street = $estate->full_address != null ? $estate->full_address : "...";
            $buildingProjectType = $estate->building_project_type_id != null ? $estate->building_project_type->name_arm : "...";
            $buildingType = $estate->building_type != null ? $estate->building_type->name_arm : "...";
            $buildingFloorCount = $estate->building_floor_count != null ? $estate->building_floor_count : "...";
            $floor = $estate->floor === 1 ? $estate->floor : "...";
            $entranceType = $estate->entrance_type === 1 ? $estate->entrance_type->name_arm : "...";
            $entranceDoorType = $estate->entrance_door_type === 1 ? $estate->entrance_door_type->name_arm : "...";
            $ceilingHeightType = $estate->ceiling_height_type === 1 ? $estate->ceiling_height_type->name_arm : "...";
            $buildingWindowCount = $estate->building_window_count === 1 ? $estate->building_window_count->name_arm : "...";
            $repairingType = $estate->repairing_type === 1 ? $estate->repairing_type->name_arm : "...";
            $heatingSystemType = $estate->heating_system_type === 1 ? $estate->heating_system_type->name_arm : "...";


            $sale16OneList = array();
            if ($estate->open_balcony === 1) {
                $sale16OneList[] = trans('estate.label.generating.text.apartment.sale.16.2');
            }
            if ($estate->oriel === 1) {
                $sale16OneList[] = trans('estate.label.generating.text.apartment.sale.16.3');
            }
            if ($estate->balcony === 1) {
                $sale16OneList[] = trans('estate.label.generating.text.apartment.sale.16.4');
            }
            if (!empty($sale16OneList)) {
                $sale16One = trans('estate.label.generating.text.apartment.sale.16.1') . " "
                    . implode(", ", $sale16OneList)
                    . trans('estate.label.generating.text.apartment.sale.16');
            }


            $sale16FiveList = array();
            if ($estate->contract_type === 1) {
                if ($estate->new_water_tubes === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.sale.16.5');
                }
                if ($estate->new_wiring === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.sale.16.6');
                }
                if ($estate->new_windows === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.sale.16.7');
                }
                if (!empty($sale16FiveList)) {
                    $sale16Five = " " . implode(", ", $sale16FiveList)
                        . " " . trans('estate.label.generating.text.apartment.sale.16.8');
                }
            } else {
                if ($estate->natural_gas === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.rent.2.1');
                }
                if ($estate->gas_heater === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.rent.2.2');
                }
                if ($estate->refrigirator === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.rent.2.3');
                }
                if ($estate->washer === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.rent.2.4');
                }
                if ($estate->dish_washer === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.rent.2.5');
                }
                if ($estate->tv === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.rent.2.6');
                }
                if ($estate->conditioner === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.rent.2.7');
                }
                if ($estate->internet === 1) {
                    $sale16FiveList[] = trans('estate.label.generating.text.apartment.rent.2.8');
                }
                if (!empty($sale16FiveList)) {
                    $sale16Five = trans('estate.label.generating.text.apartment.rent.2') . " "
                        . implode(", ", $sale16FiveList)
                        . trans('estate.label.generating.text.apartment.sale.16');
                }
            }


            $areaTotal = $estate->area_total != null ? $estate->area_total : "...";


            $priceUSD = $estate->price_usd != null ? $estate->price_usd : "...";

            $text = '';
            $text = $sale1 . " " . $roomCount . " " . $sale2 . " " . $roomCountModified . " " . $street . " " . $sale3 . " " . $buildingProjectType . " "
                . $sale4 . " " . $buildingFloorCount . " " . $sale5 . " " . $buildingType . " " . $sale5One . " "
                . $floor . " " . $sale6 .  $sale7 . $sale7One . " " . $entranceType . " "
                . $sale8 . $sale8One . $sale9 . " " . $entranceDoorType
                . " " . $sale10 . " " . $ceilingHeightType . $sale11
                . " " . $buildingWindowCount . " " . $sale12 . "\n"
                . $sale13 . " " . $repairingType . " " . $sale14 . $sale14One
                . $sale15 . " " . $heatingSystemType . $sale15One . $sale16
                . " " . $sale16One . $sale16Five . $sale16Nine . "\n"
                . $sale17 . " " . $areaTotal . " " . $sale18 . " " . $priceUSD
                . " " . $sale19 . "\n" . $sale20;

            return $text;
        }
    }
}
