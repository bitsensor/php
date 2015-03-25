<?php

class ReportingHandler
{
    public static function Handle()
    {
        global $BITsensor;
        
        //DEBUG OPTION
        //echo('<pre>' . $BITsensor->Serialize(true) . '</pre>');
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"http://bitsensor.bitsaver.nl/api/data/");
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "=" . urlencode(json_encode($BITsensor->Get())));
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec ($ch);
        curl_close ($ch);
    }
}