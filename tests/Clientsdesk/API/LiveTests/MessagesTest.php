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
    {
        $faker = Factory::create();
        $messageAttributes = [
            'source' => [
                'id' => 'c2ejKyquro3gbN88y-Wx',
                'type' => 'web_form'
            ],
            'body' => $faker->sentence(15),
            'subject' => $faker->sentence(5),
            'tags' => [$faker->word(), $faker->word()],
            'author' => [
                'name' => $faker->name(),
                'email' => $faker->email()
            ],
            'meta' => [
                'test_meta_1' => $faker->sentence(3),
                'test_meta_2' => $faker->sentence(3)
            ]

        ];
        $response = $this->client->messages()->create($messageAttributes);
        var_dump($response);
        print_r($response);
//        $message = $response->message;
        $this->assertEquals($messageAttributes['body'], $response['message']['body']);
        $this->assertEquals($messageAttributes['subject'],  $response['message']['subject']);

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
            'source' => [
                'id' => 'wrong_id',
                'type' => 'web_form'
            ],
            'body' => $faker->sentence(15),
            'subject' => $faker->sentence(5),
            'form_tags' => [$faker->word(), $faker->word()],
            'author' => [
                'name' => $faker->name(),
                'email' => $faker->email()
            ],
            'meta' => [
                'test_meta_1' => $faker->sentence(3),
                'test_meta_2' => $faker->sentence(3)
            ]

        ];
        $response = $this->client->messages()->create($messageAttributes);
        $message = $response->message;
        $this->assertEquals($messageAttributes['body'],  $response['message']['body']);
        $this->assertEquals($messageAttributes['subject'], $response['message']['subject']);

        return $response;
    }
}
