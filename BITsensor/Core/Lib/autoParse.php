<?php
namespace BITsensor\Core\Lib;


abstract class autoParse {
    public $RAW_CONSTRUCTOR_ARRAY;

    public function __construct($keyValueArray) {
        $this->RAW_CONSTRUCTOR_ARRAY = $keyValueArray;
        autoParse::ParseArrayToProperties($this, $keyValueArray);
        if (method_exists($this, 'autoParseFinalization'))
            $this->autoParseFinalization();
    }

    private static function ParseArrayToProperties($class, $keyValueArray) {
        foreach ($keyValueArray as $key => $value)
            if (property_exists($class, $key))
                $class->$key = $value;
    }
}