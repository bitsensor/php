<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\Config;
use BitSensor\Core\Context;
use BitSensor\Core\EndpointContext;
use BitSensor\Core\HttpContext;
use BitSensor\Handler\HttpRequestHandler;
use DateTime;

class HttpRequestHandlerTest extends HandlerTest {

    public function testHandle() {
        $handler = new HttpRequestHandler();
        $handler->handle($this->collector, new Config());

        $contexts = $this->collector->toArray();
        static::assertFalse($contexts[Context::NAME][HttpContext::NAME . '.' . HttpContext::HTTPS]);

        $stringDate = substr($contexts[EndpointContext::NAME][EndpointContext::REQUEST_TIME], 0, 28);
        str_replace('T', ' ', $stringDate);
        $date = new DateTime($stringDate);

        $microtime = $date->format('U.u');
        $now = microtime(true);
        static::assertLessThan(1, $now - $microtime);

        if (isset($_SERVER['DOCUMENT_ROOT'])) {
            static::assertEquals($_SERVER['DOCUMENT_ROOT'], $contexts[EndpointContext::NAME][EndpointContext::DOCUMENT_ROOT]);
        }
    }

}
