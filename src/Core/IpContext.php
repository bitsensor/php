<?php

namespace BitSensor\Core;


/**
 * Information about the ip address of the user.
 * @package BitSensor\Core
 */
class IpContext extends Context {

    public function __construct($value) {
        $this->setName(Context::REMOTE_ADDR);
        $this->setValue($value);
    }

}