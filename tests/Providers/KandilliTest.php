<?php

namespace Providers;

use Aksoyih\Earthquakes\Providers\Kandilli;
use PHPUnit\Framework\TestCase;

class KandilliTest extends TestCase
{

    public function testGetClient()
    {
        $kandilli = new Kandilli();
        $this->assertInstanceOf('GuzzleHttp\Client', $kandilli->getClient());
    }

    public function testGetEarthquakes()
    {
        $kandilli = new Kandilli();
        $this->assertIsArray($kandilli->getEarthquakes()->wait());
    }

    public function test__construct()
    {
        $kandilli = new Kandilli();
        $this->assertInstanceOf('GuzzleHttp\Client', $kandilli->getClient());
    }
}
