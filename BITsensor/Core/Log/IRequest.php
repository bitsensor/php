<?php
include_once  BITsensorBasePath . 'Core/Lib/Lib.php';
include_once  BITsensorBasePath . 'Core/Log/IContext.php';

abstract class IScriptRequest extends autoParse implements IContextable
{
    public $SCRIPT_FILENAME;
    
    public function toContext()
    {           
        return LibRename::Rename($this, array(
            'SCRIPT_FILENAME' => 'Path'
        ));
    }
}

abstract class IIpRequest extends autoParse implements IContextable 
{
     public  $REMOTE_ADDR;
     
     public function toContext()
     {
        return LibRename::Rename($this, array (
            'REMOTE_ADDR' => 'Address'));
     }
}

abstract class IHttpRequest extends autoParse implements IContextable
{
    public $SERVER_PROTOCOL, $QUERY_STRING,
            $HTTP_USER_AGENT, $HTTP_REFERER,
            $REQUEST_METHOD, $REQUEST_TIME,
            $HTTP_ACCEPT, $HTTP_ACCEPT_CHARSET, $HTTP_ACCEPT_ENCODING,  $HTTP_ACCEPT_LANGUAGE, 
            $REQUEST_URI, $PATH_INFO,
            $HTTPS, $PHP_AUTH_DIGEST;
    
    public function autoParseFinalization()
    {
        $this->HTTPS = ($this->isHttps()) ? 'True' : 'False';
        $this->PHP_AUTH_DIGEST = ($this->isAuthRequest()) ? 'True' : 'False';
    }
    
    public function isAuthRequest()
    {
        return ($this->PHP_AUTH_DIGEST == "Authorization");
    }
    
    public function isHttps()
    {
        return (!(($this->HTTPS == "off") || ($this->HTTPS == "")));
    }
    
    public function isHttpAuthenticationRequest()
    {
        $httpAuthenticationRequest = new httpAuthenticationRequest($this->RAW_CONSTRUCTOR_ARRAY);
        
        if($httpAuthenticationRequest->PHP_AUTH_USER != '')
            return $httpAuthenticationRequest;
        else
            return false;
    }
    
    public function isHttpAuthenticatedRequest()
    {
        $httpAuthenticatedRequest = new HttpAuthenticatedRequest($this->RAW_CONSTRUCTOR_ARRAY);
        
        if(isset($httpAuthenticatedRequest->REMOTE_USER))
            return $httpAuthenticatedRequest;
        else
            return false;
    }
    
    public function toContext()
    {
        return LibRename::Rename($this, array(
            'SERVER_PROTOCOL' => 'Version',
            'QUERY_STRING' => 'Query',
            'HTTP_USER_AGENT' => 'User-Agent',
            'HTTP_REFERER' => 'Referrer',
            'REQUEST_METHOD' => 'Type',
            'REQUEST_TIME' => 'Localtime-Unix',
            'HTTP_ACCEPT' => 'Accept-Media',
            'HTTP_ACCEPT_CHARSET' => 'Accept-Charset',
            'HTTP_ACCEPT_ENCODING' => 'Accept-Encoding',
            'HTTP_ACCEPT_LANGUAGE' => 'Accept-Language',
            'REQUEST_URI' => 'Uri',
            'PATH_INFO' => 'PathInfo',
            'HTTPS' => 'HTTPS'
        ));
    }
}

abstract class IHttpAuthenticationRequest extends autoParse implements IContextable{
    public $PHP_AUTH_USER, $PHP_AUTH_PW, $AUTH_TYPE;
    
    public function toContext()
    {
        return LibRename::Rename($this, array(
            'PHP_AUTH_USER' => 'Username',
            'PHP_AUTH_PW' => 'Password',
            'AUTH_TYPE' => 'Type',
        ));
     }
}

abstract class IHttpAuthenticatedRequest extends autoParse implements IContextable{
    public $REMOTE_USER;
    
    public function toContext()
    {
        return LibRename::Rename($this, array(
            'REMOTE_USER' => 'Remote User'
        ));
    }
}

abstract class IServerInfo extends autoParse implements IContextable{
    public $SERVER_ADDR, $SERVER_NAME, $SERVER_SOFTWARE, $SERVER_SIGNATURE, $SERVER_PORT;
    
    public function toContext()
    {
        return LibRename::Rename($this, array(
            'SERVER_ADDR' => 'Address',
            'SERVER_NAME' => 'Name',
            'SERVER_SOFTWARE' => 'Software',
            'SERVER_SIGNATURE' => 'Signature',
            'SERVER_PORT' => 'Port',
        ));
     }
}

class IpRequest extends IIpRequest {}
class ScriptRequest extends IScriptRequest {}
class HttpRequest extends IHttpRequest {}
class ServerInfo extends IServerInfo {}
class HttpAuthenticationRequest extends IHttpAuthenticationRequest {}
class HttpAuthenticatedRequest extends IHttpAuthenticatedRequest {}