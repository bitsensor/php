<?php

namespace BitSensor\View;


/**
 * Page to shown when warning an identified attacker.
 * @package BitSensor\View
 */
class TamperView extends View {

    public function __construct() {
        $this->setContent(file_get_contents(__DIR__ . '/Content/tamper.html'));
    }

}