<?php

class RequestInputHandler {

    public static function Handle() {
        RequestInputHandler::ProcessIntput($_POST, array('HTTP', 'Post'));
        RequestInputHandler::ProcessIntput($_GET, array('HTTP', 'Get'));
        RequestInputHandler::ProcessIntput($_COOKIE, array('HTTP', 'Cookie'));
    }

    private static function ProcessIntput($value, $name) {
        if (!isset($value))
            return;

        RequestInputHandler::recursiveProcessing($value, $name);
    }

    private static function recursiveProcessing($array, $prepend = array()) {
        foreach ($array as $key => $value) {
            $currentName = $prepend;
            array_push($currentName, $key);
            if (is_array($value)) {
                RequestInputHandler::recursiveProcessing($value, $currentName);
            } else {
                RequestInputHandler::generalProcessing($value, $currentName);
            }
        }
    }

    private static function generalProcessing($value, $name) {
        global $BITsensor;
        $BITsensor->AddInput(new RequestInput($name, $value));
    }

}
