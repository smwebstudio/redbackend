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
//       dd(request()->input('estateDocuments'), $estate);
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


//    private function setPublicTextGeneratorByApartment($estate)
//    {
//        if ($estate->estate_type_id === 1) {
//
//            $sale1 = "";
//            if ($estate->contract_type_id === 1) {
//                $sale1 = trans('estate.' . "label.generating.text.apartment.sale.1");
//            } else if ($estate->contract_type_id === 2) {
//                $sale1 = trans('estate.' . "label.generating.text.apartment.rent.1");
//            } else {
//                $sale1 = trans('estate.' . "label.generating.text.apartment.fee.1");
//            }
//
//            $sale2 = trans('estate.' . "label.generating.text.apartment.sale.2");
//            $sale3 = trans('estate.' . "label.generating.text.apartment.sale.3");
//            $sale4 = trans('estate.' . "label.generating.text.apartment.sale.4");
//            $sale5 = trans('estate.' . "label.generating.text.apartment.sale.5");
//            $sale5One = trans('estate.' . "label.generating.text.apartment.sale.5.1");
//            $sale6 = trans('estate.' . "label.generating.text.apartment.sale.6");
//            $sale7 = trans('estate.' . "label.generating.text.apartment.sale.7");
//            $sale7One = $estate . getEntranceType() == null || $estate . getEntranceType() . getId() . equals(Constants . ENTRANCE_TYPE_DOUBLE_ID)
//                ? " " + trans('estate.' . "label.generating.text.apartment.sale.7.1") : "";
//            $sale8 = trans('estate.' . "label.generating.text.apartment.sale.8");
//            $sale8One = $estate . getCourtyardImprovement() != null
//            && $estate . getCourtyardImprovement() . getId() . equals(Constants . COURTYARD_IMPROVEMENT_COMFORTABLE_ID)
//                ? trans('estate.' . "label.generating.text.apartment.sale.8.1") + " " + $estate . getCourtyardImprovement() . getName()
//                + trans('estate.' . "label.generating.text.apartment.sale.8.2") : "";
//
//
//            $sale9 = trans('estate.' . "label.generating.text.apartment.sale.9");
//            $sale10 = trans('estate.' . "label.generating.text.apartment.sale.10");
//            $sale11 = trans('estate.' . "label.generating.text.apartment.sale.11");
//            $sale12 = trans('estate.' . "label.generating.text.apartment.sale.12");
//            $sale13 = trans('estate.' . "label.generating.text.apartment.sale.13");
//            $sale14 = trans('estate.' . "label.generating.text.apartment.sale.14");
//            $sale14One = $estate . getUnInhabited() != null && $estate . getUnInhabited()
//                ? " " + trans('estate.' . "label.generating.text.apartment.sale.14.1") : "";
//            $sale15 = trans('estate.' . "label.generating.text.apartment.sale.15");
//            $sale15One = $estate . getSunny() != null && $estate . getSunny()
//                ? " " + trans('estate.' . "label.generating.text.apartment.sale.15.1") : "";
//            $sale15Two = trans('estate.' . "label.generating.text.apartment.sale.15.2");
//            $sale16 = trans('estate.' . "label.generating.text.apartment.sale.16");
//            $sale16One = "";
//            $sale16Five = "";
//            $sale16Nine = $estate . getCanBeUsedAsCommercial() != null && $estate . getCanBeUsedAsCommercial()
//                ? " " + trans('estate.' . "label.generating.text.apartment.sale.16.9") : "";
//            $sale17 = trans('estate.' . "label.generating.text.apartment.sale.17");
//
//
//            $sale18 = '';
//
//
//            if (entity . isContractTypeSaleEstate()) {
//                $sale18 = trans('estate.' . "label.generating.text.apartment.sale.18");
//            } else if (entity . isContractTypeRentingEstate()) {
//                $sale18 = trans('estate.' . "label.generating.text.apartment.rent.3");
//            } else {
//                $sale18 = trans('estate.' . "label.generating.text.apartment.fee.2");
//            }
//
//            $sale19 = trans('estate.' . "label.generating.text.apartment.sale.19");
//            $sale20 = String . format(trans('estate.' . "label.generating.text.apartment.sale.20"),
//                    entity . isContractTypeSaleEstate() ? trans('estate.' . "label.generating.text.apartment.sale.20.1") :
//                        trans('estate.' . "label.generating.text.apartment.sale.20.2"));
//
//            $sale = trans('estate.' . "label.generating.text.1");
//
//            $roomCount = entity . getRoomCount() != null ? String . valueOf(entity . getRoomCount()) : "...";
//            $roomCountModified = entity . getRoomCountModified() != null && entity . getRoomCountModified() != 0 ? (sale + " " + String . valueOf(entity . getRoomCountModified()) + " ") : "";
//            $street = entity . getStreetAddress() != null ? entity . getStreetAddress() . trim() . replace(",", "`") : "...";
//            $buildingProjectType = $estate . getBuildingProjectType() != null ? $estate . getBuildingProjectType() . getName() : "...";
//            $buildingType = $estate . getBuildingType() != null ? $estate . getBuildingType() . getName() : "...";
//            $buildingFloorCount = $estate . getBuildingFloorCount() != null ? String . valueOf($estate . getBuildingFloorCount()) : "...";
//            $floor = $estate . getFloor() != null ? String . valueOf($estate . getFloor()) : "...";
//            $entranceType = $estate . getEntranceType() != null ? $estate . getEntranceType() . getName() : "...";
//            $entranceDoorType = $estate . getEntranceDoorType() != null ? $estate . getEntranceDoorType() . getName() : "...";
//            $ceilingHeightType = $estate . getCeilingHeightType() != null ? $estate . getCeilingHeightType() . getName() : "...";
//            $buildingWindowCount = $estate . getBuildingWindowCount() != null ? $estate . getBuildingWindowCount() . getName() : "...";
//            $repairingType = $estate . getRepairingType() != null ? $estate . getRepairingType() . getName() : "...";
//            $heatingSystemType = $estate . getHeatingSystemType() != null && !$estate . getHeatingSystemType() . getId() . equals(Constants . HEATING_SYSTEM_NON_TYPE_ID) ? $estate . getHeatingSystemType() . getName() + sale15Two : "...";
//            list<String > sale16OneList = new ArrayList <> ();
//            if ($estate . getHasOpenBalcony() != null && $estate . getHasOpenBalcony()) {
//                sale16OneList . add(trans('estate.' . "label.generating.text.apartment.sale.16.2"));
//            }
//            if ($estate . getHasOriel() != null && $estate . getHasOriel()) {
//                sale16OneList . add(trans('estate.' . "label.generating.text.apartment.sale.16.3"));
//            }
//            if ($estate . getHasBalcony() != null && $estate . getHasBalcony()) {
//                sale16OneList . add(trans('estate.' . "label.generating.text.apartment.sale.16.4"));
//            }
//            if (!sale16OneList . isEmpty()) {
//                sale16One = trans('estate.' . "label.generating.text.apartment.sale.16.1") + " "
//                    + StringUtils . collectionToDelimitedString(sale16OneList, ", ")
//                    + trans('estate.' . "label.generating.text.apartment.sale.16");
//            }
//            list<String > sale16FiveList = new ArrayList <> ();
//            if (entity . isContractTypeSaleEstate()) {
//                if ($estate . getHasNewWaterTubes() != null && $estate . getHasNewWaterTubes()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.sale.16.5"));
//                }
//                if ($estate . getHasNewWiring() != null && $estate . getHasNewWiring()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.sale.16.6"));
//                }
//                if ($estate . getNewWindows() != null && $estate . getNewWindows()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.sale.16.7"));
//                }
//                if (!sale16FiveList . isEmpty()) {
//                    sale16Five = " " + StringUtils . collectionToDelimitedString(sale16FiveList, ", ")
//                        + " " + trans('estate.' . "label.generating.text.apartment.sale.16.8");
//                }
//            } else {
//                if ($estate . getHasNaturalGas() != null && $estate . getHasNaturalGas()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.rent.2.1"));
//                }
//                if ($estate . getHasGasHeater() != null && $estate . getHasGasHeater()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.rent.2.2"));
//                }
//                if ($estate . getHasRefrigerator() != null && $estate . getHasRefrigerator()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.rent.2.3"));
//                }
//                if ($estate . getHasWasher() != null && $estate . getHasWasher()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.rent.2.4"));
//                }
//                if ($estate . getHasDishWasher() != null && $estate . getHasDishWasher()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.rent.2.5"));
//                }
//                if ($estate . getHasTV() != null && $estate . getHasTV()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.rent.2.6"));
//                }
//                if ($estate . getHasConditioner() != null && $estate . getHasConditioner()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.rent.2.7"));
//                }
//                if ($estate . getHasInternet() != null && $estate . getHasInternet()) {
//                    sale16FiveList . add(trans('estate.' . "label.generating.text.apartment.rent.2.8"));
//                }
//                if (!sale16FiveList . isEmpty()) {
//                    sale16Five = trans('estate.' . "label.generating.text.apartment.rent.2") + " "
//                        + StringUtils . collectionToDelimitedString(sale16FiveList, ", ")
//                        + trans('estate.' . "label.generating.text.apartment.sale.16");
//                }
//            }
//            $areaTotal = $estate->area_total != null ? (!"0.0" . equals(String . valueOf($estate . getAreaTotal() % 1)) ?
//                String . valueOf($estate . getAreaTotal()) : String . valueOf($estate . getAreaTotal() . intValue())) : "...";
//            $priceUSD = $estate . getPriceUsd() != null ? String . valueOf(!$estate . getPriceUsd() . ulp() . toString() . equals("0.00") ?
//                    $estate . getPriceUsd() : $estate . getPriceUsd() . intValue()) : "...";
//
//            $text = '';
//            $text = $sale1 . " " . $roomCount . " " . $sale2 . " " . $roomCountModified . " " . $street . " " . $sale3 . " " . $buildingProjectType . " "
//                . $sale4 . " " . $buildingFloorCount . " " . $sale5 . " " . $buildingType . " " . $sale5One . " "
//                . $floor . " " . $sale6 . "\n"
//                . $sale7 . $sale7One . " " . $entranceType . " "
//                . $sale8 . $sale8One . $sale9 . " " . $entranceDoorType
//                . " " . $sale10 . " " . $ceilingHeightType . $sale11
//                . " " . $buildingWindowCount . " " . $sale12 . "\n"
//                . $sale13 . " " . $repairingType . " " . $sale14 . $sale14One
//                . $sale15 . " " . $heatingSystemType . $sale15One . $sale16
//                . " " . $sale16One . $sale16Five . $sale16Nine . "\n"
//                . $sale17 . " " . $areaTotal . " " . $sale18 . " " . $priceUSD
//                . " " . $sale19 . "\n" . $sale20;
//
//            return $text;
//        }
//    }
}
