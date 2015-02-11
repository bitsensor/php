<?php

abstract class autoParse
{
    public $RAW_CONSTRUCTOR_ARRAY;
    
    public function __construct($keyValueArray) {
        $this->RAW_CONSTRUCTOR_ARRAY = $keyValueArray;
        autoParse::ParseArrayToProperties($this, $keyValueArray);
        if(method_exists($this, 'autoParseFinalization'))
            $this->autoParseFinalization();
    }
    
    private static function ParseArrayToProperties($class, $keyValueArray) {
        foreach ($keyValueArray as $key => $value) 
            if (property_exists($class, $key)) 
                $class->$key = $value;
    }
}

class LibRename
{
    public static function Rename(&$object, $renameFields)
    {
        $output = array();
        foreach ($renameFields as $key=>$value) {
            array_push($output, new Context($value, $object->$key));
        }
        return $output;
    }
}

class LibDebug
{
    public static function GetCallerFile($count = 0)
    {
        $trace = debug_backtrace();
        return $trace[$count + 1]['file'];
    }
}

class LibGuid
{
    public static function GenerateGuid()
    {
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', 
                mt_rand(0, 65535), mt_rand(0, 65535), 
                mt_rand(0, 65535), mt_rand(16384, 20479), 
                mt_rand(32768, 49151), mt_rand(0, 65535), 
                mt_rand(0, 65535), mt_rand(0, 65535));
    }
}

class LibError {

    public static function ErrNoToString($errno) {
        $e_type = '';
        switch ($errno) {
            case 1: $e_type = 'E_ERROR';
                break;
            case 2: $e_type = 'E_WARNING';
                break;
            case 4: $e_type = 'E_PARSE';
                break;
            case 8: $e_type = 'E_NOTICE';
                break;
            case 16: $e_type = 'E_CORE_ERROR';
                break;
            case 32: $e_type = 'E_CORE_WARNING';
                break;
            case 64: $e_type = 'E_COMPILE_ERROR';
                break;
            case 128: $e_type = 'E_COMPILE_WARNING';
                break;
            case 256: $e_type = 'E_USER_ERROR';
                break;
            case 512: $e_type = 'E_USER_WARNING';
                break;
            case 1024: $e_type = 'E_USER_NOTICE';
                break;
            case 2048: $e_type = 'E_STRICT';
                break;
            case 4096: $e_type = 'E_RECOVERABLE_ERROR';
                break;
            case 8192: $e_type = 'E_DEPRECATED';
                break;
            case 16384: $e_type = 'E_USER_DEPRECATED';
                break;
            case 30719: $e_type = 'E_ALL';
                break;
            default: $e_type = 'E_UNKNOWN';
                break;
        }
        return $e_type;
    }

}
