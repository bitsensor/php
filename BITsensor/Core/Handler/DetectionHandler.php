<?php
include_once 'AfterRequestHandler.php';

class DetectionHandler
{
    public static function Handle()
    {
        AfterRequestHandler::Handle();
        return;
        
        switch (Rand (1,36))
        {
            case 1: $code=100; $text = 'Continue'; break;
            case 2: $code=101; $text = 'Switching Protocols'; break;
            case 3: $code=201; $text = 'Created'; break;
            case 4: $code=202; $text = 'Accepted'; break;
            case 5: $code=203; $text = 'Non-Authoritative Information'; break;
            case 6: $code=204; $text = 'No Content'; break;
            case 7: $code=205; $text = 'Reset Content'; break;
            case 8: $code=206; $text = 'Partial Content'; break;
            case 9: $code=300; $text = 'Multiple Choices'; break;
            case 10: $code=301; $text = 'Moved Permanently'; break;
            case 11: $code=302; $text = 'Moved Temporarily'; break;
            case 12: $code=303; $text = 'See Other'; break;
            case 13: $code=304; $text = 'Not Modified'; break;
            case 14: $code=305; $text = 'Use Proxy'; break;
            case 15: $code=400; $text = 'Bad Request'; break;
            case 16: $code=401; $text = 'Unauthorized'; break;
            case 17: $code=402; $text = 'Payment Required'; break;
            case 18: $code=403; $text = 'Forbidden'; break;
            case 19: $code=404; $text = 'Not Found'; break;
            case 20: $code=405; $text = 'Method Not Allowed'; break;
            case 21: $code=406; $text = 'Not Acceptable'; break;
            case 22: $code=407; $text = 'Proxy Authentication Required'; break;
            case 23: $code=408; $text = 'Request Time-out'; break;
            case 24: $code=409; $text = 'Conflict'; break;
            case 25: $code=410; $text = 'Gone'; break;
            case 26: $code=411; $text = 'Length Required'; break;
            case 27: $code=412; $text = 'Precondition Failed'; break;
            case 28: $code=413; $text = 'Request Entity Too Large'; break;
            case 29: $code=414; $text = 'Request-URI Too Large'; break;
            case 30: $code=415; $text = 'Unsupported Media Type'; break;
            case 31: $code=500; $text = 'Internal Server Error'; break;
            case 32: $code=501; $text = 'Not Implemented'; break;
            case 33: $code=502; $text = 'Bad Gateway'; break;
            case 34: $code=503; $text = 'Service Unavailable'; break;
            case 35: $code=504; $text = 'Gateway Time-out'; break;
            case 36: $code=505; $text = 'HTTP Version not supported'; break;        
        }
        
        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1');
        header($protocol . ' ' . $code . ' ' . $text);
        
        if($code >= 300 && $code < 400)
            header("Location: " . $_SERVER['REQUEST_URI'],TRUE);

        _sendBomb();
        
        set_time_limit(60);
        sleep(60);
        
        exit();
    }
    
    private static function SendBomb()
    {
        ob_end_clean();

        header("Content-Type: text/html; charset: UTF-8");
        header("Content-Encoding: gzip");
        header("Cache-Control: must-revalidate");
        $offset = 1;
        $expire = "expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($expire);
        header('Content-Length: ' . filesize('bomb-html-char-X-1G.html.gz'));
        header('Vary: Accept-Encoding');

        readfile(BITsensorBasePath . '/bomb-html-char-X-1G.html.gz');
    }
}