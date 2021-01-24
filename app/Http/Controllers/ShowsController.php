<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
//use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ShowsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(trim($request->page) != '') // if request contain a page number
        {
            return $this->getData($request->page, '');
        }
        else if(trim($request->search) != '') // if request contain a search text
        {
            return $this->getData('', $request->search);
        }
    }

    /**
     * Get data from cache or request them from 3rd party if necessary
     */
    public function getData($page, $search){

        if ($page != ""){

            $shows_per_page = config('app.shows_per_page'); // the ammount of items per one page

            // As TVMaze Docs every page (per request) contains 250 item, $TVMazePageNumber is the page number from TVMaze
            $TVMazePageNumber = floor((($page * $shows_per_page + $shows_per_page) / 250));

            //return $TVMazePageNumber;

            // array of sets of data
            $PagesData = [];
            for($i = 0 ; $i <= $TVMazePageNumber ; $i++){

                $cacheKey = self::getCacheKey('page',$i);

                // get the Data from cache if available
                array_push($PagesData, cache()->remember($cacheKey, Carbon::now()->addHour(24), function() use($i) {

                    // if not in the cache get from 3rd party and store it in cache for one hour (for optimizing API calling)
                    return self::getDataFromTVMaze('shows',['page'=> $i]);
                }));
            }

            // merge all data
            $allData = array_merge(...$PagesData);

            // return a set of items according to page number
            return array_slice($allData, $page * $shows_per_page, $shows_per_page);
        }
        if($search != ""){

            $cacheKey = self::getCacheKey('search',$search);
            return cache()->remember($cacheKey, Carbon::now()->addHour(24), function() use($search) {
                return self::getDataFromTVMaze('search/shows',['q'=> $search]);
            });
        }
    }

    public static function getCacheKey($type, $key){
        // Key and Type input tirm and to upper case
        $key = strtoupper(trim($key));
        $type = strtoupper(trim($type));

        //Multiple spaces to one space
        $key = preg_replace('/\s+/', ' ', $key);

        // Replace Spaces with (+)
        $key = str_replace(" ","+", $key);

        // for example " The   World " => "THE+WORLD"
        return 'SHOW.'.$type.'.'.$key;
    }

    public static function getDataFromTVMaze($api_url,$params=[], $isReturnArray = true)
    {
        // Increment the count of the request Jobs
        Cache::increment('REQAPICOUNT');

        // How many jobs now!
        $ReqQueue = Cache::get('REQAPICOUNT');

        // wait according to count of jobs  (if one job sleep 0.5 sec, 2 jobs sleep 1 sec ...)

        usleep($ReqQueue * 500000);


        // for testing only!
        if($api_url == "FAKE") {
            Cache::decrement('REQAPICOUNT');
            return Cache::get('REQAPICOUNT');
        }

        /**
         * Fetching from 3rd Party
         */
        $client = new Client();
        $url = 'http://api.tvmaze.com/';
        $api_url = $url . $api_url ;

        // Register this request for monitoring (URl & Time)
        $ReqsInCache =  (Cache::has('APIREQUESTS')) ? Cache::get('APIREQUESTS') : [];

        $mt = microtime(true);
        $millsec = round( $mt * 1000 ) - (floor($mt) * 1000) ;
        array_push($ReqsInCache, 'URL : '. $api_url.' | Params: '.implode(',',$params).' | Time : '.now()." > ".$millsec); //
        Cache::put('APIREQUESTS', $ReqsInCache, Carbon::now()->addDay(1));

        // Start Requesting
        $response = $client->request ('GET', $api_url, ['query' => $params]);

        // After Requesting jobs will be decreased by 1
        Cache::decrement('REQAPICOUNT');

        if($isReturnArray) return json_decode((string)$response->getBody());

        return response()->json(json_decode((string)$response->getBody()));

    }

    //Get array of Last requests to 3rd party in last 24 hours
    public static function getApiRequestCache()
    {
        return (Cache::has('APIREQUESTS')) ? Cache::get('APIREQUESTS') : [];
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Return the specified Show by its ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cacheKey = 'SHOW.SHOW.'.$id;
        //return $cacheKey;

        return cache()->remember($cacheKey, Carbon::now()->addMinute(5), function() use($id) {
            return self::getDataFromTVMaze('shows/'.$id, [] , false);
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
