Eearthquakes
=============
[![Latest Stable Version](http://poser.pugx.org/aksoyih/earthquakes/v)](https://packagist.org/packages/aksoyih/earthquakes) [![Total Downloads](http://poser.pugx.org/aksoyih/earthquakes/downloads)](https://packagist.org/packages/aksoyih/earthquakes) [![Latest Unstable Version](http://poser.pugx.org/aksoyih/earthquakes/v/unstable)](https://packagist.org/packages/aksoyih/earthquakes) [![License](http://poser.pugx.org/aksoyih/earthquakes/license)](https://packagist.org/packages/aksoyih/earthquakes) [![PHP Version Require](http://poser.pugx.org/aksoyih/earthquakes/require/php)](https://packagist.org/packages/aksoyih/earthquakes)

Features
------------
This package makes the latest earthquake data available in an uniform format from Kandilli, AFAD and USGS sources.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require aksoyih/earthquakes
```

or add

```
"{package}": "aksoyih/earthquakes"
```

to the require section of your `composer.json` file.
## Usage/Examples

Firstly, require the autoloader from Composer
```php
<?php
require_once "vendor/autoload.php";

```

Use `Aksoyih\Earthquakes\Earthquakes()` to create and initialize a Earthquakes instance.
```php
$earthquakes = new Aksoyih\Earthquakes\Earthquakes();

```

This package can be used to either fetch the latest earthquake data from above mentioned sources, or fetch them by a given source.
To fetch earthquakes data from ***all*** sources, you can use the the following example.
```php
$earthquakeData = $earthquakes->getEarthquakesFromAllSources();
```

To fetch earthquakes from **one** source, you can use the following example.
```php
$earthquakeData = $earthquakes->getEarthquakesFromSource('AFAD');
```

## Example Data

```json
[
    {
    "timestamp": 1676822306,
    "date": "2023-02-19",
    "time": "15:58:26",
    "latitude": "38.173",
    "longitude": "38.104",
    "depth": "7.0",
    "magnitude": {
      "ml": "2.0",
      "mw": null,
      "mb": null
    },
    "region": "Yeşilyurt (Malatya)",
    "solution_type": null,
    "additional_info": {
      "afad_earthquake_id": "550252"
    }
  },
  {
    "timestamp": 1676822040,
    "date": "2023-02-19",
    "time": "15:54:00",
    "latitude": "38.503",
    "longitude": "40.268",
    "depth": "7.0",
    "magnitude": {
      "ml": "1.5",
      "mw": null,
      "mb": null
    },
    "region": "Genç (Bingöl)",
    "solution_type": null,
    "additional_info": {
      "afad_earthquake_id": "550253"
    }
  }
]
```

## TODO List
- [ ]  Add sorting options
- [x]  Add tests

## Contributing

Contributions are always welcome!


[![License](http://poser.pugx.org/aksoyih/earthquakes/license)](https://packagist.org/packages/aksoyih/earthquakes)
