<?php
include_once 'IError.php';

abstract class ICodeError extends IError
{
    public $filePath, $errLine, $errContext;
 
    public function __construct($number = 0, $description = '', $filePath = '', $lineNumber = 0, $context = '') {
        parent::__construct($number, $description);
        $this->filePath = $filePath;
        $this->errLine = $lineNumber;
        $this->errContext = $context;
    }
}

class CodeError extends ICodeError {}