@extends(backpack_view('blank'))

@php
    $defaultBreadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      $crud->entity_name_plural => url($crud->route),
      trans('backpack::crud.preview') => false,
    ];

    $estate = $entry;

    $images = $estate->estateDocuments;
    // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;

@endphp

@section('header')
    <div class="container-fluid d-flex justify-content-between my-3">
        <section class="header-operation animated fadeIn d-flex mb-2 align-items-end d-print-none"
                 bp-section="page-header">
            <h1 class="text-capitalize mb-0"
                bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
            <p class="ms-2 ml-2 mb-0"
               bp-section="page-subheading">{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}</p>
            @if ($crud->hasAccess('list'))
                <p class="ms-2 ml-2 mb-0" bp-section="page-subheading-back-button">
                    <small><a href="{{ url($crud->route) }}" class="font-sm"><i
                                class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }}
                            <span>{{ $crud->entity_name_plural }}</span></a></small>
                </p>
            @endif
        </section>
        <a href="javascript: window.print();" class="btn float-end float-right"><i class="la la-print"></i></a>
    </div>
@endsection

@section('content')
    <section class="main-container__main b-shadow bg-white p-4 mb-4">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-2xl">{{ $estate->short_description ?? '' }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 main-container__main--left slider-container">
                <div class="swiper EstatesSlider">
                    <!-- Slider main container -->
                    <div class="swiper-wrapper">
                        @foreach($images as $image)
                            <div class="swiper-slide">
                                <div class="swiper-zoom-container">
                                    <img src="{{ Storage::disk('S3Public')->url('/estate/photos/'.$image->path) }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next bg-light"></div>
                    <div class="swiper-button-prev bg-light"></div>
                </div>

                <div class="swiper SwiperThumbs">
                    <div class="swiper-wrapper">
                        @foreach($images as $image)
                            <div class="swiper-slide mr-2">
                                <img src="{{ Storage::disk('S3Public')->url('/estate/photos/'.$image->path_thumb) }}">
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="col-md-5 pt-16">
                <p class="text-xl mb-12">
                    <span
                        class="bg-green text-white p-4">{{ isset($estate->contract_type) ? $estate->contract_type->name_arm : '' }}</span>
                    <span>{{ isset($estate->code) ? $estate->code : '' }}</span>
                </p>
                <p class="text-sm">{{ isset($estate->public_text_arm) ? $estate->public_text_arm : '' }}</p>
                <div class="flex justify-content-left mt-10 flex-row">
                    <div class="p-2 bg-slate-100">
                        Շենք: {{ isset($estate->address_building) ? $estate->address_building : '' }}</div>
                    <div class="p-2 ml-2 bg-slate-100">Հարկ: {{ isset($estate->floor) ? $estate->floor : '' }}
                        /{{ isset($estate->building_floor_count) ? $estate->building_floor_count : '' }}</div>
                    <div class="p-2 ml-2 bg-slate-100">
                        Սենյակներ: {{ isset($estate->room_count) ? $estate->room_count : '' }}</div>
                </div>
                <div class="flex justify-content-left mt-2 flex-row">
                    <div class="p-2 bg-slate-100">Առաստաղի
                        բարձրություն: {{ isset($estate->ceiling_height_type) ? $estate->ceiling_height_type->name_arm : '' }}</div>
                    <div class="p-2 ml-2 bg-slate-100">Ընդհանուր
                        մակերես: {{ isset($estate->area_total) ? $estate->area_total : '' }}</div>
                </div>
                <div class="flex justify-content-left mt-2 flex-row">
                    <div class="p-2 bg-slate-100">1քմ
                        արժեք: {{ isset($estate->price_per_square) ? $estate->price_per_square : '' }}</div>
                    <div class="p-2 ml-2 bg-slate-100">
                        Գին: {{ isset($estate->full_price) ? $estate->full_price : '' }}</div>
                </div>
            </div>

            <div class="col-md-2 pt-16 border-2 border-solid">
                <p class="text-sm ">{{ isset($estate->agent->contact) ? $estate->agent->contact->fullName : '' }}</p>
                <p class="text-sm mt-4 mb-4 "><img width="150px"
                                                   src="{{
    isset($estate->agent->profile_picture_path) ? Storage::disk('S3Public')->url('/estate/photos/'.$estate->agent->profile_picture_path) : ''
    }}"/>
                </p>
                <p class="text-sm ">{{ isset($estate->agent->contact) ? $estate->agent->contact->email : '' }}</p>
                <p class="text-sm ">{{ isset($estate->agent->contact) ? $estate->agent->contact->phone_mobile_1 : '' }}</p>

            </div>


        </div>
    </section>
    <div class="row" bp-section="crud-operation-show">
        <div class="{{ $crud->getShowContentClass() }}">

            {{-- Default box --}}
            <div class="">
                @if ($crud->model->translationEnabled())
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            {{-- Change translation button group --}}
                            <div class="btn-group float-right">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                        data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                    {{trans('backpack::crud.language')}}
                                    : {{ $crud->model->getAvailableLocales()[request()->input('_locale')?request()->input('_locale'):App::getLocale()] }}
                                    &nbsp; <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                                        <a class="dropdown-item"
                                           href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}?_locale={{ $key }}">{{ $locale }}</a>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                @if($crud->tabsEnabled() && count($crud->getUniqueTabNames('columns')))
                    @include('crud::inc.show_tabbed_table')
                @else
                    <div class="card no-padding no-border mb-0">
                        @include('crud::inc.show_table', ['columns' => $crud->columns()])
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


@push('after_scripts')
    <script>
        $(document).ready(function () {
            var swiper = new Swiper(".SwiperThumbs", {
                spaceBetween: 2,
                slidesPerView: 5,
                freeMode: true,
                watchSlidesProgress: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
            var SwiperThumbs = new Swiper(".EstatesSlider", {

                effect: 'fade',
                spaceBetween: 10,
                loop: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                },
                thumbs: {
                    swiper: swiper,
                },

                zoom: {
                    maxRatio: 5,
                },
            });
        })

    </script>
@endpush
@section('after_styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            important: true,
        }
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/views.css') }}">
@endsection
@push('after_styles')
    <style>

        .slider-container {
            flex-direction: column;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .SwiperThumbs .swiper-slide img {
            display: block;
            width: 120px;
            height: 70px;
            object-fit: cover;
        }


        .swiper {
            width: 600px;
            height: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .swiper-slide {
            background-size: cover;
            background-position: center;
        }

        .SwiperThumbs {
            height: 120px;
            width: 100%;
        }

        .EstatesSlider {
            height: 500px;
            box-sizing: border-box;
            padding: 10px 0;
        }

        .Swiper .swiper-slide {
            width: 25%;
            height: 100%;
            opacity: 0.4;
        }

        .Swiper .swiper-slide-thumb-active {
            opacity: 1;
        }


    </style>
@endpush


