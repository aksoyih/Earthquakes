<?php
namespace Aksoyih\Earthquakes\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Kandilli
{
    private Client $client;

    public function __construct()
    {
        $this->setClient();
    }

    public function setClient(): void
    {
        $this->client = new Client();
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getEarthquakes(){

        $request = new Request('GET', 'http://udim.koeri.boun.edu.tr/zeqmap/xmlt/son24saat.xml',[
            'headers' => [
                'Content-type' => 'Content-type: text/html; charset=UTF-8',
                'Connection' => 'keep-alive',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'Accept-Encoding' => 'gzip, deflate',
            ],
            'curl.options' => array(
                CURLOPT_COOKIESESSION => true,
                CURLOPT_COOKIEJAR => 'cookie.txt',
                CURLOPT_FRESH_CONNECT => true,
                CURLOPT_TIMEOUT => 0
            ),
            'params.cache.override_ttl' => 0,
            'params.cache.default_ttl' => 0,
            'params.cache.revalidate'   => 'always'
        ]);
        return $this->client->sendAsync($request)->then(function ($response) {
            $xml = simplexml_load_string($response->getBody());
            $earthquakes = json_decode(json_encode($xml), true)['earhquake'];

            $clean_data = [];
            foreach ($earthquakes as $earthquake){
                $earthquake = $earthquake['@attributes'];
                $timestamp = strtotime(str_replace(".", "/" , $earthquake['name']));

                $clean_data[] = [
                    'timestamp' => $timestamp,
                    'date' => date('Y-m-d', $timestamp),
                    'time' => date('H:i:s', $timestamp),
                    'latitude' => $earthquake['lat'],
                    'longitude' => $earthquake['lng'],
                    'depth' => $earthquake['Depth'],
                    'magnitude' => [
                        'ml' => $earthquake['mag'],
                        'mw' => null,
                        'mb' => null,
                    ],
                    'region' => rtrim($earthquake['lokasyon']),
                    'solution_type' => null,
                    'additional_info' => []
                ];
            }

            return $clean_data;
        });
    }
}