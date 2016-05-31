<?php

namespace BitSensor\Test\Core;


use BitSensor\Core\ApiConnector;

class ApiConnectorTest extends \PHPUnit_Framework_TestCase {

    public function testConstruction() {
        $user = 'test_user';
        $apiKey = 'test_key';
        $uri = 'http://localhost';
        $data = array();

        $connector = ApiConnector::from($user, $apiKey)
            ->with($data)
            ->to($uri)
            ->post(ApiConnector::ACTION_LOG);

        static::assertTrue($connector instanceof ApiConnector);
    }

}
