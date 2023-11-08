<?php

namespace App\Traits\Controllers;

use App\Models\Contact;
use App\Models\RealtorUser;
use Illuminate\Database\Eloquent\Builder;

trait HasEstateFilters
{
    private function addListFilters(): void
    {


        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'apartment',
            'label' => 'ԲՆԱԿԱՐԱՆ'
        ],
            false,
            function () {
                $this->crud->addClause('apartment');
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'house',
            'label' => 'ԱՌԱՆՁՆԱՏՈՒՆ'
        ],
            false,
            function () {
                $this->crud->addClause('house');
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'townhouse',
            'label' => 'ԹԱՈՒՆՀԱՈՒՍ'
        ],
            false,
            function () {
                $this->crud->addClause('townhouse');
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'Commercial',
            'label' => 'ԿՈՄԵՐՑԻՈՆ'
        ],
            false,
            function () {
                $this->crud->addClause('commercial');
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'land',
            'label' => 'ՀՈՂ'
        ],
            false,
            function () {
                $this->crud->removeAllFilters();
                $this->crud->addClause('land');
            });
        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_types',
        ]);

        // select2 filter
        $this->crud->addFilter([
            'name' => 'contract_type',
            'type' => 'select2_multiple_red',
            'label' => 'Գործարքային տիպը',
        ], function () {
            return [
                1 => 'Վաճառք',
                2 => 'Վարձակալություն',
                3 => 'Օրավարձ',
            ];
        }, function ($values) {
            $this->crud->addClause('whereIn', 'contract_type_id', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'location_province',
            'type' => 'select2',
            'label' => 'Մարզ',
        ], function () {
            return \App\Models\CLocationProvince::all()->pluck('name_arm', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'location_province_id', $value);
        });

        if (request('location_province')) {
            $province = request('location_province');

            if ($province == 1) {
                $this->crud->addFilter([
                    'name' => 'location_community',
                    'type' => 'select2_multiple_red',
                    'label' => 'Համայնք',
                ], function () {
                    return \App\Models\CLocationCommunity::orderBy('sort_id')->pluck('name_arm', 'id')->toArray();
                }, function ($values) {
                    $this->crud->addClause('whereIn', 'location_community_id', json_decode($values));
                });
            } else {
                $this->crud->addFilter([
                    'name' => 'location_city',
                    'type' => 'select2_multiple_red',
                    'label' => 'Քաղաք',
                ], function () {
                    $province = request('location_province');
                    return \App\Models\CLocationCity::where('parent_id', '=', json_decode($province))->pluck('name_arm', 'id')->toArray();
                }, function ($values) {
                    $this->crud->addClause('whereIn', 'location_city_id', json_decode($values));
                });
            }
        }


        $this->crud->addFilter([
            'name' => 'location_street',
            'type' => 'select2_multiple_red',
            'label' => 'Փողոց',
        ], function () {
            return \App\Models\CLocationStreet::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'location_street_id', json_decode($values));
        });

        $this->crud->addFilter([
            'type' => 'text',
            'name' => 'address_building',
            'label' => 'Շենք',
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'address_building', '=', $value);
            });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_9',
        ]);

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'isExclusive',
            'label' => 'Միայն էքսկլուզիվ գույքերը'
        ],
            false,
            function () {
                $this->crud->addClause('isExclusive');
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'isNotExclusive',
            'label' => 'Բացի էքսկլյուզիվ գույքերից'
        ],
            false,
            function () {
                $this->crud->addClause('isNotExclusive');
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_exchangeable',
            'label' => 'Փոխանակելի'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'exchange', 1);
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_urgent',
            'label' => 'Շտապ'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_urgent', 1);
            });


        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'apartment_construction',
            'label' => 'Կառուցապատողից'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'apartment_construction', 1);
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'ready_from_archive',
            'label' => 'Պատրաստ է վերակ. արխիվից'
        ], false, function () {
            $currentDate = \Carbon\Carbon::now();
            $this->crud->addClause('whereDate', 'archive_till_date', '<=', $currentDate->toDateString());
            $this->crud->addClause('whereNotNull', 'archive_till_date');
            $this->crud->addClause('where', 'estate_status_id', '=', 8);
        });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_from_public',
            'label' => 'Միայն հայտեր'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_from_public', 1);
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'is_ready_from_rent',
            'label' => 'Պատրաստ է վերակ. վարձակալումից'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'estate_status_id', '=', 6);
                $this->crud->addClause('isReadyFromRent');
            });




        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_1',
        ]);


