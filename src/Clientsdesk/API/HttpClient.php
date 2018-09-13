<?php

namespace Clientsdesk\API;

use Clientsdesk\API\Resources\Core\Messages;
use Clientsdesk\API\Resources\Core\WebForms;
use Clientsdesk\API\Traits\Utility\InstantiatorTrait;
use Clientsdesk\API\Authentication\Signature;
use Http\Discovery\HttpClientDiscovery;
use Http\Client\Common\PluginClient;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\RetryPlugin;

/**
 * Client class, base level access
 *
 * @method Messages messages($id = null)
 *
 */
class HttpClient
{
    const VERSION = '0.0.1';

    use InstantiatorTrait;

    /**
     * @var array $headers
     */
    private $headers = [];

    /**
     * @var string
     */
    protected $apiKey;
    /**
     * @var string
     */
    protected $subdomain;
    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var string This is appended between the full base domain and the resource endpoint
     */
    protected $apiBasePath;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var \Http\Client\Common\PluginClient
     */
    public $guzzle;

    /**
     * @param string $apiKey
     * @param string $hostname
     * @param string $subdomain
     * @param string $scheme
     * @param int $port
     */

    public function __construct(
        $apiKey,
        $apiSignature,
        $hostname = "",
        $subdomain = "",
        $scheme = "https",
        $port = 443,
        $guzzle = null
    )
    {
        $this->apiSignature = $apiSignature;
        $this->apiKey = $apiKey;

        // Create an HTTP Client
        $authentication = new Signature($this->apiSignature, $this->apiKey);

        if (is_null($guzzle)) {
            $this->guzzle = new PluginClient(
                HttpClientDiscovery::find(),
                [
                    new AuthenticationPlugin($authentication),
                    new RetryPlugin(['retries' => 1]),
                    new ErrorPlugin()
                ]
            );
        } else {
            $this->guzzle = $guzzle;
        }

        $this->subdomain = $subdomain;
        if (empty($hostname)) {
            $this->hostname = "api-clientsdesk.net";
        } else {
            $this->hostname = $hostname;
        }
        $this->scheme = $scheme;
        $this->port = $port;
        if (empty($subdomain)) {
            $this->apiUrl = "$scheme://$hostname:$port/";
        } else {
            $this->apiUrl = "$scheme://$subdomain.$hostname:$port/";
        }
        $this->apiBasePath = 'api/v1';

        $this->debug = new Debug();
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
     * Returns the generated api key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
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
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $key The name of the header to set
     * @param string $value The value to set in the header
     * @return HttpClient
     * @internal param array $headers
     *
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
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
     * Set debug information as an object
     *
     * @param mixed $lastRequestHeaders
     * @param mixed $lastRequestBody
     * @param mixed $lastResponseCode
     * @param string $lastResponseHeaders
     * @param mixed $lastResponseError
     */
    public function setDebug(
        $lastRequestHeaders,
        $lastRequestBody,
        $lastResponseCode,
        $lastResponseHeaders,
        $lastResponseError
    )
    {
        $this->debug->lastRequestHeaders = $lastRequestHeaders;
        $this->debug->lastRequestBody = $lastRequestBody;
        $this->debug->lastResponseCode = $lastResponseCode;
        $this->debug->lastResponseHeaders = $lastResponseHeaders;
        $this->debug->lastResponseError = $lastResponseError;
    }

    /**
     * Returns debug information in an object
     *
     * @return Debug
     */
    public function getDebug()
    {
        return $this->debug;
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
     * @param       $type
     * @param array $attributes
     *
     * @param array $options
     * @return \stdClass | null
     * @throws \Clientsdesk\API\Exceptions\ApiResponseException
     */
    public function post($endpoint, $postData = [], $options = [])
    {
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