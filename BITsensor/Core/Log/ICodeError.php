<?php
include_once 'IError.php';

abstract class ICodeError extends IError
{
    public $filePath = null, $line = 0, $context;
 
    public function __construct($number = 0, $description = '', $filePath = null, $lineNumber = 0, $context = '') {
        parent::__construct($number, $description);
        $this->filePath = $filePath;
        $this->line = $lineNumber;
        $this->context = $context;
    }
}

class CodeError extends ICodeError {}