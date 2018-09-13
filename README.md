# Clientsdesk API client-php
PHP Client to connect clientsdesk.net

## Requirements
* PHP 7.1+

## Installation

The Clientsdesk PHP API client can be installed using [Composer](https://packagist.org/packages/clientsdesk/clientsdesk-api-client-php).

### Composer

To install run `composer require clientsdesk/clientsdesk-api-client-php`

## Configuration

Configuration is done through an instance of `Clientsdesk\API\HttpClient`.
Api Key is mandatory and if not passed, an error will be thrown.

``` php
// load Composer
require 'vendor/autoload.php';

use Clientsdesk\API\HttpClient as ClientsdeskAPI;

$api_key     = "pLhQ6UhxsPZ8p7QU4S3rT6btXfH6yiVBjciKirnD"; // replace this with your api key
$api_signature     = "pLhQ6UhxsPZ8p7QU4S3rT6btXfH6yiVBjciKirnD"; // replace this with your api signature

$client = new ClientsdeskAPI($api_key, $api_signature);
```

## Usage

### Basic Operations

``` php

// Create new message from Web form
$messageAttributes = [
            'source' => [
                'id' => 'c2ejKyquro3gbN88y-Wx',
                'type' => 'web_form'
            ],
            'body' => 'some body from form',
            'subject' => 'optional subject',
            'tags' => ['some tag 1', 'some tag 2'],
            'author' => [
                'name' => 'First name Last name',
                'email' => 'test@test.com'
            ],
            'meta' => [
                'test_meta_1' => 'some meta 1 value',
                'test_meta_2' => 'some meta 1 value'
            ]

        ];
        $response = $this->client->messages()->create($messageAttributes);
        $message = $response->message;
print_r($message);

```