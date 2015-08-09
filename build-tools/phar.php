<?php

require_once 'version.php';

if (isset($argv[1])) {

    echo "Building $argv[1] \n";
    echo "Version: " . BITSENSOR_BUILD_VERSION . "\n";

    echo "\n";
    $phar = new Phar($argv[1], 0);
    echo "Adding files...\n";
    $phar->buildFromDirectory('src/');
    echo "Setting default stub...\n";
    $phar->setStub($phar->createDefaultStub('index.php'));

    echo "Adding metadata...\n";
    $phar->setMetadata(array(
        'version' => BITSENSOR_BUILD_VERSION,
        'buildDate' => date('Y-m-d H:i:s'),
        'title' => 'BitSensor PHP Plugin',
        'company' => 'BitSaver'
    ));

    echo "Build finished.\n";

}