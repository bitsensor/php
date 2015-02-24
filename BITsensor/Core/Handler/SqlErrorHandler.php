<?php

class SqlErrorHandler
{
    static $lastSqlErrorString = '';
    
    public static function Handle($codeError = null)
    { 
        global $BITsensor;
        
        $sqlError = SqlErrorHandler::CheckMySql();
        if($sqlError)
        {  
            if(isset($codeError))
            {
                $sqlError->errLine = $codeError->errLine;
                $sqlError->filePath = $codeError->filePath;
            }
            
            $BITsensor->AddError($sqlError);
            
            $sqlInjectionDetection = new Detection('MySQL Error', 'b4caeafd-6abf-4396-a257-ffbe201bc3f3', TRUE, 
                    new DetectionRule('General SQL error detection rule.', $sqlError, 1, 0, 'sqli'));
            
            $BITsensor->AddDetection($sqlInjectionDetection);
        }
    }

    private static function CheckMySql()
    {
        if (!function_exists("mysql_errno"))
            return FALSE;
        
        if (mysql_errno() === 0)
            return FALSE;
                         
        $errorString = mysql_error();
        $errorNumber = mysql_errno();
        
        if(SqlErrorHandler::$lastSqlErrorString === $errorString)
            return FALSE;
        
        SqlErrorHandler::$lastSqlErrorString = $errorString;
            
        return new SqlError($errorNumber, $errorString);
    }
    
    private static function CheckForMySQLI()
    {
        if (function_exists("mysqli_errno"))
            return FALSE;
        
        if (mysqli_errno() == 0)
            return FALSE;
        
        //return new MySqlEvent(mysqli_errno(), mysqli_error());
    }
}