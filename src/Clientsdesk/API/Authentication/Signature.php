<?php

namespace Clientsdesk\API\Authentication;

use Http\Message\Authentication;
use Psr\Http\Message\RequestInterface;

class Signature implements Authentication
{

    /**
     * @var string
     */
    private $api_key;


    /**
     * @param string $api_key
     * @param string $password
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    public function authenticate(RequestInterface $request)
    {
        $token = bin2hex(random_bytes(24));
        $timestamp = time();
        $time_digest = join([$timestamp, $token]);
        $signature = hash_hmac('sha256', $time_digest, $this->api_key, false );
        $header = sprintf('CD1-HMAC-SHA256 Token=%s Signature=%s Timestamp=%u', $token, $signature, $timestamp);
        return $request->withHeader('Authorization', $header);
    }
}