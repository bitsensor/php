<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <pre>
            <?php
            error_reporting(-1);
            
            /* @var $BITsensor Collector */
            $BITsensor->AddContext(Context::User('Ruben van Vreeland'));

            $detection = new Detection('File Uploader');
            $detection->addRule(new DetectionRule('Malicious file extension'));
            $BITsensor->AddDetection($detection);
            
            ?>
    </body>
</html>
