<?php
namespace BITsensor\Core\Lib;


class LibError {

    public static function ErrNoToString($errno) {
        $e_type = '';
        switch ($errno) {
            case 1:
                $e_type = 'E_ERROR';
                break;
            case 2:
                $e_type = 'E_WARNING';
                break;
            case 4:
                $e_type = 'E_PARSE';
                break;
            case 8:
                $e_type = 'E_NOTICE';
                break;
            case 16:
                $e_type = 'E_CORE_ERROR';
                break;
            case 32:
                $e_type = 'E_CORE_WARNING';
                break;
            case 64:
                $e_type = 'E_COMPILE_ERROR';
                break;
            case 128:
                $e_type = 'E_COMPILE_WARNING';
                break;
            case 256:
                $e_type = 'E_USER_ERROR';
                break;
            case 512:
                $e_type = 'E_USER_WARNING';
                break;
            case 1024:
                $e_type = 'E_USER_NOTICE';
                break;
            case 2048:
                $e_type = 'E_STRICT';
                break;
            case 4096:
                $e_type = 'E_RECOVERABLE_ERROR';
                break;
            case 8192:
                $e_type = 'E_DEPRECATED';
                break;
            case 16384:
                $e_type = 'E_USER_DEPRECATED';
                break;
            case 30719:
                $e_type = 'E_ALL';
                break;
            default:
                $e_type = 'E_UNKNOWN';
                break;
        }
        return $e_type;
    }

}
