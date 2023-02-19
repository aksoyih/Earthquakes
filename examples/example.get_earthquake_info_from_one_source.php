<?php

require_once __DIR__.'/../vendor/autoload.php';

$earthquakes = new Aksoyih\Earthquakes\Earthquakes();

// valid sources: Kandilli, AFAD, USGS
$earthquakeData = $earthquakes->getEarthquakesFromSource('Kandilli');
//$earthquakeData = $earthquakes->getEarthquakesFromSource('AFAD');
//$earthquakeData = $earthquakes->getEarthquakesFromSource('USGS');

echo json_encode($earthquakeData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);