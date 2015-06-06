<?php
namespace BITsensor\Core;

use BITsensor\Core\Lib\autoParse;
use BITsensor\Core\Lib\LibRename;

class RequestInfo extends autoParse implements IContextable {
    public $HTTP_CONNECTION, $REMOTE_PORT, $REQUEST_TIME, $REQUEST_TIME_FLOAT, $SCRIPT_FILENAME, $REQUEST_URI, $HTTP_REFERER, $REQUEST_METHOD;

    public function toContext() {
        return LibRename::Rename($this, array(
            'HTTP_CONNECTION' => 'Connection',
            'REMOTE_PORT' => 'Remote Port',
            'REQUEST_TIME' => 'Request Time',
            'REQUEST_TIME_FLOAT' => 'Request Time Float',
            'REQUEST_URI' => 'URI',
            'SCRIPT_FILENAME' => 'Absolute Script Path',
            'HTTP_REFERER' => 'Referrer',
            'REQUEST_METHOD' => 'Method'
        ));
    }
}