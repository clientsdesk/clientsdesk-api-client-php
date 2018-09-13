<?php
declare(strict_types=1);

namespace Clientsdesk\API;

use GuzzleHttp\Psr7\Request;
use Clientsdesk\API\Exceptions\ApiResponseException;
use Http\Client\Exception\HttpException;


/**
 * HTTP functions via curl
 * @package Clientsdesk\API
 */
class Http
{
    /**
     * Use the send method to call every endpoint except for oauth/tokens
     *
     * @param HttpClient $http_client
     * @param string $endPoint E.g. "/web_form_request"
     * @param array $options
     *                             Available options are listed below:
     *                             array $queryParams Array of unencoded key-value pairs, e.g. ["ids" => "1,2,3,4"]
     *                             array $postFields Array of unencoded key-value pairs, e.g. ["filename" => "blah.png"]
     *                             string $method "GET", "POST", etc. Default is GET.
     *
     * @return \stdClass | null The response body, parsed from JSON into an object. Also returns null if something went wrong
     * @throws ApiResponseException
     *
     */
    public static function send(
        HttpClient $http_client,
        $endPoint,
        $options = []
    )
    {

        $options = array_merge(
            [
                'method'      => 'GET',
                'contentType' => 'application/json',
                'postFields'  => null,
                'queryParams' => null
            ],
            $options
        );

        $headers = array_merge([
            'Accept'       => 'application/json',
            'Content-Type' => $options['contentType'],
            'User-Agent'   => $http_client->getUserAgent()
        ], $http_client->getHeaders());

        $request = new Request(
            $options['method'],
            $http_client->getApiUrl() . $http_client->getApiBasePath() . $endPoint,
            $headers
        );

        if (! empty($options['postFields'])) {
            $request = $request->withBody(\GuzzleHttp\Psr7\stream_for(json_encode($options['postFields'])));
        }

        if (! empty($options['queryParams'])) {
            foreach ($options['queryParams'] as $queryKey => $queryValue) {
                $uri     = $request->getUri();
                $uri     = $uri->withQueryValue($uri, $queryKey, $queryValue);
                $request = $request->withUri($uri, true);
            }
        }

        try {
            $response = $http_client->guzzle->sendRequest($request);
        } catch (HttpException $e) {
            throw new ApiResponseException($e);
        } finally {
            $http_client->setDebug(
                $request->getHeaders(),
                $request->getBody(),
                isset($response) ? $response->getStatusCode() : null,
                isset($response) ? $response->getHeaders() : null,
                isset($e) ? $e : null
            );
            $request->getBody()->rewind();
        }
        return json_decode($response->getBody()->getContents());
    }
}