//        $this->crud->addFilter([
//            'name' => 'currency',
//            'type' => 'select2',
//            'label' => 'Արժույթ',
//        ], function () {
//            return \App\Models\CCurrency::all()->pluck('name_arm', 'id')->toArray();
//        }, function ($value) {
////            $this->crud->addClause('where', 'currency_id', $value);
//        });

        if (!request('extended_price')) {
            $currency = session('currency') ? session('currency') : 'AMD';
            $contractType = request('contract_type');

            if (!empty($currency)) {
                $this->crud->addFilter([
                    'name' => 'price_range',
                    'type' => 'select2',
                    'label' => 'Գնային միջակայք',
                ], function () use ($currency,$contractType) {
                    if ($currency == 'USD') {
                        if($contractType == 2) {
                            return \App\Models\CRentPriceInUsd::all()->pluck('name_arm', 'name_eng')->toArray();
                        }
                        if($contractType == 3) {
                            return \App\Models\CDailyPriceInUsd::all()->pluck('name_arm', 'name_eng')->toArray();
                        }
                        return \App\Models\CSellPriceInUsd::all()->pluck('name_arm', 'name_eng')->toArray();
                    }
                    if ($currency == 'RUB') {
                        if($contractType == 2) {
                            return \App\Models\CRentPriceInRur::all()->pluck('name_arm', 'name_eng')->toArray();
                        }
                        if($contractType == 3) {
                            return \App\Models\CDailyPriceInRur::all()->pluck('name_arm', 'name_eng')->toArray();
                        }
                        return \App\Models\CSellPriceInRur::all()->pluck('name_arm', 'name_eng')->toArray();
                    }
                    if ($currency == 'AMD') {
                        if($contractType == 2) {
                            return \App\Models\CRentPriceInAmd::all()->pluck('name_arm', 'name_eng')->toArray();
                        }
                        if($contractType == 3) {
                            return \App\Models\CDailyPriceInAmd::all()->pluck('name_arm', 'name_eng')->toArray();
                        }
                        return \App\Models\CSellPriceInAmd::all()->pluck('name_arm', 'name_eng')->toArray();
                    }
                }, function ($values) {
                    $currency = session('currency') ? session('currency') : 'AMD';

                    preg_match('/(\d+)/', $values, $matches);

                    if (strpos($values, 'Up to') !== false) {
                        $range = [null, (int) $matches[1]];
                    } elseif (strpos($values, 'and more') !== false) {
                        $range = [(int) $matches[1], null];
                    } else {
                        if ($currency == 'AMD') {
                            preg_match('/(\d+) million/', $values, $matches);
                            $range = [(int) $matches[0]* 1000000, (int) $matches[1] * 1000000];
                        } else {
                            preg_match('/(\d+)-(\d+)/', $values, $matches);
                            $range = [(int) $matches[1], (int) $matches[2]];

                        }

                    }


                    if ($currency == 'AMD') {
                        if ($range[0]) {
                            $this->crud->addClause('where', 'price_amd', '>=', (float)$range[0]);
                        }
                        if ($range[1]) {
                            $this->crud->addClause('where', 'price_amd', '<=', (float)$range[1]);
                        }
                    }

                    if ($currency == 'RUB') {
                        if ($range[0]) {
                            $this->crud->addClause('where', 'price_amd', '>=', (float)$range[0] * 4);
                        }
                        if ($range[1]) {
                            $this->crud->addClause('where', 'price_amd', '<=', (float)$range[1] * 4);
                        }
                    }

                    if ($currency == 'USD') {
                        if ($range[0]) {
                            $this->crud->addClause('where', 'price_amd', '>=', (float)$range[0] * 390);
                        }
                        if ($range[1]) {
                            $this->crud->addClause('where', 'price_amd', '<=', (float)$range[1] * 390);
                        }
                    }


                });
            }


        }

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'extended_price',
            'label' => 'Ընդլայնված',
        ],
            false,
            function () {
                //For frontend purposes, DO NOT REMOVE
            });

        if (request('extended_price')) {

            $this->crud->addFilter([
                'name' => 'price',
                'type' => 'range',
                'label' => 'Գին',
            ],
                false,
                function ($value) {
                    $range = json_decode($value);


                    $currency = session('currency') ? session('currency') : 'AMD';

                    if ($currency == 'AMD') {
                        if ($range->from) {
                            $this->crud->addClause('where', 'price_amd', '>=', (float)$range->from);
                        }
                        if ($range->to) {
                            $this->crud->addClause('where', 'price_amd', '<=', (float)$range->to);
                        }
                    }

                    if ($currency == 'RUB') {
                        if ($range->from) {
                            $this->crud->addClause('where', 'price_amd', '>=', (float)$range->from * 4);
                        }
                        if ($range->to) {
                            $this->crud->addClause('where', 'price_amd', '<=', (float)$range->to * 4);
                        }
                    }

                    if ($currency == 'USD') {
                        if ($range->from) {
                            $this->crud->addClause('where', 'price_amd', '>=', (float)$range->from * 390);
                        }
                        if ($range->to) {
                            $this->crud->addClause('where', 'price_amd', '<=', (float)$range->to * 390);
                        }
                    }

                });
        }

        $this->crud->addFilter([
            'name' => 'price_sqm',
            'type' => 'range',
            'label' => 'ք/մ գին',
        ],
            false,
            function ($value) {
                $range = json_decode($value);

                $currency = session('currency') ? session('currency') : 'AMD';

                if ($currency == 'AMD') {
                    if ($range->from) {
                        $this->crud->addClause('whereRaw', 'price_amd/area_total > ?', [(float)$range->from]);
                    }
                    if ($range->to) {
                        $this->crud->addClause('whereRaw', 'price_amd / estate.area_total < ?', [(float)$range->to]);
                    }
                }

                if ($currency == 'RUB') {
                    if ($range->from) {
                        $this->crud->addClause('whereRaw', 'price_amd/area_total > ?', [(float)$range->from * 4]);
                    }
                    if ($range->to) {
                        $this->crud->addClause('whereRaw', 'price_amd / estate.area_total < ?', [(float)$range->to * 4]);
                    }
                }

                if ($currency == 'USD') {
                    if ($range->from) {
                        $this->crud->addClause('whereRaw', 'price_amd/area_total > ?', [(float)$range->from * 390]);
                    }
                    if ($range->to) {
                        $this->crud->addClause('whereRaw', 'price_amd / estate.area_total < ?', [(float)$range->to * 390]);
                    }
                }
            });


        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_price_discount',
        ]);

        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'price_down_on_from',
            'label' => 'Սակարկված սկսած'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'price_down_on', '>=', $value);
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'price_down_on_to',
            'label' => 'Սակարկված մինչև'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'price_down_on', '<=', $value . ' 23:59:59');
            });


        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_price',
        ]);

        $this->crud->addFilter([
            'name' => 'area',
            'type' => 'range',
            'label' => 'Մակերես',
        ],
            false,
            function ($value) {
                $range = json_decode($value);
                if ($range->from) {
                    $this->crud->addClause('where', 'area_total', '>=', (float)$range->from);
                }
                if ($range->to) {
                    $this->crud->addClause('where', 'area_total', '<=', (float)$range->to);
                }
            });

        $this->crud->addFilter([
            'name' => 'room_count',
            'type' => 'select2_multiple_red',
            'label' => 'Սենյակներ',
        ], function () {
            return \App\Models\CRoomsQuantity::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $valuesArray = json_decode($values, true);
            if (in_array('4+', $valuesArray)) {
                // Add values 5 through 15 to the array
                for ($i = 5; $i <= 15; $i++) {
                    $valuesArray[] = (string)$i;
                }
            }

            $this->crud->addClause('whereIn', 'room_count', $valuesArray);

        });

