<?php

namespace Clientsdesk\API\Exceptions;

use Curl\Curl;

/**
 * Class ApiResponseException
 *
 * @package Clientsdesk\API\Exceptions
 */
class ApiResponseException extends \Exception
{
    /**
     * @param Curl $curl
     */
    protected $errorDetails = '';

    public function __construct(Curl $curl)
    {
        $message = $curl->errorMessage;
        $code =  $curl->getErrorCode();
        $this->errorDetails = $curl->getRawResponse();
        if ($code < 500) {
            $message .= ' [details] ' .  $this->errorDetails;
        } elseif ($code >= 500) {
            $message .= ' [details] Clientsdesk may be experiencing internal issues or undergoing scheduled maintenance.';
        } elseif (!$code) {
            // Unsuccessful response, log what we can
            $message .= '[url ]' . $curl->url;
            $message .= '[requestHeaders ]' . $curl->requestHeaders;
            $message .= '[responseHeaders ]' . $curl->responseHeaders;
            $message .= '[rawResponseHeaders ]' . $curl->rawResponseHeaders;
            $message .= '[responseCookies ]' . $curl->responseCookies;
            $message .= '[response ]' . $curl->response;
            $message .= '[rawResponse ]' . $curl->rawResponse;
        }
        parent::__construct($message, (int)$curl->errorCode);
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
