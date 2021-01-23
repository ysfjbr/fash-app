<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ShowsController extends Controller
{
    public static function getDataFromTVMaze($api_url,$params=[])
    {
        $client = new Client();
        $url = 'http://api.tvmaze.com/';
        $api_url = $url . $api_url ;
        $response = $client->request ('GET', $api_url, ['query' => $params]);
        return json_decode ((string)$response->getBody ());
    }

    public function getData($page, $search){
        if ($page){
            $cacheKey = 'SHOW.PAGE.'.$page;
            
            // get the Data from cache if available
            return cache()->remember($cacheKey, Carbon::now()->addHour(10), function() use($page) {

                // if not in the cache get from 3rd party and store it in cache for one hour (for optimizing API calling)
                return self::getDataFromTVMaze('shows',['page'=> $page]);
            });            
        }
        if($search){
            // Search input tirm and to upper case
            $search = strtoupper(trim($search));

            // Replace Spaces with + 
            $search = str_replace(" ","+", $search);

            // for example "&the world# " => "THE+WORLD"
            $cacheKey = 'SHOW.SEARCH.'.$search;

            //return $cacheKey;
            
            return cache()->remember($cacheKey, Carbon::now()->addHour(10), function() use($search) {
                return self::getDataFromTVMaze('search/shows',['q'=> $search]);
            });
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(trim($request->page) != '')
        {
            return $this->getData($request->page, '');
        }
        else if(trim($request->search) != '')
        {
            return $this->getData('', $request->search);
        }
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
