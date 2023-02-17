<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\EstateCollection;
use App\Models\Estate;
use DebugBar\DebugBar;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\PointCheck;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EstatesController extends Controller
{


    public function filterEstates(Request $request)
    {

        $pageSize = $request->input('page_size') ? $request->input('page_size') : 12;


        return new EstateCollection(QueryBuilder::for(Estate::class)
            ->allowedFilters([
                AllowedFilter::exact('prices'),
                AllowedFilter::scope('price_from'),
                AllowedFilter::scope('price_to'),
                AllowedFilter::scope('text_search'),
                AllowedFilter::exact('currency'),
                AllowedFilter::exact('room_count'),
                AllowedFilter::exact('estate_type_id'),
                AllowedFilter::exact('location_province_id'),
                AllowedFilter::exact('location_city_id'),
                AllowedFilter::exact('location_community_id'),
            ])
            ->allowedSorts(['created_on', 'visits_count', 'room_count'])
            ->paginate($pageSize)
            ->appends(request()->query()));


    }

    public function filterAnnouncements(Request $request)
    {
        $request_coords = $request->coords;
        $filter = [];


        parse_str($request->filter, $filter);

        // return $filter;
        if (isset($filter['place'])) {
            unset($filter['place']);
        }

//        $locale = $request->locale;
        $filter['order_by'] = 'created_at';
        $filter['order_dir'] = 'DESC';
//        $data = new Request($filter);
//        $ajax = true;
//        $estates = $this->filter($data, $ajax);

        $estates = Estate::where('estate_latitude', '>', 34)->where('estate_longitude', '>', 34)->limit('1')->get();


        foreach ($estates as $key => $estate) {
            if (isset($estate['estate_latitude']) && isset($estate['estate_longitude'])) {
//                $response = Http::get('https://geocode-maps.yandex.ru/1.x/?apikey=98976ac2-1627-4fc8-ac83-e4d35764b12c&format=json&results=1&geocode='.$estate->full_address);

//                $estate_coords = json_decode($response->body())->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;


                $estate_coords = $estate['estate_longitude'] . ' ' . $estate['estate_latitude'];

                Log::info('Estate id -' . $estate->id);
                Log::info('Pos -' . $estate_coords);


                Log::info('Coords -' . $estate_coords);


//                usleep(100);
//                dd($ann->full_address, $responseData, json_decode($response->body()));
//                $ann_coords = [$ann['estate_longitude'], $ann['estate_latitude']];

                $ann_coords = $this->pointStringToCoordinates($estate_coords);
                $vertices_x = [];
                $vertices_y = [];


                foreach ($request_coords as $coord) {
                    $vertices_x[] = $coord[0];
                    $vertices_y[] = $coord[1];
                };

                $points_polygon = count($vertices_x) - 1;
                $longitude = $ann_coords[0];
                $latitude = $ann_coords[1];

                $estate->estate_latitude = $latitude;
                $estate->estate_longitude = $longitude;
                $estate->save();

                Log::info('long - ' . $longitude);
                Log::info('latitude - ' . $latitude);

                if ($this->is_in_polygon($request_coords, $points_polygon, $vertices_x, $vertices_y, $longitude, $latitude)) {
                    $estate->native_coords = $ann_coords;
                } else {
                    unset($estates[$key]);
                }
            } else {
                unset($estates[$key]);
            }
        }


        // return response()->json($outer_html);
        return response()->json(['data' => $estates, 'current_page' => 2, 'last_page' => 2]);

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
