<?php
namespace BITsensor\Core\Handler;

use BITsensor\Core\Context;
use BITsensor\Core\HttpRequest;
use BITsensor\Core\IpRequest;
use BITsensor\Core\ScriptRequest;
use BITsensor\Core\ServerInfo; 
use BITsensor\Core\PathInfo;  
use BITsensor\Core\RequestInfo;  
use BITsensor\Core\UserInfo; 

class HttpRequestHandler {
    public static function Handle() {
        /*@var $BITsensor Collector*/
        global $BITsensor;
        return;
//        echo '<pre>';
//        
//        print_r (new Context(array('Server'), new ServerInfo($_SERVER)));
//        print_r (new Context(array('User'), new UserInfo($_SERVER)));
//        print_r (new Context(array('Info'), new RequestInfo($_SERVER)));
//        print_r (new Context(array('Path'), new PathInfo($_SERVER)));
//        
        
        $BITsensor->AddContext(new Context('IP', new IpRequest($_SERVER)));
        $BITsensor->AddContext(new Context(array('Script'), new ScriptRequest($_SERVER)));

        $httpRequest = new HttpRequest($_SERVER);
        $BITsensor->AddContext(new Context('HTTP', $httpRequest));

        if ($httpRequest->isHttpAuthenticationRequest())
            $BITsensor->AddContext(new Context('Athentication', $httpRequest->isHttpAuthenticationRequest()));

        if ($httpRequest->isHttpAuthenticatedRequest())
            $BITsensor->AddContext(new Context('User', $httpRequest->isHttpAuthenticatedRequest()));

        $BITsensor->AddContext(new Context(array('Server', 'Info'), new ServerInfo($_SERVER)));

    }
}


