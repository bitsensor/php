<?php
namespace BITsensor\Core\Lib;


use \BITsensor\Core\Context;

class LibRename {
    public static function Rename(&$object, $renameFields) {
        $output = array();
        foreach ($renameFields as $key => $value) {
            array_push($output, new Context($value, $object->$key));
        }
        return $output;
    }
}