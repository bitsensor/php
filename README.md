# BitSensor PHP Plugin
## Requirements
* ``php >= 5.3.3``
* ``php5-curl >= 5.3.3``

## Composer
This project uses composer to handle dependencies. Use ``php composer.phar install`` to install everything after checking out the source.

## External dependencies
Libraries not managed by Composer should go in the ``lib/`` folder.

## Building
The application can be packaged as a PHP Archive (phar). Executing the following command will generate the archive:

``php build-tools/phar.php [output-file]``

By default, the resulting file will be placed in the ``build/`` folder, but this can be changed via the optional ``output-file`` parameter.