<?php

require_once 'version.php';

if (isset($argv[1])) {

    $out = $argv[1];

    echo "Building $out \n";
    echo "Version: " . BITSENSOR_BUILD_VERSION . "\n";

    echo "\n";

    echo "Removing old archives...";
    @unlink($out);
    @unlink($out . '.bz2');
    @unlink($out . '.gz');
    echo "done\n";

    echo "Creating new archive...";
    $phar = new Phar($out, 0);
    echo "done\n";

    echo "Adding files...";
    $phar->buildFromDirectory('src/');
    echo "done\n";

    echo "Setting default stub...";
    $phar->setStub($phar->createDefaultStub('index.php'));
    echo "done\n";

    echo "Adding metadata...";
    $phar->setMetadata(array(
        'version' => BITSENSOR_BUILD_VERSION,
        'buildDate' => date('Y-m-d H:i:s'),
        'title' => 'BitSensor Web Application Security',
        'company' => 'BitSaver'
    ));
    echo "done\n";

    echo "Compressing archive...";
    $phar->compress(Phar::BZ2);
    $phar->compress(Phar::GZ);
    echo "done\n";

    echo "Build finished.\n";

}