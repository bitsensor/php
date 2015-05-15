<?php
namespace BITsensor\Core\Log;


use BITsensor\Core\Lib\autoParse;
use BITsensor\Core\Lib\LibRename;

abstract class IServerInfo extends autoParse implements IContextable {
    public $SERVER_ADDR, $SERVER_NAME, $SERVER_SOFTWARE, $SERVER_SIGNATURE, $SERVER_PORT;

    public function toContext() {
        return LibRename::Rename($this, array(
            'SERVER_ADDR' => 'Address',
            'SERVER_NAME' => 'Name',
            'SERVER_SOFTWARE' => 'Software',
            'SERVER_SIGNATURE' => 'Signature',
            'SERVER_PORT' => 'Port',
        ));
    }
}