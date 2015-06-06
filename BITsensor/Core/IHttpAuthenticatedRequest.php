<?php
namespace BITsensor\Core;


use BITsensor\Core\Lib\autoParse;
use BITsensor\Core\Lib\LibRename;

abstract class IHttpAuthenticatedRequest extends autoParse implements IContextable {
    public $REMOTE_USER;

    public function toContext() {
        return LibRename::Rename($this, array(
            'REMOTE_USER' => 'Remote User'
        ));
    }
}