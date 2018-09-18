<?php
/**
 * Created by PhpStorm.
 * User: howtwizer
 * Date: 8/6/18
 * Time: 17:13
 */

namespace Clientsdesk\API\LiveTests;

use Faker\Factory;

class MessagesTest extends BasicTest
{

    public function testCreate()
        /*
         * Live test if real web form.
         * Web Form data:
         *   hash_id: "5cVsx6JHHwHCnm5MtHxF",
         * fields: [
         * {"type"=>"email", "name"=>"email", "label"=>"Email", "placeholder"=>"Email", "required"=>true, "recommended"=>true},
         * {"type"=>"text", "name"=>"name", "label"=>"Name", "placeholder"=>"Enter your name", "required"=>false, "recommended"=>true},
         * {"type"=>"text", "name"=>"subject", "label"=>"Subject", "placeholder"=>"Subject", "required"=>false, "recommended"=>true},
         * {"type"=>"text", "name"=>"body", "label"=>"Message body", "placeholder"=>"Body text here", "required"=>false, "recommended"=>true},
         * {"type"=>"hidden", "name"=>"custom_info", "label"=>"", "placeholder"=>"Some custom info here", "required"=>false, "recommended"=>false}
         * ]
         *
         */
    {
        $faker = Factory::create();
        $messageAttributes = [
            'form_id' => '5cVsx6JHHwHCnm5MtHxF',
            'body' => $faker->sentence(15),
            'subject' => $faker->sentence(5),
            'name' => $faker->name(),
            'email' => $faker->email(),
            'custom_info' =>  $faker->sentence(3)
        ];
        $response = $this->client->messages()->create($messageAttributes);
        $this->assertEquals($messageAttributes['body'], $response['message']['body']);
        $this->assertEquals($messageAttributes['subject'], $response['message']['subject']);

        return $response;
    }

    /**
     * Test we can handle api exceptions, by finding a non-existing ticket
     *
     * @expectedException Clientsdesk\API\Exceptions\ApiResponseException
     * @expectedExceptionMessage Not Found
     */
    public function testCreateWrongSourceId()
    {
        $faker = Factory::create();
        $messageAttributes = [
            'form_id' => 'wrong_id',
            'body' => $faker->sentence(15),
            'subject' => $faker->sentence(5),
            'name' => $faker->name(),
            'email' => $faker->email(),
            'custom_info' =>  $faker->sentence(3)

        ];
        $response = $this->client->messages()->create($messageAttributes);
        $this->assertEquals($messageAttributes['body'], $response['message']['body']);
        $this->assertEquals($messageAttributes['subject'], $response['message']['subject']);

        return $response;
    }

    /**
     * Test we can handle api exceptions, by finding a non-existing ticket
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Form ID required
     */
    public function testCreateWithoutId()
    {
        $faker = Factory::create();
        $messageAttributes = [
            'body' => $faker->sentence(15),
            'subject' => $faker->sentence(5),
            'name' => $faker->name(),
            'email' => $faker->email(),
            'custom_info' =>  $faker->sentence(3)

        ];
        $response = $this->client->messages()->create($messageAttributes);
        $this->assertEquals($messageAttributes['body'], $response['message']['body']);
        $this->assertEquals($messageAttributes['subject'], $response['message']['subject']);

        return $response;
    }
}
