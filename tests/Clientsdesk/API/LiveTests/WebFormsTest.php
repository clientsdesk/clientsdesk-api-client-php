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
        $web_forms = $response->data;
        $count = count($web_forms);
        $this->assertNotEmpty($web_forms);
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
        $web_forms = $response->data;
        $count = count($web_forms);
        $this->assertNotEmpty($web_forms);
        $this->assertEquals(5, $count);
    }
}
