<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EstateCollection;
use App\Http\Resources\EstateDetailsCollection;
use App\Http\Resources\EstateResource;
use App\Models\Estate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\PointCheck;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EstatesController extends Controller
{


    public function filterEstates(Request $request)
    {

        $pageSize = $request->input('page_size') ? $request->input('page_size') : 12;

        $request_coords = $request->input('filter.coordinates');
        $fromMap = $request->input('fromMap');

        if($fromMap) {
            Log:;info('from map');
            $pageSize = 50;
        }


        return new EstateCollection(QueryBuilder::for(Estate::class)
            ->allowedFilters([
                AllowedFilter::exact('id', ),
                AllowedFilter::scope('price_from'),
                AllowedFilter::scope('price_to'),
                AllowedFilter::scope('text_search'),
                AllowedFilter::scope('coordinates', null, '|'),
                AllowedFilter::exact('currency_id'),
                AllowedFilter::exact('room_count'),
                AllowedFilter::exact('estate_type_id'),
                AllowedFilter::exact('contract_type_id'),
                AllowedFilter::exact('location_province_id'),
                AllowedFilter::exact('location_city_id'),
                AllowedFilter::exact('location_community_id'),
            ])
            ->allowedSorts(['created_on', 'visits_count', 'room_count'])
            ->paginate($pageSize)
            ->appends(request()->query()));


    }


    public function compareEstates(Request $request)
    {

        $pageSize = $request->input('page_size') ? $request->input('page_size') : 50;



        return new EstateDetailsCollection(QueryBuilder::for(Estate::class)
            ->allowedFilters([
                AllowedFilter::exact('id', ),
                AllowedFilter::scope('price_from'),
                AllowedFilter::scope('price_to'),
                AllowedFilter::scope('text_search'),
                AllowedFilter::scope('coordinates', null, '|'),
                AllowedFilter::exact('currency_id'),
                AllowedFilter::exact('room_count'),
                AllowedFilter::exact('estate_type_id'),
                AllowedFilter::exact('contract_type_id'),
                AllowedFilter::exact('location_province_id'),
                AllowedFilter::exact('location_city_id'),
                AllowedFilter::exact('location_community_id'),
            ])
            ->allowedSorts(['created_on', 'visits_count', 'room_count'])
            ->paginate($pageSize)
            ->appends(request()->query()));


    }


    public function mapSearch(Request $request)
    {

        if($request->input('coords')) {
            $request_coords = json_decode($request->input('coords'));


            $polygon_coords = '';
            foreach ($request_coords as $coord) {
                $polygon_coords .= $coord[0] . ' ' . $coord[1] . ', ';
            }
            $polygon_coords .= $request_coords[0][0] . ' ' . $request_coords[0][1]; // add first coordinate again to close the polygon


            $estates = Estate::whereIn('estate_status_id', [2, 3, 4])->whereRaw("ST_Within(Point(estate_latitude, estate_longitude), PolygonFromText('POLYGON((" . $polygon_coords . "))'))")->limit(150)
                ->get();
        } else {
            $estates = Estate::whereIn('estate_status_id', [2, 3, 4])->limit(150)
                ->get();
        }



        return EstateResource::collection($estates);
    }

//    public function filter(Request $request, $ajax = false)
//    {
//        $locale = $this->changeLang('hy');
//        $estates = AnnouncementSearch::apply($request)->with('place.parent.parent', 'announcement_type', 'currency_price', 'property_type')
//            ->whereHas('statuses', function($query) {
//                $query->where(['status_id' => 4, 'current' => 1]);
//            })
//            ->orderBy($request->order_by,$request->order_dir);
//        $property_types = PropertyType::where('locale', $locale)->get();
//        $announcement_types = AnnouncementType::where('locale', $locale)->get();
//        // dd($estates->total());
//        $states = Place::whereHas('type', function($query) use ($locale){
//            $query->where([['locale', $locale], ['parent_id', 2]]);
//        })->with('children.children')->get();
//        $opt_types = AnnouncementOptionType::where('locale', $locale)->whereIn('parent_id', [13, 52, 109, 106, 22, 1, 4, 46, 103, 73, 94, 130, 133, 136, 97, 139, 142, 145, 148, 106, 100])->with(['options' => function($query) use ($locale){
//            $query->where('locale',$locale);
//        }])->get();
//        $option_types = array();
//        $option_parents = array();
//        foreach ($opt_types as $option_type)
//        {
//            $option_types[$option_type->id] = $option_type;
//            $option_parents[$option_type->parent_id] = $option_type;
//        }
//        $steets = [];
//        if(isset($request->place['community_id'])) {
//            $steets = Place::whereIn('parent_id',$request->place['community_id'])->get();
//        }
//        $currencies = Currency::where('locale', $locale)->get();
//
//        $currency = Setting::where('name', 'price_options_1')->first()->value;
//        $currency_ids = json_decode($currency, true);
//
//        $currency_area = Setting::where('name', 'area_price_options')->first()->value;
//        $currency_area_ids = json_decode($currency_area, true);
//
//        if ($request->coords && !empty($request->coords) ) {
//            $request->merge(['filter' => $request->params]);
//            return $this->filterAnnouncements($request);
//        }
//        if (isset($request->page)) {
//            return response()->json($estates->paginate(8));
//        }
//        if( $ajax ) {
//            return $estates->get();
//        } else {
//            return view('pages.announcement.index')->with([
//                'announcements' => $estates->paginate(8),
//                'property_types' => $property_types,
//                'announcement_types' => $announcement_types,
//                'states' => $states,
//                'option_types' => $option_types,
//                'currencies' => $currencies,
//                'currency_ids' => $currency_ids,
//                'currency_area_ids' => $currency_area_ids,
//                'streets' => $steets
//
//            ]);
//        }
//    }

    public function is_in_polygon($coords, $points_polygon, $vertices_x, $vertices_y, $longitude, $latitude)
    {
        $pointLocation = new PointCheck();
        $point = [$latitude, $longitude];
        $polygon = $coords;

        return $pointLocation->pointInPolygon($point, $polygon);
    }

    function pointStringToCoordinates($pointString)
    {
        $coordinates = explode(" ", $pointString);
        return array($coordinates[0], $coordinates[1]);
    }

}
