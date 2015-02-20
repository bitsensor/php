<?php

class SqlErrorHandler
{
    static $lastSqlErrorString = '';
    
    public static function Handle()
    { 
        $sqlError = SqlErrorHandler::CheckMySql();
        if($sqlError)
        {
            global $BITsensor;
            $BITsensor->AddError($sqlError);
            $sqlInjectionDetection = new Detection('MySQL Error', 'b4caeafd-6abf-4396-a257-ffbe201bc3f3', TRUE, 
                    new DetectionRule('General SQL error detection rule.', $sqlError->description, 1, 0, 'sqli'));
            
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
        
        if(mysql_error() == '')
            return FALSE;
        
        if(SqlErrorHandler::$lastSqlErrorString === mysql_error())
            return FALSE;
        
        SqlErrorHandler::$lastSqlErrorString = mysql_error();
            
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