<?php

namespace BitSensor\Handler;


use BitSensor\Core\FileContext;
use BitSensor\Core\InputContext;
use BitSensor\Core\SessionContext;
use Proto\Datapoint;

/**
 * Collects information about the HTTP request data.
 * @package BitSensor\Handler
 */
class RequestInputHandler extends AbstractHandler
{

    /**
     * @param Datapoint $datapoint
     */
    public function doHandle(Datapoint $datapoint)
    {
        $post = array();
        foreach ($_POST as $k => $v) {
            if (is_array($v)) {
                self::flatten($v, $post, $k);
            } else {
                $post[$k] = $v;
            }
        }

        foreach ($post as $k => $v) {
            $datapoint->getInput()[InputContext::POST . '.' . $k] = $v;
        }

        $get = array();
        foreach ($_GET as $k => $v) {
            if (is_array($v)) {
                self::flatten($v, $get, $k);
            } else {
                $get[$k] = $v;
            }
        }

        foreach ($get as $k => $v) {
            $datapoint->getInput()[InputContext::GET . '.' . $k] = $v;
        }

        $cookie = array();
        foreach ($_COOKIE as $k => $v) {
            if (is_array($v)) {
                self::flatten($v, $cookie, $k);
            } else {
                $cookie[$k] = $v;
            }
        }

        foreach ($cookie as $k => $v) {
            if ($k === 'PHPSESSID') {
                $datapoint->getContext()['php.' . SessionContext::NAME . '.' . SessionContext::SESSION_ID] = $v;
            } else {
                $datapoint->getInput()[InputContext::COOKIE . '.' . $k] = $v;
            }
        }

        $files = array();
        foreach ($_FILES as $k => $v) {
            if (is_array($v)) {
                self::flatten($v, $files, $k);
            } else {
                $files[$k] = $v;
            }
        }

        foreach ($files as $k => $v) {
            $datapoint->getInput()[FileContext::NAME . '.' . $k] = $v;
        }
    }

    /**
     * Flattens an array into an other array. After execution <code>$output</code> contains
     * a flattened version of <code>$input</code>.
     *
     * @param array $input The original array.
     * @param array $output The array in which the flattened elements should be placed.
     * @param string $prefix Prefix to add to each element.
     */
    public static function flatten($input, &$output, $prefix)
    {
        foreach ($input as $k => $v) {
            if (is_array($v)) {
                self::flatten($v, $output, $prefix . ($prefix !== '' ? '.' : '') . $k);
            } else {
                $output[$prefix . ($prefix !== '' ? '.' : '') . $k] = $v;
            }
        }
    }
}