<?php
namespace BITsensor\Core\Handler;


use \BITsensor\Core\Detection;
use \BITsensor\Core\DetectionRule;
use \BITsensor\Core\SQL\SqlError;

class SqlErrorHandler {
    static $lastSqlErrorString = '';

    public static function Handle($codeError = null) {
        global $BITsensor;

        $sqlError = SqlErrorHandler::CheckMySql();
        if ($sqlError) {
            if (isset($codeError)) {
                $sqlError->line = $codeError->line;
                $sqlError->filePath = $codeError->filePath;
            }

            $BITsensor->AddError($sqlError);

            $sqlInjectionDetection = new Detection('MySQL Error', TRUE,
                new DetectionRule(0, 'General SQL error detection rule.', 1, 'sqli', null, $sqlError));

            $BITsensor->AddDetection($sqlInjectionDetection);
        }
    }

    private static function CheckMySql() {
        if (!function_exists("mysql_errno"))
            return FALSE;

        $errorString = (string)mysql_error();
        $errorNumber = (int)mysql_errno();

        if ($errorNumber === 0)
            return FALSE;

        if (SqlErrorHandler::$lastSqlErrorString === $errorString)
            return FALSE;

        SqlErrorHandler::$lastSqlErrorString = $errorString;

        return new SqlError((int)$errorNumber, (string)$errorString);
    }

    private static function CheckForMySQLI() {
        if (function_exists("mysqli_errno"))
            return FALSE;

        if (mysqli_errno() == 0)
            return FALSE;

        //return new MySqlEvent(mysqli_errno(), mysqli_error());
    }
}