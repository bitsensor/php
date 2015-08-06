<?php

namespace BitSensor\Core;


class AuthenticatedContext extends Context {

    const REMOTE_USER = 'user';

    public function __construct() {
        $this->setName(Context::AUTH);
        $this->setValue(array(
            self::REMOTE_USER => $_SERVER['REMOTE_USER']
        ));
    }


}