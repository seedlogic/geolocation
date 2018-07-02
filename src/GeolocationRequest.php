<?php
namespace Seedlogic\Utils\Geolocation;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class GeolocationRequest
{
    /**
     * The Guzzle HTTP Client instance
     *
     * @access public
     * @var GuzzleHttp\Client
     **/
    public $client;

    /**
     * The Guzzle HTTP client Request object
     *
     * @access public
     * @var GuzzleHttp\Psr7\Request;
     **/
    public $request;

    /**
     * The response object instance.
     *
     * @access public
     * @var mixed
     **/
    public $response;

    /**
     * The response body as a JSON string.
     *
     * @access private
     * @var GuzzleHttp\Psr7\Response
     */
    private $data;

    /**
     * An indicator for switching source response schemas.
     *
     * @access private
     * @var string
     */
    private $source;

    /**
     * The IP Stack API access key
     * @access private
     * @var string
     **/
    private $access_key = '66811f9619d7fc47a8af86f87e833c56';

    /**
     * @Brief: Object constructor
     *
     * @access public
     * @return GeolocationRequest
     **/
    public function __construct()
    {
        $this->client = new Client();

        return $this;
    }

    /**
     * @Brief: Fetches the aggregate location data for a given IP.
     *
     * @return void
     **/
    public function fetchLocationData()
    {
        $user_ip = $_SERVER['REMOTE_ADDR'];

        if($this->makeRequest('http://api.ipstack.com/' . $user_ip . '?access_key=' . $this->access_key . '&output=json'))
        {
            $this->source = 'primary';
            $this->data = json_decode($this->response->getBody(), true);
        }
        elseif($this->makeRequest('http://ip-api.com/json/' . $user_ip))
        {
            $this->source = 'secondary';
            $this->data = json_decode($this->response->getBody(), true);
        }
        elseif($this->makeRequest('http://www.geoplugin.net/json.gp?ip=' . $user_ip))
        {
            $this->source = 'tertiary';
            $this->data = json_decode($this->response->getBody(), true);
        }
        else
        {
            throw new Exception('Currently unable to load location data');
        }

        return;
    }

    /**
     * @Brief: Makes a request at the specified endpoint.
     *
     * Defaults to FreeGeoIP.net if none specified.
     *
     * @access public
     * @param string $request_uri The endpoint to initiate an HTTP request to.
     * 
     * @return bool
     **/
    public function makeRequest(string $request_uri = null)
    {
        try
        {
            $this->request = new Request('GET', $request_uri);
            $this->response = $this->client->send($this->request);
    
            return $this->response->getStatusCode() === 200;
        }
        catch(\GuzzleHttp\Exception\ClientException $e)
        {
            return false;
        }
    }

    /**
     * @Brief: Returns a single location datum.
     *
     * @access public
     * @param string $datum The datum (data point) to retrieve.
     * 
     * @return string
     * @throws Exception
     **/
    public function getDatum(string $datum)
    {
        switch($this->source)
        {
            case "primary":
                switch($datum)
                {
                    case "ip": return $this->data['ip']; break;
                    case "country_code": return $this->data['country_code']; break; 
                    case "country_name": return $this->data['country_name']; break; 
                    case "lat":
                    case "latitude": return $this->data['latitude']; break; 
                    case "lon":
                    case "longitude": return $this->data['longitude']; break; 
                    case "region_code": return $this->data['region_code']; break; 
                    case "region":
                    case "region_name": return $this->data['region_name']; break;
                    case "city": return $this->data['city']; break;
                    case "zip": return $this->data['zip_code']; break;
                    default: throw new \Exception("No data point specified to return");
                }
                break;
            
            case "secondary":
                switch($datum)
                {
                    case "ip": return $this->data['query']; break;
                    case "country_code": return $this->data['countryCode']; break; 
                    case "country_name": return $this->data['country']; break; 
                    case "lat":
                    case "latitude": return $this->data['lat']; break; 
                    case "lon":
                    case "longitude": return $this->data['lon']; break; 
                    case "region_code": return $this->data['region']; break; 
                    case "region":
                    case "region_name": return $this->data['region_name']; break;
                    case "city": return $this->data['city']; break;
                    case "zip": return $this->data['zip']; break;
                    default: throw new \Exception("No data point specified to return");
                }
                break;
                
            case "tertiary":
                switch($datum)
                {
                    case "ip": return $this->data['geoplugin_request']; break;
                    case "country_code": return $this->data['geoplugin_countryCode']; break; 
                    case "country_name": return $this->data['geoplugin_countryName']; break; 
                    case "lat":
                    case "latitude": return $this->data['geoplugin_latitude']; break; 
                    case "lon":
                    case "longitude": return $this->data['geoplugin_longitude']; break; 
                    case "region_code": return $this->data['geoplugin_regionCode']; break; 
                    case "region":
                    case "region_name": return $this->data['geoplugin_regionName']; break; 
                    case "city": return $this->data['geoplugin_city']; break;
                    case "zip": return $this->data['geoplugin_zip']; break;
                    default: throw new \Exception("No data point specified to return");
                }
                break;
        }
    }

    /**
     * @Brief: Returns the response data as an array.
     *
     * @access public
     * @return array
     * @throws conditon
     **/
    public function getData()
    {
        return empty($this->data)
            ? json_decode($this->response->getBody(), true)
            : $this->data;
    }
}