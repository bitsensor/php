<?php

namespace BITsensor\Core;


class AuthenticatedContext extends Context {

    const REMOTE_USER = 'User';

    public function __construct() {
        $this->setName(Context::AUTH);
        $this->setValue(array(
            self::REMOTE_USER => $_SERVER['REMOTE_USER']
        ));
    }


}