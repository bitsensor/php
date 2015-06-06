<?php
namespace BITsensor\Core;

use BITsensor\Core\Lib\autoParse;
use BITsensor\Core\Lib\LibRename;

class ServerInfo extends autoParse implements IContextable {
    public $SERVER_ADDR, $SERVER_NAME, $SERVER_SOFTWARE, $SERVER_SIGNATURE, $SERVER_PORT, $DOCUMENT_ROOT, $GATEWAY_INTERFACE;

    public function toContext() {
        return LibRename::Rename($this, array(
            'SERVER_ADDR' => 'Address',
            'SERVER_NAME' => 'Name',
            'SERVER_SOFTWARE' => 'Software',
            'SERVER_SIGNATURE' => 'Signature',
            'SERVER_PORT' => 'Port',
            'DOCUMENT_ROOT' => 'Document Root',
            'GATEWAY_INTERFACE' => 'Interface'
        ));
    }
}