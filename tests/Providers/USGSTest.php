<?php

namespace Providers;

use Aksoyih\Earthquakes\Providers\USGS;
use PHPUnit\Framework\TestCase;

class USGSTest extends TestCase
{

    public function testGetEarthquakes()
    {
        $usgs = new USGS();
        $this->assertIsArray($usgs->getEarthquakes()->wait());
    }

    public function testEarthquakeDataDate()
    {
        $usgs = new USGS();
        $data = $usgs->getEarthquakes()->wait();
        $first_data = $data[0];
        $this->assertIsArray($first_data);

        $this->assertArrayHasKey('timestamp', $first_data);

        // check if timestamp is valid date
        $this->assertIsInt($first_data['timestamp']);
        $this->assertIsString(date('Y-m-d H:i:s', $first_data['timestamp']));

        // check if date is within 50 years
        $this->assertLessThan(time() + 50 * 365 * 24 * 60 * 60, $first_data['timestamp']);
        $this->assertGreaterThan(time() - 50 * 365 * 24 * 60 * 60, $first_data['timestamp']);

        // check if date is in the past
        $this->assertLessThan(time(), $first_data['timestamp']);

    }

}
