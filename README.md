# Beta Release - 0.5

<img src="https://www.vultr.com/dist/img/brand/logo-dark.svg" width="300">

# Vultr API PHP Client.

[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/vultr/vultr-php/main/LICENSE)
[![Vultr-Php Changelog](https://img.shields.io/badge/-changelog-blue)](https://github.com/vultr/vultr-php/blob/main/CHANGELOG.md)
[![PHP Version Require](http://poser.pugx.org/vultr/vultr-php/require/php)](https://packagist.org/packages/vultr/vultr-php)
[![Latest Stable Version](http://poser.pugx.org/vultr/vultr-php/v)](https://packagist.org/packages/vultr/vultr-php)
[![Latest Unstable Version](http://poser.pugx.org/vultr/vultr-php/v/unstable)](https://packagist.org/packages/vultr/vultr-php)
[![Total Downloads](http://poser.pugx.org/vultr/vultr-php/downloads)](https://packagist.org/packages/vultr/vultr-php)
[![PHP Tests](https://github.com/vultr/vultr-php/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/vultr/vultr-php/actions/workflows/php.yml)
[![Test Coverage](https://vultr.github.io/vultr-php/code-coverage/badge.svg)](https://vultr.github.io/vultr-php/code-coverage/index.html)

## Getting Started

Must have a PSR7, PSR17, and PSR18 Compatible HTTP Client. 
[View all PSR's](https://www.php-fig.org/psr/)
This client will act on those interfaces above interfaces which allow for dependancy injection. See Usage for more info.
https://packagist.org/providers/psr/http-client-implementation

### Installation
```
composer require vultr/vultr-php
```

### Usage

#### Initializing the client
Once decided on what HTTP client implementation that will be used to initiate the client. If the client implementation you chose is a wider used client, it may be possible to be auto detected. This is because this client uses [PHP-Http/Discovery](https://github.com/php-http/discovery).
```php
<?php

declare(strict_types=1);

require (__DIR__.'/../vendor/autoload.php');

$client = Vultr\VultrPhp\VultrClient::create('Your Lovely Vultr API Key');
```
The above code example would try and initialize the client using the HTTP Discovery method. If you wanna customize your http client, or the Discovery Method does not find it, the VultrClient will allow to pass in the PSR18 client along with a PSR17 HTTP Factory.

```php
<?php

declare(strict_types=1);

require (__DIR__.'/../vendor/autoload.php');

$http_factory = new GuzzleHttp\Psr7\HttpFactory();
$client = Vultr\VultrPhp\VultrClient::create('Heres my api key', new GuzzleHttp\Client(), $http_factory, $http_factory);
```

#### Using the client
This client implements all the service endpoints in the current iteration of the version 2 api of vultr. Which can be found [here](https://www.vultr.com/api).

For more detailed examples view the [examples](https://github.com/vultr/vultr-php/tree/main/examples) folder.

##### Pagination
The client uses a linked list to paginate between your cursors. Each list call returns a ListOptions passed by reference which you can manipulate with each subsequent call and thus the function manipulates it as well. This allows you to choose previous and or next cursor links to navigate.

```php
<?php

declare(strict_types=1);

require (__DIR__.'/../vendor/autoload.php');

$client = Vultr\VultrPhp\VultrClient::create('Your Lovely Vultr API Key');

$options = new Vultr\VultrPhp\Util\ListOptions();
// Or
// $options = null;
/**
 * Whether you pass in a null $options or a ListOptions. You can always expect to have ListOptions be passed back out too you when calling the function.
 */
while (true)
{
	$instances = [];
	foreach ($client->instances->getInstances(null, $options) as $instance)
	{
		$instances[] = $instance;
	}

	// Exit our loop, we have reached the end. Hooray!
	if ($options->getNextCursor() == '')
	{
		break;
	}
	// Setting the "CurrentCursor" will tell the client which page it should transcode the url to make the request too.
	$options->setCurrentCursor($options->getNextCursor());
}

```

#### Exception Usage

TODO

## Documentation

See our documentation for [detailed information about API v2](https://www.vultr.com/api).

TODO

## Support

## Contribute

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

