<?php

namespace Clientsdesk\API;

use \Curl\Curl;


class HttpClient
{
    const VERSION = '0.0.3';

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
        $referrer = "",
        $hostname = "api-clientsdesk.net",
        $subdomain = "",
        $scheme = "https",
        $port = 443,
        $curl_client = null
    )
    {
        $this->apiSignature = $apiSignature;
        $this->apiKey = $apiKey;
        $this->referrer = $referrer;
        $this->hostname = $hostname;
        $this->scheme = $scheme;

        if (is_null($curl_client)) {
            $this->curl_client = new Curl();
        } else {
            $this->curl_client = $curl_client;
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
     * @throws \Exception
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
     * @param       $endpoint
     * @param array $queryParams
     *
     * @return \stdClass | null
     * @throws \Clientsdesk\API\Exceptions\AuthException
     * @throws \Clientsdesk\API\Exceptions\ApiResponseException
     */
    public function get($endpoint, $queryParams = [])
    {

        $response = Http::send(
            $this,
            $endpoint,
            ['queryParams' => $queryParams]
        );

        return $response;
    }


    /**
     * This is a helper method to do a post request.
     *
     * @param       $endpoint
     * @param array $postData
     *
     * @param array $options
     * @return \stdClass | null
     * @throws \Clientsdesk\API\Exceptions\ApiResponseException
     */
    public function post($endpoint, $postData = [], $options = [])
    {

        $uri = $this->getApiUrl() . $http_client->getApiBasePath() . $endPoint,
        $this->curl_client->post('https://httpbin.org/post', array(
            'id' => '1',
            'content' => 'Hello world!',
            'date' => date('Y-m-d H:i:s'),
        ));
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        } else {
            echo 'Data server received via POST:' . "\n";
            var_dump($curl->response->form);
        }

        $extraOptions = array_merge($options, [
            'postFields' => $postData,
            'method' => 'POST'
        ]);
        $response = Http::send(
            $this,
            $endpoint,
            $extraOptions
        );
        return $response;
    }
}