//        $this->crud->addFilter([
//            'type'  => 'simple',
//            'name'  => 'detailed_area',
//            'label' => 'Ընդլայնված'
//        ],
//            false,
//            function() { // if the filter is active
//                // $this->crud->addClause('active'); // apply the "active" eloquent scope
//            } );

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_3',
        ]);

        $this->crud->addFilter([
            'name' => 'building_project_type',
            'type' => 'select2_multiple_red',
            'label' => 'Շենքի նախագիծը',
        ], function () {
            return \App\Models\CBuildingProjectType::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'building_project_type_id', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'building_type',
            'type' => 'select2_multiple_red',
            'label' => 'Արտաքին պատեր',
        ], function () {
            return \App\Models\CBuildingType::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'building_type_id', json_decode($values));
        });


        $this->crud->addFilter([
            'name' => 'floor_count',
            'type' => 'select2_multiple_red',
            'label' => 'Արտաքին պատեր',
        ], function () {
            return \App\Models\CFloorsQuantity::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'floor_count_id', json_decode($values));
        });

        $this->crud->addFilter([
            'name' => 'repairing_type',
            'type' => 'select2_multiple_red',
            'label' => 'Վերանորոգման տեսակ',
        ], function () {
            return \App\Models\CRepairingType::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'repairing_type_id', json_decode($values));
        });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_4',
        ]);


        $this->crud->addFilter([
            'name' => 'estate_status',
            'type' => 'select2_multiple_red',
            'label' => 'Կարգավիճակ',
        ], function () {
            return \App\Models\CEstateStatus::all()->pluck('name_arm', 'id')->toArray();
        }, function ($values) {
            $this->crud->addClause('whereIn', 'estate_status_id', json_decode($values));
        });


        $this->crud->addFilter([
            'name' => 'agents',
            'type' => 'select2',
            'label' => 'Գործակալ',
        ], function () {

            return RealtorUser::join('contact', 'contact.id', '=', 'realtor_user.contact_id')
//                ->join('professional_profession', 'professional_profession.user_id', '=', 'realtor_user.id')
                ->join('realtor_user_role', 'realtor_user_role.user_id', '=', 'realtor_user.id')
                ->where('contact.contact_type_id', 3)
                ->whereNotNull('contact.name_arm')
                ->whereIn('realtor_user_role.role_id', [4,6,7,8])
//                ->whereIn('professional_profession.profession_id', [-2, -3])
                ->select('realtor_user.*')
                ->get()
                ->pluck('contact.fullName', 'id')
                ->toArray();


        }, function ($value) {
            $this->crud->addClause('where', 'agent_id', $value);
        });


        $this->crud->addFilter([
            'name' => 'info_source',
            'type' => 'select2',
            'label' => 'Ինֆորմացիայի աղբյուր',
        ], function () {
            return RealtorUser::whereHas('contact', function (Builder $query) {
                $query->whereNotNull(['name_arm']);
            })->with('contact')->get()->pluck('contact.fullName', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'info_source_id', $value);
        });

        $this->crud->addFilter([
            'name' => 'property_agent',
            'type' => 'select2',
            'label' => 'Տեղազննող Գործակալ',
        ], function () {
            return RealtorUser::whereHas('contact', function (Builder $query) {
                $query->whereNotNull(['name_arm']);
            })->with('contact')->get()->pluck('contact.fullName', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'property_agent_id', $value);
        });

        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_4_others',
        ]);




        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'created_at_from',
            'label' => 'Ստեղծված սկսած'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'created_at', '>=', $value);
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'created_at_to',
            'label' => 'Ստեղծված մինչև'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'created_at', '<=', $value . ' 23:59:59');
            });

        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'updated_at_from',
            'label' => 'Թարմացված սկսած'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'updated_at', '>=', $value);
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'updated_at_to',
            'label' => 'Թարմացված մինչև'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'updated_at', '<=', $value . ' 23:59:59');
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'filled_on_from',
            'label' => 'Տեղազնված սկսած'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'filled_on', '>=', $value);
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'filled_on_to',
            'label' => 'Տեղազնված մինչև'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'filled_on', '<=', $value . ' 23:59:59');
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'verified_on_from',
            'label' => 'Հաստատված սկսած'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'verified_on', '>=', $value);
            });


        $this->crud->addFilter([
            'type' => 'date',
            'name' => 'verified_on_to',
            'label' => 'Հաստատված մինչև'
        ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'verified_on', '<=', $value . ' 23:59:59');
            });


        $this->crud->addFilter([
            'type' => 'divider',
            'name' => 'divider_end_filters',
        ]);


    }

}
