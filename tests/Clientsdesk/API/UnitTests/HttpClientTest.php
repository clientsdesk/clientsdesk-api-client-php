<?php

namespace Clientsdesk\API\UnitTests;


use Faker\Factory;

class HttpClientTest extends BasicTest
{

    public function testPostValidWebFrom(): void
    {
        $faker = Factory::create();
        // Create a new web form request
        $messageAttributes = [
            'message' => [
                'source' => [
                    'id' => 'c2ejKyquro3gbN88y-Wx',
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

            ]
        ];

        $response = $this->client->post('/messages', $messageAttributes);
        $message = $response->message;
        $this->assertEquals($messageAttributes['message']['body'], $message->body);
        $this->assertEquals($messageAttributes['message']['subject'], $message->subject);
    }

    /**
     * Test we can handle api exceptions, by finding a non-existing ticket
     *
     * @expectedException Clientsdesk\API\Exceptions\ApiResponseException
     * @expectedExceptionMessage Unprocessable Entity
     */
    public function testPostWebFromWithWrongEmail(): void
    {
        $faker = Factory::create();
        // Create a new web form request
        $this->client->post('/messages', [
                'message' => [
                    'source' => [
                        'id' => 'c2ejKyquro3gbN88y-Wx',
                        'type' => 'web_form'
                    ],
                    'body' => $faker->sentence(15),
                    'subject' => $faker->sentence(5),
                    'form_tags' => [$faker->word(), $faker->word()],
                    'author' => [
                        'name' => $faker->name(),
                        'email' => $faker->word(),
                        'phone' => $faker->e164PhoneNumber()
                    ],
                    'meta' => [
                        'test_meta_1' => $faker->sentence(3),
                        'test_meta_2' => $faker->sentence(3)
                    ]

                ]
            ]
        );
    }
}
