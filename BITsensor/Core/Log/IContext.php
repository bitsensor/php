<?php

interface IContextable {
    public function toContext();
}

abstract class IContext {
    public $name, $value;
    
    public function __construct($name, $value) {
        if(gettype($value) == 'object' && in_array('IContextable', class_implements($value)))
            $value = $value->toContext();
        
        switch (gettype($name))
        {
            case 'array':
                
                while($curName = array_pop($name))
                    $context = new Context($curName, (isset($context)) ? $context : $value);
                
                $this->name = $context->name;
                $this->value = $context->value;
                
                break;
           
            default: 
                $this->name = $name;
                $this->value = $value;
        }
    }
    
    public function setName($name)
    {
        $new = new Context($name, $this->value);
        $this->name = $new->name;
        $this->value = $new->value;
    }
    
    public function setValue($value)
    {
        $new = new Context($this->name, $value);
        $this->name = $new->name;
        $this->value = $new->value;
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