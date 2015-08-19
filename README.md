# BitSensor PHP Plugin
## Requirements
* ``php >= 5.3.3``
* ``php5-curl >= 5.3.3``

## Composer
This project uses composer to handle dependencies. Use ``php composer.phar install`` to install everything after checking out the source.

## Usage
``index.php:``
```php
// Load BitSensor phar
require_once '/path/to/BitSensor.phar';

// Create config
$config = json_encode(array(
    Config::URI => 'http://bitsensor.io/api/',
    Config::USER => 'your_username',
    Config::API_KEY => 'your_api_key',
    Config::MODE => Config::MODE_ON,
    Config::CONNECTION_FAIL => Config::ACTION_BLOCK
));

// ...or save it in a JSON file
$config = file_get_contents('config.json');

// Start BitSensor with the config
$bitSensor = new BitSensor(new Config($config));
```

``config.json:``
```json
{
  "uri": "http://bitsensor.io/api/",
  "user": "your_username",
  "apiKey": "your_api_key",
  "mode": "on",
  "connectionFail": "block"
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