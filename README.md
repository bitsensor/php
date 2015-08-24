# BitSensor PHP Plugin
## Requirements
* ``php >= 5.3.3``
* ``php5-curl >= 5.3.3``

## Composer
This project uses composer to handle dependencies. Use ``php composer.phar install`` to install everything after checking out the source.

## Usage
Upload ``BitSensor.phar`` to your server and create a ``config.json`` file.

``index.php:``
```php
// Load BitSensor phar
require_once '/path/to/BitSensor.phar';

// Start BitSensor 
$bitSensor = new BitSensor());
```

``config.json:``
```json
{
  "uri": "http://bitsensor.io/api/",
  "user": "your_username",
  "apiKey": "your_api_key",
  "mode": "on",
  "connectionFail": "block",
  "ipAddressSrc": "remoteAddr"
}
```

To log Apache errors add the following to your ``.htaccess``:
```ApacheConf
AddType application/x-httpd-php .phar

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
```

## External dependencies
Libraries not managed by Composer should go in the ``lib/`` folder.

## Building
The application can be packaged as a PHP Archive (phar). Executing the following command will generate the archive:

``php build-tools/phar.php [output-file]``

By default, the resulting file will be placed in the ``build/`` folder, but this can be changed via the optional ``output-file`` parameter.

## Testing
Debug logging can be printed using ``BitSensor\Util\Log::d($msg)``,
this will only be printed when running in debug mode, activated by adding ``$debug`` to  the global scope like this:

``global $debug;``

This should be done in a test script and not in the actual source.