<?php
include_once 'IContext.php';

abstract class IRequestInput extends IContext {
    public $name, $value;
       
    public function __construct($name, $value) {
        $this->name = $name; 
        $this->value = $value;
    }    
}

class RequestInput extends IRequestInput {}