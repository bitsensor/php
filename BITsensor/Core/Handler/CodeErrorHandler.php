<?php
include_once BITsensorBasePath . 'Core/Lib/Lib.php';
include_once BITsensorBasePath . 'Core/Log/ICodeError.php';
include_once BITsensorBasePath . 'Core/Log/IFileIncludeError.php';
include_once 'SqlErrorHandler.php';

class CodeErrorHandler {
    
    public static function Handle($number = 0, $description = '', $filePath = '', $line = 0, $stack = '')
    {
        global $BITsensor;
        
        $error = new CodeError($number, $description, $filePath, $line);
        
        SqlErrorHandler::Handle($error);
        
        //$stack = CodeErrorHandler::formaldehyde_remove_recursion($stack);
        if(preg_match("/(input|stream|file|include|inclusion)/i", $description)){
            $error = new FileIncludeError($number, $description, $filePath, $line);
            $fileInclusionDetection = new Detection('File Inclusion Error', true, 
                     new DetectionRule(0, 'Detection based on General Error Based File Inclusion', 1, array('lfi', 'rfi'),null, $error));
            
            $BITsensor->AddDetection($fileInclusionDetection);
        }
        
        if(preg_match("/(sql)/i", $description)){
            $error = new SqlError($number, $description, $filePath, $line);
            $sqlInjectionDetection = new Detection('SQL Error', true, 
                    new DetectionRule(0, 'Detection of SQL Error in code', 1, 'sqli',null, $error));
            
            $BITsensor->AddDetection($sqlInjectionDetection);
        }
        
        $BITsensor->AddError($error);
    }
}