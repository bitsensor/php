<?php



//            header('Content-Encoding: gzip');
//            header('Content-Type: text/html; charset=ISO-8859-1');
//            header('Content-Length: 3435795');
//
//            


            global $BITsensor;
            /* @var $BITsensor Collector */
            $BITsensor->AddContext(Context::User('Ruben van Vreeland'));
            
            

            $detection = new Detection('File Uploader');
            $detection->addRule(new DetectionRule('Malicious file extension', 1, '', new AttackType('file upload')));
            $BITsensor->AddDetection($detection);
            
            $BITsensor->AddDetection($detection)
?>