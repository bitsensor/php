<?php
namespace BITsensor\Core;

use BITsensor\Core\Lib\autoParse;
use BITsensor\Core\Lib\LibRename;

class UserInfo extends autoParse implements IContextable  {
    public $REMOTE_ADDR, $SERVER_PROTOCOL, $HTTP_USER_AGENT, $HTTP_ACCEPT, $HTTP_ACCEPT_CHARSET, $HTTP_ACCEPT_ENCODING, $HTTP_ACCEPT_LANGUAGE;
    
    public function toContext() {
        return LibRename::Rename($this, array(
            'REMOTE_ADDR' => 'Address',
            'SERVER_PROTOCOL' => 'Version',
            'HTTP_USER_AGENT' => 'User-Agent',
            'HTTP_ACCEPT' => 'Accept-Media',
            'HTTP_ACCEPT_CHARSET' => 'Accept-Charset',
            'HTTP_ACCEPT_ENCODING' => 'Accept-Encoding',
            'HTTP_ACCEPT_LANGUAGE' => 'Accept-Language',
        ));
    }
}