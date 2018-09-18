<?php
/**
 * Created by PhpStorm.
 * User: howtwizer
 * Date: 8/6/18
 * Time: 17:13
 */

namespace Clientsdesk\API\LiveTests;

use Faker\Factory;

class WebFormsTest extends BasicTest
{

    /**
     * Except to get a list of Web Forms from API in JSON format
     */
    public function testIndexOnePerpage()
    {
        $indexParams = [
          'page' => 0,
          'per_page' => 1
        ];
        $response = $this->client->web_forms()->getIndex($indexParams);
//        $response = $this->client->get('/web_forms', $indexParams);
        $count = count($response);
        $this->assertNotEmpty($response);
        $this->assertEquals(1, $count);
    }

    /**
     * Test we can handle api exceptions, wrong route
     *
     * @expectedException Clientsdesk\API\Exceptions\ApiResponseException
     * @expectedExceptionMessage Not Found
     */
    public function testCreateWrongSourceId()
    {
        $indexParams = [
            'page' => 0,
            'per_page' => 1
        ];
//        $response = $this->client->web_forms()->getIndex($indexParams);
        $response = $this->client->get('/WRONG', $indexParams);
        $count = count($response['web_forms']);
        $this->assertNotEmpty($response);
        $this->assertEquals(1, $count);
    }

    /**
     * Except to get a list of Web Forms from API in JSON format
     */
    public function testIndexFivePerpage()
    {
        $indexParams = [
            'page' => 0,
            'per_page' => 5
        ];
        $response = $this->client->web_forms()->getIndex($indexParams);
        $count = count($response['web_forms']);
        $this->assertNotEmpty($response);
        $this->assertEquals(5, $count);
    }

    /**
     * Except to get 1 Web Form from API in JSON format
     */
    public function testShow()
    {
        /*
         * Get froms list
         */
        $indexParams = [
            'page' => 0,
            'per_page' => 5
        ];
        $list = $this->client->web_forms()->getIndex($indexParams);
        $form_id = $list['web_forms'][0]['hash_id'];

        $response = $this->client->web_forms()->show($form_id);
        $this->assertNotEmpty($response);
        $this->assertEquals($response['web_form']['hash_id'], $form_id);
    }
}
