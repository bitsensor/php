<?php
namespace BITsensor\Core;

abstract class IContext {
    public $name, $value;

    public function __construct($name, $value) {
        $this->setName($name);

        if (gettype($value) == 'object' && in_array('BITsensor\Core\IContextable', class_implements($value)))
            $value = $value->toContext();

        $this->value = $value;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setValue($value) {
        $this->value = $value;
    }
}