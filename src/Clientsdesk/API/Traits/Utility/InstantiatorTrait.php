<?php

namespace Clientsdesk\API\Traits\Utility;

use Clientsdesk\API\HttpClient;
use Clientsdesk\API\Resources\ResourceAbstract;

/**
 * The Instantiator trait which has the magic methods for instantiating Resources
 * @package Clientsdesk\API
 *
 */
trait InstantiatorTrait
{
    /**
     * Generic method to object getter. Since all objects are protected, this method
     * exposes a getter function with the same name as the protected variable, for example
     * $client->messages can be referenced by $client->messages()
     *
     * @param $name
     * @param $arguments
     *
     * @return ChainedParametersTrait
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if ((array_key_exists($name, $validSubResources = $this::getValidSubResources()))) {
            $className = $validSubResources[$name];
            $client    = ($this instanceof HttpClient) ? $this : $this->client;
            $class     = new $className($client);
        } else {
            throw new \Exception("No method called $name available in " . __CLASS__);
        }

        $chainedParams = ($this instanceof ResourceAbstract) ? $this->getChainedParameters() : [];

        if ((isset($arguments[0])) && ($arguments[0] != null)) {
            $chainedParams = array_merge($chainedParams, [get_class($class) => $arguments[0]]);
        }

        $class = $class->setChainedParameters($chainedParams);

        return $class;
    }
}
