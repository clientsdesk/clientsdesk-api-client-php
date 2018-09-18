# Clientsdesk API client-php
PHP Client to connect clientsdesk.net

## Requirements
* PHP 5.6+

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

* Hint: You can find examples and operations in tests directory.

``` php

// Create new message from Web form
$messageAttributes = [
            'form_id' => 'form id here',
            'body' => 'some body from form',
            'subject' => 'optional subject',
            'name' => 'Test User',
            'email' => 'test@test.com',
            'custom_info => 'Some custom'

        ];
        $response = $this->client->messages()->create($messageAttributes);
        $message = $response->message;
print_r($message);

// Get Web Forms list

$indexParams = [
            'page' => 0,
            'per_page' => 5
        ];
        $response = $this->client->web_forms()->getIndex($indexParams);

```