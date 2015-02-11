<?php

abstract class IError{
    public $number, $description, $events = array();
    
    public function __construct($errorNumber, $errorDescription) {
        $this->number = $errorNumber;
        $this->description = $errorDescription;
    }
    
    public function AddEvent($event)
    {
        $this->Add($event);
    }
    
    public function Add($object)
    {
        array_push($this->events, $object);
    }
}

class Error extends IError {}