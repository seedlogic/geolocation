<?php
require_once 'vendor/autoload.php';

use Seedlogic\Utils\Geolocation\GeolocationRequest;

$location = new GeolocationRequest();
$location->fetchLocationData();

echo $location->getDatum('region_code');

print_r($location->getData());