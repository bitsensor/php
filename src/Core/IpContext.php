<?php

namespace BitSensor\Core;


class IpContext extends Context {

    public function __construct() {
        $this->setName(Context::REMOTE_ADDR);
        $this->setValue($_SERVER['REMOTE_ADDR']);
    }

}