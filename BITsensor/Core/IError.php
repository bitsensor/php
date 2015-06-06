<?php
namespace BITsensor\Core;


abstract class IError {
    public $number = 0, $description = '', $events = array();

    public function __construct($errorNumber = 0, $errorDescription = '') {
        $this->number = $errorNumber;
        $this->description = $errorDescription;
    }

    public function AddEvent($event) {
        $this->Add($event);
    }

    public function Add($object) {
        array_push($this->events, $object);
    }
}
