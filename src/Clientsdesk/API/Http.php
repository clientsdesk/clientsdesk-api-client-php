<?php

namespace Clientsdesk\API;

use Clientsdesk\API\Exceptions\ApiResponseException;


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
                'method' => 'GET',
                'contentType' => 'application/json',
                'postFields' => null,
                'queryParams' => null
            ],
            $options
        );

        $url = $http_client->getApiUrl() . $http_client->getApiBasePath() . $endPoint;

        $http_client->curl_client->setDefaultJsonDecoder($assoc = true);
        $http_client->curl_client->setHeader('Content-Type', $options['contentType']);

        if ($options['method'] == 'POST') {
            $http_client->curl_client->post(
                $url,
                $options['postFields']
            );
        } else {
            $http_client->curl_client->get(
                $url,
                $options['queryParams']
            );
        }

        if ($http_client->curl_client->error) {
            echo 'Error: ' . $http_client->curl_client->errorCode . ': ' . $http_client->curl_client->errorMessage . "\n";
            throw new ApiResponseException($http_client->curl_client);
        } else {
            return $http_client->curl_client->getResponse();
        }


    }
}
