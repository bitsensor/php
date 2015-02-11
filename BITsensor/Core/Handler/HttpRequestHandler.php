<?php
/*@var $BITsensor Collector*/
$BITsensor->AddContext(new Context('IP', new IpRequest($_SERVER)));

$BITsensor->AddContext(new Context(array('Script'), new ScriptRequest($_SERVER)));

$httpRequest = new HttpRequest($_SERVER);
$BITsensor->AddContext(new Context('HTTP', $httpRequest));

if($httpRequest->isHttpAuthenticationRequest())
    $BITsensor->AddContext(new Context('Athentication', $httpRequest->isHttpAuthenticationRequest()));

if($httpRequest->isHttpAuthenticatedRequest())
    $BITsensor->AddContext(new Context('User', $httpRequest->isHttpAuthenticatedRequest()));
    
$BITsensor->AddContext(new Context(array('Server', 'Info'), new ServerInfo($_SERVER)));

//$BITsensor->AddRequest($request);
//$BITsensor->AddInputSet($request->getInput());