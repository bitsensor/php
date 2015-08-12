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
        $post = array();
        foreach ($_POST as $k => $v) {
            if (is_array($v)) {
                self::flatten($v, $post, $k);
            } else {
                $post[$k] = $v;
            }
        }

        $collector->addInput(new InputContext(InputContext::POST, $post));

        $get = array();
        foreach ($_GET as $k => $v) {
            if (is_array($v)) {
                self::flatten($v, $get, $k);
            } else {
                $get[$k] = $v;
            }
        }

        $collector->addInput(new InputContext(InputContext::GET, $get));

        $cookie = array();
        foreach ($_COOKIE as $k => $v) {
            if (is_array($v)) {
                self::flatten($v, $cookie, $k);
            } else {
                $cookie[$k] = $v;
            }
        }

        $collector->addInput(new InputContext(InputContext::COOKIE, $cookie));
    }

    /**
     * Flattens an array into an other array. After execution <code>$output</code> contains
     * a flattened version of <code>$input</code>.
     *
     * @param array $input The original array.
     * @param array $output The array in which the flattened elements should be placed.
     * @param string $prefix Prefix to add to each element.
     */
    private static function flatten($input, &$output, $prefix) {
        foreach ($input as $k => $v) {
            if (is_array($v)) {
                self::flatten($v, $output, $prefix . "[$k]");
            } else {
                $output[$prefix . "[$k]"] = $v;
            }
        }
    }

}