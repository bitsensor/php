<?php
include_once BITsensorBasePath . 'Core/Lib/Lib.php';
include_once BITsensorBasePath . 'Core/Log/ICodeError.php';
include_once BITsensorBasePath . 'Core/Log/IFileIncludeError.php';

class CodeErrorHandler {
    
    public static function Handle($number = 0, $description = '', $filePath = '', $line = 0, $stack = '')
    {
        global $BITsensor;
        
        $errorSendToSensor = FALSE;
        
        //$stack = CodeErrorHandler::formaldehyde_remove_recursion($stack);
        if(preg_match("/(input|stream|file|include|inclusion)/i", $description)){
            $error = new FileIncludeError($number, $description, $filePath, $line);
            $fileInclusionDetection = new Detection('File Inclusion Error', '4c7ce510-c8fa-49b2-ae03-c235e78e2206', true, 
                     new DetectionRule('Detection based on General Error Based File Inclusion', $description, 1, 0, array('lfi', 'rfi')));
            
            $BITsensor->AddDetection($fileInclusionDetection);
            $errorSendToSensor = TRUE;
        }
        
        if(preg_match("/(mysql)/i", $description)){
            $error = new SqlError($number, $description, $filePath, $line);
            $sqlInjectionDetection = new Detection('SQL Error', '9326ab07-6b92-4e4f-bb04-90855641b9c6', true, 
                     new DetectionRule('Detection of SQL Error in code', $description, 1, 0, array('sqli')));
            
            $BITsensor->AddDetection($sqlInjectionDetection);
            $errorSendToSensor = TRUE;
        }
        
        if($errorSendToSensor == FALSE)
        {
            $error = new CodeError($number, $description, $filePath, $line);
            $BITsensor->AddError($error);
        }
        
        return false;
    }
}