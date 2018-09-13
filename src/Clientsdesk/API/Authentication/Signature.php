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
     * @var string
     */
    private $access_key;

    /**
     * @param string $api_key
     * @param string $access_key
     */
    public function __construct($api_key, $access_key)
    {
        $this->api_key = $api_key;
        $this->access_key = $access_key;
    }

    public function authenticate(RequestInterface $request)
    {
        $token = bin2hex(random_bytes(24));
        $timestamp = time();
        $time_digest = join([$timestamp, $token]);
        $signature = hash_hmac('sha256', $time_digest, $this->api_key, false );
        $header = sprintf('CD1-HMAC-SHA256 Token=%s Signature=%s Timestamp=%u Key=%s', $token, $signature, $timestamp, $this->access_key);
        return $request->withHeader('Authorization', $header);
    }
}