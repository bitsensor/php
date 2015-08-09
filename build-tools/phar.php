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
        'title' => 'BitSensor Web Application Security',
        'company' => 'BitSaver'
    ));

    echo "Compressing archive...\n";
    $phar->compress(Phar::BZ2);
    $phar->compress(Phar::GZ);

    echo "Build finished.\n";

}