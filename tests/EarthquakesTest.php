<?php


use Aksoyih\Earthquakes\Earthquakes;
use PHPUnit\Framework\TestCase;

class EarthquakesTest extends TestCase
{
    public function testGetEarthquakesFromAllSources()
    {
        $earthquakes = new Earthquakes();
        $data = $earthquakes->getEarthquakesFromAllSources();
        $this->assertIsArray($data);
        $this->assertArrayHasKey('kandilli', $data);
        $this->assertArrayHasKey('afad', $data);
        $this->assertArrayHasKey('usgs', $data);
    }

    public function testGetEarthquakesFromSource()
    {
        $earthquakes = new Earthquakes();
        $data = $earthquakes->getEarthquakesFromSource('Kandilli');
        $this->assertIsArray($data);
    }

    public function testGetClient()
    {
        $earthquakes = new Earthquakes();
        $client = $earthquakes->getClient('Kandilli');
        $this->assertInstanceOf('Aksoyih\Earthquakes\Providers\Kandilli', $client);

        $client = $earthquakes->getClient('AFAD');
        $this->assertInstanceOf('Aksoyih\Earthquakes\Providers\AFAD', $client);

        $client = $earthquakes->getClient('USGS');
        $this->assertInstanceOf('Aksoyih\Earthquakes\Providers\USGS', $client);

        $this->expectException('Exception');
        $client = $earthquakes->getClient('InvalidSource');
    }
}