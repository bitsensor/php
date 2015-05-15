<?php
namespace BITsensor\Core\Lib;


class LibDebug {
    public static function GetCallerFile($count = 0) {
        $trace = debug_backtrace();
        return $trace[$count + 1]['file'];
    }
}