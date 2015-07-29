<?php

namespace BitSensor\Core;


class ScriptContext extends Context {

    public function __construct() {
        $this->setName(Context::SCRIPT_FILENAME);
        $this->setValue($_SERVER['SCRIPT_FILENAME']);
    }

}