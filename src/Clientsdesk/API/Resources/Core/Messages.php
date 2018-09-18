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
     * Available options are listed below:
     *
     *  string $form_id required ID  of web for registered in ClientsDesk
     *
     *  string options that are defined in WebFrom fields (name, email, etc.)
     *
     * @throws \Exception
     * @return \stdClass | null
     * @throws \Clientsdesk\API\Exceptions\ApiResponseException
     */
    public function create(array $params)
    {
        $extraOptions = [];

        if (isset($params['form_id'])) {
            $params['source'] = [
                'id' => $params['form_id'],
                'type' => 'web_form'
            ];
            unset($params['form_id']);
        } else {
            throw new \Exception('Form ID required');
            return;
        }

        $route = $this->getRoute(__FUNCTION__, $params);
        return $this->client->post(
            $route,
            [$this->objectName => $params],
            $extraOptions
        );
    }

}