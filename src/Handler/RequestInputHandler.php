<?php

namespace BitSensor\Handler;


use BitSensor\Core\Collector;
use BitSensor\Core\InputContext;

/**
 * Collects information about the HTTP request data.
 * @package BitSensor\Handler
 */
class RequestInputHandler {

    /**
     * @param Collector $collector
     */
    public static function handle(Collector $collector) {
        $collector->addInput(new InputContext(InputContext::POST, $_POST));

        $get = array();
        foreach ($_GET as $k => $v) {
            if (is_array($v)) {
                self::flatten($v, $get[$k]);
            } else {
                $get[$k] = $v;
            }
        }
        $collector->addInput(new InputContext(InputContext::GET, $get));

        $collector->addInput(new InputContext(InputContext::COOKIE, $_COOKIE));
    }

    /**
     * Flattens an array into an other array. After execution <code>$output</code> contains
     * a flattened version of <code>$input</code>.
     *
     * @param array $input The original array.
     * @param array $output The array in which the flattened elements should be placed.
     */
    private static function flatten($input, &$output) {
        foreach ($input as $entry) {
            if (is_array($entry)) {
                self::flatten($entry, $output);
            } else {
                $output[] = $entry;
            }
        }
    }

}