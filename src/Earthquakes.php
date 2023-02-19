<?php

namespace Aksoyih\Earthquakes;

use Exception;

class Earthquakes
{
    private array $sources;
    private array $clients;

    public function __construct()
    {
        $this->setSources();
        $this->clients = $this->initiateClients();
    }

    public function initiateClients(): array
    {
        $clients = [];
        foreach ($this->sources as $key => $value) {
            $clients[$key] = new $value();
        }
        return $clients;
    }

    /**
     * @throws Exception
     */
    public function getClient($source){
        if(!in_array($source, array_keys($this->sources))) {
            throw new Exception('Invalid source');
        }
        return $this->clients[$source];
    }

    private function setSources(): void
    {
        $this->sources = [
            'Kandilli' => 'Aksoyih\Earthquakes\Providers\Kandilli',
            'AFAD' => 'Aksoyih\Earthquakes\Providers\AFAD',
            'USGS' => 'Aksoyih\Earthquakes\Providers\USGS',
        ];
    }

    public function getEarthquakesFromAllSources(): array
    {
        return [
            'kandilli' => $this->clients['Kandilli']->getEarthquakes()->wait(),
            'afad' => $this->clients['AFAD']->getEarthquakes()->wait(),
            'usgs' => $this->clients['USGS']->getEarthquakes()->wait(),
        ];
    }

    public function getEarthquakesFromSource($source): array
    {
        return $this->clients[$source]->getEarthquakes()->wait();
    }

}