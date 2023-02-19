<?php

namespace Aksoyih\Earthquakes;

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

    public function getClient($source){
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

    public function getEarthquakes(): array
    {
        return [
            'kandilli' => $this->clients['Kandilli']->getEarthquakes()->wait(),
            'afad' => $this->clients['AFAD']->getEarthquakes()->wait(),
            'usgs' => $this->clients['USGS']->getEarthquakes()->wait(),
        ];
    }
}