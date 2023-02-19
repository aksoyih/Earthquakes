<?php

namespace Aksoyih\Earthquakes\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class USGS
{
    // send async request to USGS API and return promise
    public function getEarthquakes(){
        $client = new Client();
        $minLatitudeOfTurkey = 35;
        $maxLatitudeOfTurkey = 42;
        $minLongitudeOfTurkey = 25;
        $maxLongitudeOfTurkey = 45;

        $request = new Request('GET', "https://earthquake.usgs.gov/fdsnws/event/1/query?format=geojson&minlatitude=$minLatitudeOfTurkey&maxlatitude=$maxLatitudeOfTurkey&minlongitude=$minLongitudeOfTurkey&maxlongitude=$maxLongitudeOfTurkey&orderby=time");
        return $client->sendAsync($request)->then(function ($response) {
            $result = $response->getBody();
            // body contains turkish characters, so we need to convert it to utf-8
            $result = iconv("ISO-8859-9", "UTF-8", $result);

            $data = json_decode($result, true);
            $clean_data = [];
            foreach ($data['features'] as $key => $value) {
                $dateTime = $this->getDateTime(intval($value['properties']['time']));

                $type = $value['properties']['magType'];
                $ml = $mb = $mw = null;
                if($type == 'ml'){
                    $ml = $value['properties']['mag'];
                }elseif($type == 'mw'){
                    $mw = $value['properties']['mag'];
                }else{
                    $mb = $value['properties']['mag'];
                }

                $clean_data[] = [
                    'timestamp' => $dateTime->getTimestamp(),
                    'date' => $this->getDateFromDateTime($dateTime),
                    'time' => $this->getTimeFromDateTime($dateTime),
                    'latitude' => $value['geometry']['coordinates'][1],
                    'longitude' => $value['geometry']['coordinates'][0],
                    'depth' => $value['geometry']['coordinates'][2],
                    'magnitude' => [
                        'ml' => $ml,
                        'mw' => $mw,
                        'mb' => $mb,
                    ],
                    'region' => $value['properties']['place'],
                    'solution_type' => $value['properties']['status'],
                    'additional_info' => [
                        'url' => $value['properties']['url'],
                        'detail' => $value['properties']['detail'],
                        'alert' => $value['properties']['alert'],
                    ]
                ];
            }
            return $clean_data;
        });
    }

    private function getDateTime($timestamp){
        $date = new \DateTime();
        $date->setTimestamp(intval($timestamp / 1000)); // divide by 1000 to convert milliseconds to seconds
        return $date;
    }

    private function getDateFromDateTime($date){
        return $date->format('Y-m-d');
    }

    private function getTimeFromDateTime($date){
        return $date->format('H:i:s');
    }
}