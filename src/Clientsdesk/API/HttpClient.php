<?php

namespace Clientsdesk\API;

use Clientsdesk\API\Resources\Core\Messages;
use Clientsdesk\API\Resources\Core\WebForms;
use Clientsdesk\API\Traits\Utility\InstantiatorTrait;
use Curl\Curl;


class HttpClient
{
    const VERSION = '0.0.8';

    use InstantiatorTrait;

    /**
     * @var string
     */
    protected $referrer;

    /**
     * @var string
     */
    protected $apiKey;
    /**
     * @var string
     */
    protected $apiSignature;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var string
     */
    protected $apiBasePath;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var string
     */
    protected $subdomain;

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var \Curl\Curl
     */
    public $curl_client;

    public function __construct(
        $apiKey,
        $apiSignature,
        $hostname = "",
        $referrer = "",
        $subdomain = "",
        $scheme = "https",
        $apiBasePath = "api/v1",
        $port = 443,
        $curl_client = null
    )
    {
        $this->apiSignature = $apiSignature;
        $this->apiKey = $apiKey;
        $this->referrer = $referrer;
        $this->hostname = $hostname;
        $this->scheme = $scheme;
        $this->apiBasePath = $apiBasePath;


        if (is_null($curl_client)) {
            $this->curl_client = new Curl();
        } else {
            $this->curl_client = $curl_client;
        }

        if (empty($hostname)) {
            $this->hostname = "api-clientsdesk.net";
        } else {
            $this->hostname = $hostname;
        }

        if (empty($subdomain)) {
            $this->apiUrl = "$scheme://$hostname:$port/";
        } else {
            $this->apiUrl = "$scheme://$subdomain.$hostname:$port/";
        }

        $this->setAuth();
        $this->curl_client->setUserAgent($this->getUserAgent());
        $this->curl_client->setReferrer($this->referrer);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getValidSubResources()
    {
        return [
            'messages' => Messages::class,
            'web_forms' => WebForms::class
        ];
    }

    /**
     * Returns the generated api URL
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Sets the api base path
     *
     * @param string $apiBasePath
     */
    public function setApiBasePath($apiBasePath)
    {
        $this->apiBasePath = $apiBasePath;
    }

    /**
     * Returns the api base path
     *
     * @return string
     */
    public function getApiBasePath()
    {
        return $this->apiBasePath;
    }

    /**
     * Return the user agent string
     *
     * @return string
     */
    public function getUserAgent()
    {
        return 'ClientsdeskAPI PHP ' . self::VERSION;
    }

    /**
     * Set Auth headers
     *
     */
    protected function setAuth()
    {
        $token = bin2hex(random_bytes(24));
        $timestamp = time();
        $time_digest = join([$timestamp, $token]);
        $signature = hash_hmac('sha256', $time_digest, $this->apiSignature, false);
        $header = sprintf('CD1-HMAC-SHA256 Token=%s Signature=%s Timestamp=%u Key=%s', $token, $signature, $timestamp, $this->apiKey);
        $this->curl_client->setHeader('Authorization', $header);
    }

    /**
     * This is a helper method to do a get request.
     *
     * @param       $endPoint
     * @param array $queryParams
     *
     * @return \stdClass | null
     * @throws \Clientsdesk\API\Exceptions\ApiResponseException
     */
    public function get($endPoint, $queryParams = [])
    {

        $response = Http::send(
            $this,
            $endPoint,
            ['queryParams' => $queryParams]
        );

        return $response;
    }


    /**
     * This is a helper method to do a post request.
     *
     * @param       $endPoint
     * @param array $postData
     *
     * @param array $options
     * @return \stdClass | null
     * @throws \Clientsdesk\API\Exceptions\ApiResponseException
     */
    public function post($endPoint, $postData = [], $options = [])
    {
        $extraOptions = array_merge($options, [
            'postFields' => $postData,
            'method' => 'POST'
        ]);
        $response = Http::send(
            $this,
            $endPoint,
            $extraOptions
        );

        return $response;
    }
}