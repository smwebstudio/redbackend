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
        <section class="header-operation animated fadeIn d-flex mb-2 align-items-end d-print-none" bp-section="page-header">
            <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
            <p class="ms-2 ml-2 mb-0" bp-section="page-subheading">{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}</p>
            @if ($crud->hasAccess('list'))
                <p class="ms-2 ml-2 mb-0" bp-section="page-subheading-back-button">
                    <small><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
                </p>
            @endif
        </section>
        <a href="javascript: window.print();" class="btn float-end float-right"><i class="la la-print"></i></a>
    </div>
@endsection

@section('content')
    <section class="main-container__main b-shadow">
        <div class="row">
            <div class="col-md-12">
                {{ $estate->short_description }}
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
            <div class="col-md-4">
                <div class="main-container__main--info">
                    <h2 class="hospital-name">
                        {{  $estate->name_prefix ?? $estate->refer }}
                        {{  $estate->full_name }}
                        <span>ID: {{ $estate->id }}</span>
                    </h2>
                    <ul class="info-list">

                        <li>{{ $estate->location_province?->name_arm }}</li>
                    </ul>
                </div>
                <div class="main-container__main--actions-date">
                    Created: {{ $estate->created_at }}
                </div>
                <div class="main-container__main--status">
                    @can('expert.create')
                        Status: <span
                            class="{{ $estate->published ? 'text-green' : 'text-orange' }}">{{ $estate->published ? 'Published' : 'Draft' }}</span>
                    @endcan
                </div>
                <div class="main-container__main--actions-wrapper">


                </div>
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
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[request()->input('_locale')?request()->input('_locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                                        <a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}?_locale={{ $key }}">{{ $locale }}</a>
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
            height:500px;
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


