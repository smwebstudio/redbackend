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
            'type' => 'select2',
            'label' => 'Գործարքային տիպը',
        ], function () {
            return [
                1 => 'Վաճառք',
                2 => 'Վարձակալություն',
                3 => 'Օրավարձ',
            ];
        }, function ($value) {
            $this->crud->addClause('where', 'contract_type_id', $value);
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
                    return \App\Models\CLocationCommunity::all()->pluck('name_arm', 'id')->toArray();
                }, function ($values) {
                    $this->crud->addClause('whereIn', 'location_community_id', json_decode($values));
                });
            } else {
                $this->crud->addFilter([
                    'name' => 'location_city',
                    'type' => 'select2',
                    'label' => 'Քաղաք',
                ], function () {
                    $province = request('location_province');
                    return \App\Models\CLocationCity::where('parent_id', '=', json_decode($province))->pluck('name_arm', 'id')->toArray();
                }, function ($value) {
                    $this->crud->addClause('where', 'location_city_id', $value);
                });
            }
        }


        $this->crud->addFilter([
            'name' => 'location_street',
            'type' => 'select2',
            'label' => 'Փողոց',
        ], function () {
            return \App\Models\CLocationStreet::all()->pluck('name_arm', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'location_street_id', $value);
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
            'name' => 'apartment_construction',
            'label' => 'Կառուցապատողից'
        ],
            false,
            function () {
                $this->crud->addClause('where', 'apartment_construction', 1);
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
            'type' => 'divider',
            'name' => 'divider_1',
        ]);


        $this->crud->addFilter([
            'name' => 'currency',
            'type' => 'select2',
            'label' => 'Արժույթ',
        ], function () {
            return \App\Models\CCurrency::all()->pluck('name_arm', 'id')->toArray();
        }, function ($value) {
            $this->crud->addClause('where', 'currency_id', $value);
        });

        if (request('currency') && !request('extended_price')) {
            $currency = request('currency');
            if (!empty($currency)) {
                $this->crud->addFilter([
                    'name' => 'price_range',
                    'type' => 'select2_multiple_red',
                    'label' => 'Գնային միջակայք',
                ], function () use ($currency) {
                    if ($currency == 1) {
                        return \App\Models\CSellPriceInUsd::all()->pluck('name_arm', 'id')->toArray();
                    }
                    if ($currency == 2) {
                        return \App\Models\CSellPriceInRur::all()->pluck('name_arm', 'id')->toArray();
                    }
                    if ($currency == 3) {
                        return \App\Models\CSellPriceInAmd::all()->pluck('name_arm', 'id')->toArray();
                    }
                }, function ($values) {
                    $this->crud->addClause('whereIn', 'location_community_id', json_decode($values));
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
                    if ($range->from) {
                        $this->crud->addClause('where', 'price', '>=', (float)$range->from);
                    }
                    if ($range->to) {
                        $this->crud->addClause('where', 'price', '<=', (float)$range->to);
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
                if ($range->from) {
                    $this->crud->addClause('whereRaw', 'price_usd/area_total > ?', [(float)$range->from]);
                }
                if ($range->to) {
                    $this->crud->addClause('whereRaw', 'price_usd / estate.area_total < ?', [(float)$range->to]);
                }
            });


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
