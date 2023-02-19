<?php

namespace Providers;

use Aksoyih\Earthquakes\Providers\AFAD;
use PHPUnit\Framework\TestCase;

class AFADTest extends TestCase
{

    public function testGetClient()
    {
        $afad = new AFAD();
        $this->assertInstanceOf('GuzzleHttp\Client', $afad->getClient());
    }

    public function test__construct()
    {
        $afad = new AFAD();
        $this->assertInstanceOf('GuzzleHttp\Client', $afad->getClient());
    }

    public function testGetEarthquakes()
    {
        $afad = new AFAD();
        $this->assertIsArray($afad->getEarthquakes()->wait());
    }
}
