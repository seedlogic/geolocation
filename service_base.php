<?php
require_once 'vendor/autoload.php';

$client = new GuzzleHttp\Client();

$response = $client->request('GET', 'http://ip-api.com/json');
$primary = $response->getBody();
if(empty($primary))
{
    $response = $client->request('GET', 'http://www.geoplugin.net/json.gp');
    $secondary = $response->getBody();

    if(empty($secondary))
    {
        $response = $client->request('GET', 'http://freegeoip.net/json/');
        $tertiary = $response->getBody();

        if(empty($tertiary))
        {
            echo "FUCK";
        }
        else
        {
            $tertiary = json_decode($tertiary, true);
            echo $tertiary['region_code'];
        }
    }
    else
    {
        $secondary = json_decode($secondary, true);
        echo $secondary['regionCode'];
    }
}
else
{
    $primary = json_decode($primary, true);

    echo $primary['region'];
}