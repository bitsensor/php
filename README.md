![logo](https://dl2.pushbulletusercontent.com/3WwxLx0wKhfLB4sMWZ2QjLqFzwT5nwWD/Logo_BitSensorSmall_Light.png)

# BitSensor PHP Plugin

The BitSensor PHP plugin.

[BitSensor](https://bitsensor.io/)

[Documentation](https://plugins.bitsensor.io/php)

[Gitlab repo](https://git.bitsensor.io/plugins/php)


## Setup BitSensor

### Requirements
* `php >= 5.6.0`
* `composer`
* `uopz` [optional, for query tracing]
  
## Installation
```bash
composer require bitsensor/php
```

## Configuration

### Code 
```php
<?php
use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Connector\ApiConnector;
use BitSensor\Blocking\Blocking;
use BitSensor\Blocking\Action\BlockingpageAction;
use BitSensor\Handler\IpHandler;
use BitSensor\Handler\AfterRequestHandler;

// Load Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Create config using PHP.
ApiConnector::setUser('dev');
ApiConnector::setApiKey('secret-apikey');
// ApiConnector::setHost('optional-host'); when not running on bitsensor.io
BlockingpageAction::setUser('dev');
// BlockingpageAction::setHost('optional-host'); //when not running on bitsensor.io
Blocking::setAction(new BlockingpageAction());
BitSensor::setConnector(new ApiConnector());
IpHandler::setIpAddressSrc(IpHandler::IP_ADDRESS_REMOTE_ADDR);
AfterRequestHandler::setExecuteFastcgiFinishRequest(true); // If you are using FastCGI
BitSensor::setEnbaleUopzHook(true); // If you have enabled UOPZ

// Start BitSensor 
BitSensor::run();
```

### JSON
``index.php``
```php
<?php
use BitSensor\Core\BitSensor;

BitSensor::configure('/path/to/config.json');
```

Sample configuration file:
```json
{ 
  "connector": {
    "type": "api",
    "user": "dev",
    "apikey": "php-plugin-test"
  },
  "blocking": {
    "action" :{ 
      "type": "blockingpage",
      "user": "dev"
    }
  },
  "mode": "ids",
  "ipAddressSrc": "remoteAddr",
  "hostSrc": "serverName",
  "logLevel": "none",
  "uopzHook": "on",
  "executeFastCgi": "off"
}
```

## Documentation
You have the following config options at your disposal:

| PHP                           | JSON           | Value                                                                                                                                                      | Default                                                 | Description                                                                                                                |
|-------------------------------|----------------|------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------|
| ```setMode()```               | mode           | ```BitSensor::MODE_IDS``` ("ids"), ```BitSensor::MODE_MONITORING``` ("monitoring"), ```BitSensor::MODE_OFF``` ("off")                                      | ```BitSensor::MODE_IDS``` ("ids")                       | BitSensor pipeline running mode. Default "ids" mode includes process pipeline, false positive detection, auto-blocking. "monitoring" mode excludes auto-blocking while in "off" mode, only logging will be done                                                                    |
| ```setIpAddressSrc()```       | ipAddressSrc   | ```Config::IP_ADDRESS_REMOTE_ADDR``` ("remoteAddr"), ```Config::IP_ADDRESS_X_FORWARDED_FOR``` ("forwardedFor"), ```Config::IP_ADDRESS_MANUAL``` ("manual") | ```Config::IP_ADDRESS_REMOTE_ADDR``` ("remoteAddr")     | Source of the IP address of the user.                                                                                      |
| ```setIpAddress()```          | ipAddress      | ip override                                                                                                                                                | <empty>                                                 | IP address manual override value.                                                                                          |
| ```setHostSrc()```            | hostSrc        | ```Config::HOST_SERVER_NAME``` ("serverName"), ```Config::HOST_HOST_HEADER``` ("hostHeader"), ```Config::HOST_MANUAL``` ("manual")                         | ```Config::HOST_SERVER_NAME``` ("serverName")           | Source of the hostname.                                                                                                    |
| ```setHost()```               | host           | host address override                                                                                                                                      | <empty>                                                 | Hostname manual override value.                                                                                            |
| ```setLogLevel()```           | logLevel       | ```Config::LOG_LEVEL_ALL``` ("all"), ```Config::LOG_LEVEL_NONE``` ("none")                                                                                 | ```Config::LOG_LEVEL_ALL``` ("all")                     | The logging level.                                                                                                         |
| ```setOutputFlushing```       | outputFlushing | ```Config::OUTPUT_FLUSHING_ON``` ("on"), ```Config::OUTPUT_FLUSHING_OFF``` ("off")                                                                         | ```Config::OUTPUT_FLUSHING_OFF``` ("off")               | Output flushing. Turning this on allows the browser to render the page while BitSensor is still working in the background. |
| ```setUopzHook```             | uopzHook       | ```Config::UOPZ_HOOK_ON``` ("on"), ```Config::UOPZ_HOOK_OFF``` ("off")                                                                                     | ```Config::UOPZ_HOOK_ON``` ("on")                       | Uopz Hooking. Turning this on enables BitSensor to hook into function calls.                                               |
| ```setFastcgiFinishRequest``` | executeFastCgi | ```Config::EXECUTE_FASTCGI_FINISH_REQUEST_ON``` ("on"), ```Config::EXECUTE_FASTCGI_FINISH_REQUEST_OFF``` ("off")                                           | ```Config::EXECUTE_FASTCGI_FINISH_REQUEST_OFF``` ("off")| Finish request to your FastCGI webserver, while processing BitSensor in a seperate thread.                                 |

### Connector Types

#### Api
| PHP                           | JSON           | Value                                                                                                                                                      | Default                                                 | Description                                                                                                                |
|-------------------------------|----------------|------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------|
| ```setUser()```               | user           | username                                                                                                                                                   | <empty>                                                 | Your BitSensor username.                                                                                                   |
| ```setApiKey()```             | apikey         | api key                                                                                                                                                    | <empty>                                                 | Your BitSensor API key.                                                                                                    |
| ```setHost()```               | host           | hostname                                                                                                                                                   | {user}.bitsensor.io                                     | Hostname of the BitSensor endpoint.                                                                                        |
| ```setPort()```               | port           | port                                                                                                                                                       | 8080                                                    | Port of the BitSensor endpoint.                                                                                            |

#### File
| PHP                 | JSON     | Value            | Default                               | Description  |
|---------------------|----------|------------------|---------------------------------------|--------------|
| ```setFilename()``` | filename | File to write to | /var/log/bitsensor-datapoints-php.log | Logfile that is appendd to. Make sure that the Apache or Nginx user can write to this file. Set this using `touch /var/log/bitsensor-datapoints-php.log; chown www-data /var/log/bitsensor-datapoints-php.log; chmod 200 /var/log/bitsensor-datapoints-php.log` |


### Blocking Actions
#### Blockingpage
| PHP                           | JSON           | Value                                                                                                                                                      | Default                                                 | Description                                                                                                                |
|-------------------------------|----------------|------------------------------------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------|
| ```setUser()```               | user           | username                                                                                                                                                   | <empty>                                                 | Your BitSensor username.                                                                                                   |
| ```setHost()```               | host           | hostname                                                                                                                                                   | {user}.bitsensor.io                                     | Hostname of the BitSensor endpoint.                                                                                        |
| ```setPort()```               | port           | port                                                                                                                                                       | 2080                                                    | Port of the BitSensor endpoint.                                                                                            |

## Query tracing
    
To use PDO and MySQLi query tracing, the [uopz](https://github.com/krakjoe/uopz) pecl extension must be installed.

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

## Logging

### Monolog
```php
<?php
use Monolog\Logger;

use Monolog\Handler\PsrHandler;
use BitSensor\Handler\PsrLogHandler;

// Your existing logger code
$log = new Logger('name');

// Add the BitSensor PsrLogHandler
$log->pushHandler(new PsrHandler(new PsrLogHandler()));
```

### Tags
If you are running many applications, it might be sensible to group them by a tag. You can create a tag using the following snipplet
```php
<?php
use \BitSensor\Core\BitSensor;
BitSensor::putEndpoint("tag", "cool-applications");
```

### WebSocket
If you are using websockets, BitSensor needs to know in order to properly detect attacks.
```php
<?php
use \BitSensor\Core\BitSensor;
use \BitSensor\Core\EndpointConstants;

BitSensor::putEndpoint(EndpointConstants::WEBSOCKET, 'true');
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


## Debugging
For more extensive debugging in your codebase, we provide the following hooks:

Debug logging can be printed using ``BitSensor\Util\Log::d($msg)``,
this will only be printed when running in debug mode, activated by setting:

```php
\BitSensor\Util\Log::setEnabled(true);
```

This should be done in a test script and not in the actual source.

## External dependencies
Libraries not managed by Composer should go in the ``lib/`` folder.