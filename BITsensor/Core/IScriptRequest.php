<?php
namespace BITsensor\Core;


use BITsensor\Core\Lib\autoParse;
use BITsensor\Core\Lib\LibRename;

abstract class IScriptRequest extends autoParse implements IContextable {
    public $SCRIPT_FILENAME;

    public function toContext() {
        return LibRename::Rename($this, array(
            'SCRIPT_FILENAME' => 'Path'
        ));
    }
}