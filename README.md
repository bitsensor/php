<a id="about"></a>
![logo](https://dl2.pushbulletusercontent.com/3WwxLx0wKhfLB4sMWZ2QjLqFzwT5nwWD/Logo_BitSensorSmall_Light.png)

# BitSensor PHP Plugin

[![Latest Stable Version](https://poser.pugx.org/bitsensor/php/v/stable)](https://packagist.org/packages/bitsensor/php)[![Total Downloads](https://poser.pugx.org/bitsensor/php/downloads)](https://packagist.org/packages/bitsensor/php)[![License](https://poser.pugx.org/bitsensor/php/license)](https://packagist.org/packages/bitsensor/php)[![composer.lock](https://poser.pugx.org/bitsensor/php/composerlock)](https://packagist.org/packages/bitsensor/php)

[![pipeline status](https://git.bitsensor.io/plugins/php/badges/master/pipeline.svg)](https://git.bitsensor.io/plugins/nodejs/commits/master)

[![Twitter Follow](https://img.shields.io/twitter/follow/EnableBitSensor.svg?style=social&label=Follow)](https://twitter.com/intent/follow?screen_name=EnableBitSensor)

The BitSensor PHP plugin.

[BitSensor](https://bitsensor.io/)

[Documentation](https://plugins.bitsensor.io/php)

[Gitlab repo](https://git.bitsensor.io/plugins/php)



## Setup BitSensor

### Requirements
* `php >= 5.6.0`
* `composer`
* `uopz` [optional]
  
### Installation

* Install bitsensor/php package using composer:

    ```bash
    composer require bitsensor/php
    ```
    
* Install from source:

    ```bash
    php composer.phar install
    ```
    
* To use PDO and MySQLi query tracing, the [uopz](https://github.com/krakjoe/uopz) pecl extension must be installed.

    ```bash
    # You might have to install `pecl` and php-dev dependencies
    sudo apt-get install php-pear php-dev
    
    pecl install uopz
    
    # You might have to add `extension=uopz.so` to your php.ini, if that does not happen automatically
    echo 'extension=uopz.so' >> /etc/php/7.0/fpm/php.ini
    
    # In case of php-fpm, reload the service
    service php7.0-fpm reload
    
    # Check successful installation, the output should be `1`
    php -r 'echo extension_loaded("uopz");'
    ```

## Usage
BitSensor can be used with Composer or as a standalone Phar.

### Composer
Add ``bitsensor/php`` to your ``composer.json``. After running ``php composer.phar install`` all required dependencies will be available to
you. Refer to Composer's [Documentation](https://getcomposer.org/) for more information.

``index.php:``
```php
<?php
use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;

// Load Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Create config using PHP.
$config = new Config();
$config->setUri('http://user.bitsensor.io:8080');
$config->setUser('example_user');
$config->setApiKey('abcdefghijklmnopqrstuvwxyz');
$config->setMode(Config::MODE_DETECTION);
$config->setConnectionFail(Config::ACTION_ALLOW);
$config->setIpAddressSrc(Config::IP_ADDRESS_REMOTE_ADDR);
$config->setHostSrc(Config::HOST_SERVER_NAME);
$config->setLogLevel(Config::LOG_LEVEL_NONE);
$config->setUopzHook(Config::UOPZ_HOOK_ON);

// Start BitSensor 
$bitSensor = new BitSensor();
$bitSensor->config($config);
```

### Phar

Upload ``bitsensor.phar`` to your server and create a ``config.json`` file, or define your config in PHP.

``index.php:``
```php
<?php
use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;

// Load BitSensor phar
require_once '/path/to/bitsensor.phar';

// Create config using PHP.
$config = new Config();
$config->setUri('http://user.bitsensor.io:8080');
$config->setUser('example_user');
$config->setApiKey('abcdefghijklmnopqrstuvwxyz');
$config->setMode(Config::MODE_DETECTION);
$config->setConnectionFail(Config::ACTION_ALLOW);
$config->setIpAddressSrc(Config::IP_ADDRESS_REMOTE_ADDR);
$config->setHostSrc(Config::HOST_SERVER_NAME);
$config->setLogLevel(Config::LOG_LEVEL_NONE);
$config->setUopzHook(Config::UOPZ_HOOK_ON);

// Start BitSensor 
$bitSensor = new BitSensor();
$bitSensor->config($config);
```

## Configuration
You have the following config options at your disposal:

| PHP                       | JSON           | Value                                                                                                                                                      | Default                                             | Description                                                                                                                |
|---------------------------|----------------|------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------|
| ```setUri()```            | uri            | uri                                                                                                                                                        | <empty>                                             | URI to the BitSensor API.                                                                                                  |
| ```setUser()```           | user           | username                                                                                                                                                   | <empty>                                             | Your BitSensor username.                                                                                                   |
| ```setApiKey()```         | apiKey         | api key                                                                                                                                                    | <empty>                                             | Your BitSensor API key.                                                                                                    |
| ```setMode()```           | mode           | ```Config::MODE_ON``` ("on"), ```Config::MODE_DETECTION``` ("detection")                                                                                   | ```Config::MODE_ON``` ("on")                        | Running mode. In detection mode only logging will be done.                                                                 |
| ```setConnectionFail()``` | connectionFail | ```Config::ACTION_ALLOW``` ("allow"), ```Config::ACTION_BLOCK``` ("block")                                                                                 | ```Config::ACTION_BLOCK``` ("block")                | Action to perform when the connection to the BitSensor servers is lost.                                                    |
| ```setIpAddressSrc()```   | ipAddressSrc   | ```Config::IP_ADDRESS_REMOTE_ADDR``` ("remoteAddr"), ```Config::IP_ADDRESS_X_FORWARDED_FOR``` ("forwardedFor"), ```Config::IP_ADDRESS_MANUAL``` ("manual") | ```Config::IP_ADDRESS_REMOTE_ADDR``` ("remoteAddr") | Source of the IP address of the user.                                                                                      |
| ```setIpAddress()```      | ipAddress      | ip override                                                                                                                                                | <empty>                                             | IP address manual override value.                                                                                          |
| ```setHostSrc()```        | hostSrc        | ```Config::HOST_SERVER_NAME``` ("serverName"), ```Config::HOST_HOST_HEADER``` ("hostHeader"), ```Config::HOST_MANUAL``` ("manual")                         | ```Config::HOST_SERVER_NAME``` ("serverName")       | Source of the hostname.                                                                                                    |
| ```setHost()```           | host           | host address override                                                                                                                                      | <empty>                                             | Hostname manual override value.                                                                                            |
| ```setLogLevel()```       | logLevel       | ```Config::LOG_LEVEL_ALL``` ("all"), ```Config::LOG_LEVEL_NONE``` ("none")                                                                                 | ```Config::LOG_LEVEL_ALL``` ("all")                 | The logging level.                                                                                                         |
| ```setOutputFlushing```   | outputFlushing | ```Config::OUTPUT_FLUSHING_ON``` ("on"), ```Config::OUTPUT_FLUSHING_OFF``` ("off")                                                                         | ```Config::OUTPUT_FLUSHING_OFF``` ("off")           | Output flushing. Turning this on allows the browser to render the page while BitSensor is still working in the background. |
| ```setUopzHook```         | uopzHook       | ```Config::UOPZ_HOOK_ON``` ("on"), ```Config::UOPZ_HOOK_OFF``` ("off")                                                                                     | ```Config::UOPZ_HOOK_ON``` ("on")                   | Uopz Hooking. Turning this on enables BitSensor to hook into function calls.                                               |

### Tags
If you are running many applications, it might be sensible to group them by a tag. You can create a tag using the following snipplet
```php
global $datapoint;
$datapoint->putContext("tag", "cool-applications");
```

The configuration can be specified in either PHP or JSON. To use JSON instead of PHP use the following code:
``index.php``
```php
<?php
use BitSensor\Core\BitSensor;

$bitSensor = new BitSensor();
$bitSensor->config('/path/to/config.json');
```

Sample configuration file:
```json
{
  "uri": "http://bitsensor.io:8080/",
  "user": "example_user",
  "apiKey": "abcdefghijklmnopqrstuvwxyz",
  "mode": "detection",
  "connectionFail": "allow",
  "ipAddressSrc": "remoteAddr",
  "hostSrc": "serverName",
  "logLevel": "none",
  "uopzHook": "on"
}
```

## Apache
After sinking BitSensor hooks in your application, you can extend BitSensor's visibility to include Apache events that aren't processed by your application. 

To log Apache errors add the following to your ``.htaccess``:
```ApacheConf
# Open .phar files as PHP files
AddType application/x-httpd-php .phar

# Add all errors you want BitSensor to handle
# The path to BitSensor.phar is as seen in the URL in the browser
ErrorDocument 400 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=400
ErrorDocument 401 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=401
ErrorDocument 402 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=402
ErrorDocument 403 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=403
ErrorDocument 404 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=404
ErrorDocument 405 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=405
ErrorDocument 406 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=406
ErrorDocument 407 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=407
ErrorDocument 408 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=408
ErrorDocument 409 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=409
ErrorDocument 410 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=410
ErrorDocument 411 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=411
ErrorDocument 412 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=412
ErrorDocument 413 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=413
ErrorDocument 414 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=414
ErrorDocument 415 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=415
ErrorDocument 500 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=500
ErrorDocument 501 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=501
ErrorDocument 502 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=502
ErrorDocument 503 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=503
ErrorDocument 504 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=504
ErrorDocument 505 /path/to/BitSensor.phar/Handler/ErrorDocumentHandler.php?e=505

# Add all errors you want to show a custom page for
# The path is as seen in the URL in the browser
SetEnv ERROR_DOCUMENT_400 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_401 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_402 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_403 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_404 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_405 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_406 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_407 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_408 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_409 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_410 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_411 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_412 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_413 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_414 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_415 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_500 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_501 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_502 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_503 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_504 /path/to/error/document.html
SetEnv ERROR_DOCUMENT_505 /path/to/error/document.html
```


## Testing
Assuming default target location, a simple test run can be executed using ``curl localhost/php/test/index.php``. This should return "Accepted", the raw JSON datapoint and the encrypted datapoint.  
To test successful connection using your API key and endpoint, change the configuration in the ``test/index.php`` file and login to your BitSensor dashboard.

For more extensive debugging in your codebase, we provide the following hooks:

Debug logging can be printed using ``BitSensor\Util\Log::d($msg)``,
this will only be printed when running in debug mode, activated by setting ``$debug`` to true in the global scope like this:

```php
global $debug;
$debug = true;
```

This should be done in a test script and not in the actual source.

## External dependencies
Libraries not managed by Composer should go in the ``lib/`` folder.
