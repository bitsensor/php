<?php
global $BITsensor;

include_once 'SqlErrorHandler.php';
SqlErrorHandler::Handle();

echo '<pre>';
print_r($BITsensor->Get());