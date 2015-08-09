<?php

namespace BitSensor\View;


class TamperView extends View {

    public function __construct() {
        $this->setContent(file_get_contents(__DIR__ . '/Content/tamper.html'));
    }

}