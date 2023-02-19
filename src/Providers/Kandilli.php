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

        $request = new Request('GET', 'http://www.koeri.boun.edu.tr/scripts/lst7.asp', [
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

        $promise = $this->client->sendAsync($request)->then(function ($response) {
            $result = iconv("ISO-8859-9", "UTF-8", $response->getBody());
            $data = explode('<pre>', strip_tags($result));
            $data = explode("\n", $data[0]);
            array_filter(array_map('trim', $data));

            $key = array_search("---------- --------  --------  -------   ----------    ------------    --------------                                  --------------\r", $data);
            $data = array_slice($data, $key+1);

            $key = array_search("\r", $data);
            $data = array_slice($data, 0, $key);

            $clean_data = [];
            foreach ($data as $key => $value) {
                $pre_clean_data =  array_values(array_filter(explode(' ', $value), function($value) { return $value !== ''; }));

                if(str_contains($pre_clean_data[9], '(')) {
                    $pre_clean_data[8] = $pre_clean_data[8] . ' ' . $pre_clean_data[9];
                    $pre_clean_data[9] = $pre_clean_data[10];
                }

                $pre_clean_data[9] = str_replace("\r", "", $pre_clean_data[9]);

                $ml = $pre_clean_data[5] == "-.-" ? null : $pre_clean_data[5];
                $mb = $pre_clean_data[7] == "-.-" ? null : $pre_clean_data[7];
                $mw = $pre_clean_data[6] == "-.-" ? null : $pre_clean_data[6];

                // get general area of earthquake
                $clean_data[] = [
                    'timestamp' => strtotime($pre_clean_data[0] . ' ' . $pre_clean_data[1]),
                    'date' => $pre_clean_data[0],
                    'time' => $pre_clean_data[1],
                    'latitude' => $pre_clean_data[2],
                    'longitude' => $pre_clean_data[3],
                    'depth' => $pre_clean_data[4],
                    'magnitude' => [
                        'ml' => $ml,
                        'mw' => $mw,
                        'mb' => $mb,
                    ],
                    'region' => $pre_clean_data[8],
                    'solution_type' => $pre_clean_data[9],
                    'additional_info' => []
                ];
            }

            return $clean_data;
        });

        return $promise;
    }
}