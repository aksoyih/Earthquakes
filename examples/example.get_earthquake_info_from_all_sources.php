<?php

require_once __DIR__.'/../vendor/autoload.php';

$earthquakes = new Aksoyih\Earthquakes\Earthquakes();

$earthquakeData = $earthquakes->getEarthquakesFromAllSources();

echo json_encode($earthquakeData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
