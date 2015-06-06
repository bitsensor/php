<?php
namespace BITsensor\Core\Handler\ServerErrorHandler;
use \BITsensor\Core\ServerError;

class ServerErrorHandler {
    static function raiseServerError($errorNumber, $errorFile) {
        global $BITsensor;
        $BITsensor->DeleteContext(array('Script', 'Path'));
        $BITsensor->AddError(new ServerError($errorNumber, $errorFile));
    }
}