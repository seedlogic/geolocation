# Geolocation PHP Utility

A simple PHP Geolocation Utility Library that can be used independently, or as a WordPress plugin with shortcodes.

## Installation

Install the latest version with

```bash
composer require seedlogic-php-utils/geolocation
```

## Basic Usage

User location data can be retrieved as an array of all data points, or as a single data point ("datum").

```php

<?php
require_once 'vendor/autoload.php';

use Seedlogic\Utils\Geolocation\GeolocationRequest;

// Create a request
$location = new GeolocationRequest();
$location->fetchLocationData();

// Fetch all location data as an array
var_dump($location->getData());

// Fetch a single location datum
echo $location->getDatum('region_code');
```

Available data points:

- "ip"
- "country_code"
- "country_name"
- "latitude" (or "lat")
- "longitude" (or "lon")
- "region_code"
- "region_name" (or "region")
- "city"
- "zip"

## Wordpress Implementation

This utility has been wrapped into a simple WordPress plugin for use with existing WordPress sites.

Installation:

1. Download the geolocation_plugin.zip folder as part of this package (or, if you've installed this package via Composer already, it will be in the package root folder).
2. Upload geolocation_plugin.php to Wordpress as a manually-added plugin and activate it.

Individual data points are retrievable on all parts of Wordpress through simple shortcodes:

    [geolocation item="ip"]
    [geolocation item="region_code"]
    [geolocation item="latitude"]
