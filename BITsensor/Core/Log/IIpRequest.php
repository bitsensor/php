<?php
namespace BITsensor\Core\Log;


use BITsensor\Core\Lib\autoParse;
use BITsensor\Core\Lib\LibRename;

abstract class IIpRequest extends autoParse implements IContextable {
    public $REMOTE_ADDR;

    public function toContext() {
        return LibRename::Rename($this, array(
            'REMOTE_ADDR' => 'Address'));
    }
}