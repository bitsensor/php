<?php
global $BITsensor;

include_once 'SqlErrorHandler.php';
SqlErrorHandler::Handle();

echo '<pre>';
echo($BITsensor->Serialize(true));