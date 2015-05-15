<?php
    global $BITsensor;
    
    print_r(
            pathinfo ( $_REQUEST['x'] ,  PATHINFO_EXTENSION ));
    
    /* @var $BITsensor Collector 
    $BITsensor->AddContext(Context::User('Ruben BadUser'));

    $BITsensor->AddError(new SqlError(12, 'testsql'));

    $detection = new Detection('File Uploader');
        $detection->addRule(new DetectionRule(1, 'Malicious file extension', 1, 'File Upload' ));
        $BITsensor->AddDetection($detection);*/
?>