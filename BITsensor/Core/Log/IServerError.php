<?php
include_once 'IError.php';

abstract class IServerError extends IError
{
 public $filePath;
 
    public function __construct($number, $filePath, $description = '') {
        parent::__construct($number, ($description == '') ? $number : $description);
        $this->filePath = $filePath;
    }
}

class ServerError extends IServerError {}