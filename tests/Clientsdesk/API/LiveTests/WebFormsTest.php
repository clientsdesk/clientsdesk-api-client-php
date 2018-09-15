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
        $count = count($response);
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
//        $response = $this->client->get('/web_forms', $indexParams);
        $count = count($response);
        $this->assertNotEmpty($response);
        $this->assertEquals(1, $count);
    }
}
