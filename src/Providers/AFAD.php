<?php

namespace Aksoyih\Earthquakes\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class AFAD
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

        $request = new Request('GET', 'https://deprem.afad.gov.tr/last-earthquakes.html', [
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
            $result = iconv("UTF-8", "UTF-8", $response->getBody());

            // find table in html
            $dom = new \DOMDocument();
            $dom->loadHTML($result);
            $table = $dom->getElementsByTagName('table')->item(0);
            // find rows in table
            $rows = $table->getElementsByTagName('tr');
            $data = [];
            foreach ($rows as $row) {
                // find cells in row
                $cells = $row->getElementsByTagName('td');
                $row_data = [];
                foreach ($cells as $cell) {
                    // get cell value
                    $row_data[] = $cell->nodeValue;
                }
                $data[] = $row_data;
            }
            // remove header row
            array_shift($data);
            // remove empty rows
            $data = array_filter($data);
            // remove empty cells
            $data = array_map(function ($row) {
                return array_filter($row);
            }, $data);

            $clean_data = [];
            foreach ($data as $earthQuake){
                $date = strtotime($earthQuake[0]);

                $type = $earthQuake[4];
                $ml = $mb = $mw = null;
                if($type == 'ML'){
                    $ml = $earthQuake[5];
                }elseif($type == 'MW'){
                    $mw = $earthQuake[5];
                }else{
                    $mb = $earthQuake[5];
                }

                $clean_data[] = [
                    'timestamp' => $date,
                    'date' => date('Y-m-d', $date),
                    'time' => date('H:i:s', $date),
                    'latitude' => $earthQuake[1],
                    'longitude' => $earthQuake[2],
                    'depth' => $earthQuake[3],
                    'magnitude' => [
                        'ml' => $ml,
                        'mw' => $mw,
                        'mb' => $mb,
                    ],
                    'region' => $earthQuake[6],
                    'solution_type' => null,
                    'additional_info' => [
                        'afad_earthquake_id' => $earthQuake[7]
                    ],
                ];
            }

            return $clean_data;
        });

        return $promise;
    }
}