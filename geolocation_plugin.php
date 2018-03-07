<?php
/**
 * Plugin Name: SeedLogic Geolocation WP Plugin
 * Plugin URI: http://seedlogic.com/
 * Description: Fetches a user's relative location based on their IP address through 1 of 3 API services
 * Version: 1.0
 * Author: bmworf
 * Author URI: http://seedlogic.com/
 * License: 
 * 
 * @version 1.0
 **/

// exit if accessed directly
if(!defined('ABSPATH')) 
{
	exit;
}

// Composer link-in
require_once 'vendor/autoload.php';

use Seedlogic\Utils\Geolocation\GeolocationRequest;

// Path/URL to root of this plugin, with trailing slash.
if (!defined('GEOLOC_PATH')) 
{
	define('GEOLOC_PATH', plugin_dir_path(__FILE__));
}
if (!defined('GEOLOC_URL')) 
{
	define('GEOLOC_URL', plugin_dir_url(__FILE__));
}
if(!defined('GEOLOC_BASENAME'))
{
	define('GEOLOC_BASENAME', plugin_basename(__FILE__));
}


function get_location() 
{
	$location = new GeolocationRequest();
	$location->fetchLocationData();

	return $location->getData();
}

function get_location_item($item) 
{
	$value = $item['default'];
	
	$location = get_location();

	if(isset($location[$item['item']]))
	{
		$value = $location[$item['item']];
	}

	return $value;
}

add_shortcode('geolocation', 'get_location_item');