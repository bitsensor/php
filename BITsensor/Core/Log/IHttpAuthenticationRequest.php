<?php
namespace BITsensor\Core\Log;


use BITsensor\Core\Lib\autoParse;
use BITsensor\Core\Lib\LibRename;

abstract class IHttpAuthenticationRequest extends autoParse implements IContextable {
    public $PHP_AUTH_USER, $PHP_AUTH_PW, $AUTH_TYPE;

    public function toContext() {
        return LibRename::Rename($this, array(
            'PHP_AUTH_USER' => 'Username',
            'PHP_AUTH_PW' => 'Password',
            'AUTH_TYPE' => 'Type',
        ));
    }
}