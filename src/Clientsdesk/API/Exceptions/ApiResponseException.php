<?php

namespace Clientsdesk\API\Exceptions;

use Http\Client\Exception\HttpException;
use Http\Client\Common\Exception\ClientErrorException;
use Http\Client\Common\Exception\ServerErrorException;

/**
 * Class ApiResponseException
 *
 * @package Clientsdesk\API\Exceptions
 */
class ApiResponseException extends \Exception
{
    /**
     * @var array
     */
    protected $errorDetails = [];
    public function __construct(HttpException $e)
    {
        $message = $e->getMessage();
        if ($e instanceof ClientErrorException) {
            $response           = $e->getResponse();
            $responseBody       = $response->getBody()->getContents();
            $this->errorDetails = json_decode($responseBody, true);
            $message .= ' [details] ' . $responseBody;
        } elseif ($e instanceof ServerErrorException) {
            $message .= ' [details] Clientsdesk may be experiencing internal issues or undergoing scheduled maintenance.';
        } elseif (! $e->getResponse()) {
            $request = $e->getRequest();
            // Unsuccessful response, log what we can
            $message .= ' [url] ' . $request->getUri();
            $message .= ' [http method] ' . $request->getMethod();
            $message .= ' [body] ' . $request->getBody()->getContents();
        }
        parent::__construct($message, $e->getCode(), $e);
    }
    /**
     * Returns an array of error fields with descriptions. http://jsonapi.org/format/#error-objects
     *
     * [
     *  { "detail": "Sender testtest.com is not a valid email.",
     *    "source":{
     *      "pointer":"/data/attributes/email"
     *     }
     *  }
     * ]
     *
     * @return array
     */
    public function getErrorDetails()
    {
        return $this->errorDetails;
    }
}
