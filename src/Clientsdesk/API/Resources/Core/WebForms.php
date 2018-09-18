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
            'getIndex' => 'web_forms',
            'show' => 'web_forms'
        ]);
    }


    /**
     * This is a helper method to do a get request.
     *
     * @param   array $params
     *
     * @return \stdClass | null
     * @throws \Clientsdesk\API\Exceptions\ApiResponseException
     */
    public function getIndex(array $params)
    {
        $route = $this->getRoute(__FUNCTION__, $params);
        return $this->client->get(
            $route,
            $params
        );
    }

    /**
     * This is a helper method to do a get request.
     *
     * @param   string $form_id
     * @param   array $params
     *
     * @return \stdClass | null
     * @throws \Clientsdesk\API\Exceptions\ApiResponseException
     */
    public function show($form_id, array $params = [])
    {
        $route = $this->getRoute(__FUNCTION__) . '/' . $form_id;
        return $this->client->get(
            $route,
            $params
        );
    }


}