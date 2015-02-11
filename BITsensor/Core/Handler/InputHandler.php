<?php

ProcessIntput($_POST, array('HTTP', 'Post'));
ProcessIntput($_GET, array('HTTP', 'Get'));
ProcessIntput($_COOKIE, array('HTTP', 'Cookie'));

function ProcessIntput($array, $name)
{
    if(!isset($array))
        return;
    
    recursiveProcessing($array, $name);
}

function recursiveProcessing($array, $prepend = array()) {
    foreach ($array as $key => $value)
    {
        $currentName = $prepend;
        array_push($currentName, $key);
        if (is_array($value))
        {
            recursiveProcessing($value, $currentName);
        }
        else
        {   
            generalProcessing($value, $currentName);
        }
    }
}

function generalProcessing($value, $name) {
    global $BITsensor;
    $BITsensor->AddInput(new RequestInput($name, $value));
}