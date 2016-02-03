# BitSensor PHP Plugin
## Requirements
* ``php >= 5.3.3``
* ``php5-curl >= 5.3.3``

## Composer
This project uses composer to handle dependencies. Use ``php composer.phar install`` to install everything after checking out the source.

## Usage
Upload ``BitSensor.phar`` to your server and create a ``config.json`` file, or define your config in PHP.

``index.php:``
```php
use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;

// Load BitSensor phar
require_once '/path/to/bitsensor.phar';

// Create config using PHP.
$config = new Config();
$config->setUri('http://bitsensor.io/api/');
$config->setUser('example_user');
$config->setApiKey('abcdefghijklmnopqrstuvwxyz');
$config->setMode(Config::MODE_DETECTION);
$config->setConnectionFail(Config::ACTION_ALLOW);
$config->setIpAddressSrc(Config::IP_ADDRESS_REMOTE_ADDR);
$config->setLogLevel(Config::LOG_LEVEL_NONE);

// Start BitSensor 
$bitSensor = new BitSensor();
```

``config.json:``
```json
{
  "uri": "http://bitsensor.io/",
  "user": "example_user",
  "apiKey": "abcdefghijklmnopqrstuvwxyz",
  "mode": "detection",
  "connectionFail": "allow",
  "ipAddressSrc": "remoteAddr",
  "logLevel": "none"
}
```

### Config
You have the following config options at your disposal:

| PHP                       | JSON           | Value                                                                      | Default                                             | Description                                                             |
|---------------------------|----------------|----------------------------------------------------------------------------|-----------------------------------------------------|-------------------------------------------------------------------------|
| ```setUri()```            | uri            | uri                                                                        | <empty>                                             | URI to the BitSensor API.                                               |
| ```setUser()```           | user           | username                                                                   | <empty>                                             | Your BitSensor username.                                                |
| ```setApiKey()```         | apiKey         | api key                                                                    | <empty>                                             | Your BitSensor API key.                                                 |
| ```setMode()```           | mode           | ```Config::MODE_ON``` ("on"), ```Config::MODE_DETECTION``` ("detection")   | ```Config::MODE_ON``` ("on")                        | Running mode. In detection mode only logging will be done.              |
| ```setConnectionFail()``` | connectionFail | ```Config::ACTION_ALLOW``` ("allow"), ```Config::ACTION_BLOCK``` ("block") | ```Config::ACTION_BLOCK``` ("block")                | Action to perform when the connection to the BitSensor servers is lost. |
| ```setIpAddressSrc()```   | ipAddressSrc   | ```Config::IP_ADDRESS_REMOTE_ADDR``` ("remoteAddr")                        | ```Config::IP_ADDRESS_REMOTE_ADDR``` ("remoteAddr") | Source of the IP address of the user.                                   |
| ```setLogLevel()```       | logLevel       | ```Config::LOG_LEVEL_ALL``` ("all"), ```Config::LOG_LEVEL_NONE``` ("none") | ```Config::LOG_LEVEL_ALL``` ("all")                 | The logging level.                                                      |

### Apache

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

## External dependencies
Libraries not managed by Composer should go in the ``lib/`` folder.

## Building
The application can be packaged as a PHP Archive (phar). Executing the following command will generate the archive:

``php build-tools/phar.php [output-file]``

By default, the resulting file will be placed in the ``build/`` folder, but this can be changed via the optional ``output-file`` parameter.

## Testing
Debug logging can be printed using ``BitSensor\Util\Log::d($msg)``,
this will only be printed when running in debug mode, activated by setting ``$debug`` to true in the global scope like this:

```php
global $debug;
$debug = true;
```

This should be done in a test script and not in the actual source.