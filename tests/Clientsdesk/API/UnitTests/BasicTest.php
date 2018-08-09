<?php

namespace Clientsdesk\API\UnitTests;

use PHPUnit\Framework\TestCase;
use Clientsdesk\API\HttpClient;

/**
 * Basic test class
 */
abstract class BasicTest extends TestCase
{
    /**
     * @var HttpClient
     */
    protected $client;
    /**
     * @var string
     */
    protected $subdomain;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var string
     */
    protected $token;
    /**
     * @var string
     */
    protected $oAuthToken;
    /**
     * @var string
     */
    protected $hostname;
    /**
     * @var string
     */
    protected $scheme;
    /**
     * @var string
     */
    protected $port;
    /**
     * @var array
     */
    protected $mockedTransactionsContainer = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->api_key = getenv('API_KEY');
        $this->hostname = getenv('TEST_HOSTNAME');
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->client = new HttpClient($this->api_key, $this->hostname);
        $this->client->setApiBasePath('api/v1');
    }
}