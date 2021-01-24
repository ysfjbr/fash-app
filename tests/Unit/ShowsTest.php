<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\ShowsController;

class ShowsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /* public function test_example()
    {
        $this->assertTrue(true);
    } */

    /**
     * Test the cache key generation function
     */
    public function test_GetCacheKey()
    {
        $actual = ShowsController::getCacheKey('search',"  The   world ");
        $this->assertEquals("SHOW.SEARCH.THE+WORLD",$actual,$actual." : Not match the correct result!");
    }


}
