<?php

namespace Clientsdesk\API\Resources\Core;

use Clientsdesk\API\Resources\ResourceAbstract;
use Clientsdesk\API\Traits\Utility\InstantiatorTrait;


class Messages extends ResourceAbstract
{
    use InstantiatorTrait;


    /**
     * Declares routes to be used by this resource.
     */
    protected function setUpRoutes()
    {
        parent::setUpRoutes();
        $this->setRoutes([
            'create' => 'messages'
        ]);
    }

    /**
     * Create a conversation
     *
     * @param array $params
     *
     * @throws \Exception
     * @return \stdClass | null
     * @throws \Clientsdesk\API\Exceptions\ApiResponseException
     */
    public function create(array $params)
    {
        $extraOptions = [];

        $route = $this->getRoute(__FUNCTION__, $params);
        return $this->client->post(
            $route,
            [$this->objectName => $params],
            $extraOptions
        );
    }

}