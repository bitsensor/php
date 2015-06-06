<?php
namespace BITsensor\Core;

use BITsensor\Core\Lib\autoParse;
use BITsensor\Core\Lib\LibRename;

class PathInfo extends autoParse implements IContextable {
    public $SCRIPT_NAME;

    public function toContext() {
        return LibRename::Rename($this, array(
            'SCRIPT_NAME' => 'File Path'
        ));
    }
}