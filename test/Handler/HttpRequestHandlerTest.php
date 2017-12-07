<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\Config;
use BitSensor\Core\EndpointConstants;
use BitSensor\Core\HttpConstants;
use BitSensor\Handler\HttpRequestHandler;
use DateTime;

class HttpRequestHandlerTest extends HandlerTest
{

    public function testHandle()
    {
        $handler = new HttpRequestHandler();
        $handler->handle($this->datapoint, new Config());

        $context = $this->datapoint->getContext();
        $endpoint = $this->datapoint->getEndpoint();

        self::assertEquals('false', $context[HttpConstants::NAME . '.' . HttpConstants::HTTPS]);

        $stringDate = substr($endpoint[EndpointConstants::REQUEST_TIME], 0, 28);
        str_replace('T', ' ', $stringDate);
        $date = new DateTime($stringDate);

        $now = microtime(true);
        self::assertLessThan(2, $now - $date->getTimestamp());

        if (isset($_SERVER['DOCUMENT_ROOT'])) {
            self::assertEquals($_SERVER['DOCUMENT_ROOT'], $endpoint[EndpointConstants::DOCUMENT_ROOT]);
        }
    }

}
