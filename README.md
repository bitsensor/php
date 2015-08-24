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