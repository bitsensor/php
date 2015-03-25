<?php
interface IContextable {
    public function toContext();
}

abstract class IContext {
    public $name, $value;
    
    public function __construct($name, $value) {
        $this->setName($name);
        
        if(gettype($value) == 'object' && in_array('IContextable', class_implements($value)))
            $value = $value->toContext();
        
        $this->value = $value;
    }
    
    public function setName($name)
    {
        $this->name = json_encode($name);
    }
    
    public function setValue($value)
    {
        $this->value = $value;
    }
}

class Context extends IContext {
    public static function User($identifier, $applicationName = 'Application')
    {
        return new Context(array($applicationName, 'User'), $identifier);
    }
    
    public static function Session($identifier, $applicationName = 'Application')
    {
        return new Context(array($applicationName, 'Session'), $identifier);
    }
}