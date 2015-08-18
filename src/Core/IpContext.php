<?php

namespace BitSensor\Core;


/**
 * Information about the ip address of the user.
 * @package BitSensor\Core
 */
class IpContext extends Context {

    /**
     * IP address of the connecting user.
     */
    const NAME = 'ip';

    public function __construct($value) {
        $this->setName(self::NAME);
        $this->setValue($value);
    }

}