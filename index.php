<pre>
<?php
    global $BITsensor;
    
    //$BITsensor->AddContext(\BITsensor\Core\Context::User('Ruben BadUser'));

    
    print_r($_SERVER);
    /* @var $BITsensor Collector 
    
    $BITsensor->AddError(new SqlError(12, 'testsql'));*/

    $detection = new Detection('File Uploader');
        $detection->addRule(new DetectionRule(1, 'Malicious file extension', 1, 'File Upload' ));
        $BITsensor->AddDetection($detection);
?>