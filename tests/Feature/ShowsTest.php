<?php

namespace Tests\Feature;

use App\Http\Controllers\ShowsController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class ShowsTest extends TestCase
{
    /**
     * Check if end-point /api/shows is working.
     *
     * @return void
     */
    public function test_ShowsGetApiIsOnline()
    {
        /**
         * check if Get Api is working and return code 200
         */
        $response = $this->get('/api/shows');

        $response->assertStatus(200);
    }

    public function test_ShowsGetOneItemApiIsOnline()
    {
        /**
         * check if Get Api is working and return code 200
         */
        $response = $this->get('/api/shows/1');

        $response->assertStatus(200);
    }

    public function test_OtherApiIsOffline()
    {
        /**
         * check if Get Api is not found and return code 404
         */
        $response = $this->get('/api/other');

        $response->assertStatus(404);
    }

    public function test_ApiDelayRequesting()
    {
        /**
         * 4 request must exeuted in at least 2 seconds
         */

        $ReqCount = 4;
        $MinTime = $ReqCount / 2;

        $StartTime = microtime( true );

        for($i = 0 ; $i < $ReqCount ; $i++)
            ShowsController::getDataFromTVMaze('FAKE');


        $DiffTime = microtime( true ) - $StartTime;
        $this->assertLessThan( $DiffTime , $MinTime , 'There are no delay between Requests!' );
    }

    /**
     * Print the list of request from TVMaze in last 24 Hour
     */
    public function test_GetRequestsListFromCache()
    {
        print_r(ShowsController::getApiRequestCache());
        $this->assertTrue(true);
    }

}
