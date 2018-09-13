<?php

namespace Clientsdesk\API\Resources\Core;

use Clientsdesk\API\Resources\ResourceAbstract;
use Clientsdesk\API\Traits\Utility\InstantiatorTrait;


class WebForms extends ResourceAbstract
{
    use InstantiatorTrait;

    /**
     * Declares routes to be used by this resource.
     */
    protected function setUpRoutes()
    {
        parent::setUpRoutes();
        $this->setRoutes([
            'getIndex' => 'web_forms'
        ]);
    }



    public function getIndex(array $params)
    {
        $route = $this->getRoute(__FUNCTION__, $params);
        return $this->client->get(
            $route,
            $params
        );
    }


}