<?php
/**
 * Creates a PHP Archive (phar) from the current source code.
 *
 * Usage:
 * <code>
 * php build-tools/phar.php [output-file]
 * </code>
 */
?>
<?php
require_once dirname(__DIR__) . '/src/index.php';

$out = isset($argv[1]) ? $argv[1] : dirname(__DIR__) . '/target/bitsensor.phar';

echo "Building $out\n";
echo "Version: " . \BitSensor\Core\BitSensor::VERSION . "\n";

echo "\n";

echo "Checking configuration:\n";
$configErrors = array();

echo "Writing phar files allowed? ";
$parReadOnly = ini_get('phar.readonly');
if ($parReadOnly === '1') {
    echo "\033[31m" . "no" . "\033[0m" . "\n";
    $configErrors[] = "phar.readonly must be set to `Off` in your php.ini CLI configuration.\nPlease set phar.readonly = Off\nYou might find php.ini in /etc/php5/cli/php.ini\n";
} else {
    echo "\033[32m" . "yes" . "\033[0m" . "\n";
}

echo "\n";

if (count($configErrors) > 0) {
    echo "Configuration errors:\n";

    foreach ($configErrors as $configError) {
        echo "----\n";
        echo $configError;
    }

    echo "----\n";
    echo "\n";

    exit("\033[31m" . "Configuration check failed with " . count($configErrors) . " errors" . "\033[0m" . "\n");
}

echo "\033[32m" . "Configuration check passed" . "\033[0m" . "\n";
echo "\n";

if (!is_dir(dirname($out))) {
    mkdir(dirname($out));
}

echo "Removing old archives...";
@unlink($out);
//@unlink($out . '.bz2');
@unlink($out . '.gz');
echo "done\n";

echo "Creating new archive...";
$phar = new Phar($out, 0);
echo "done\n";

echo "Adding files...";
$phar->buildFromDirectory(__DIR__ . '/../src/');
echo "done\n";

echo "Setting default stub...";
$phar->setStub(Phar::createDefaultStub('index.php', 'index.php'));
echo "done\n";

echo "Adding metadata...";
$phar->setMetadata(array(
    'version' => \BitSensor\Core\BitSensor::VERSION,
    'buildDate' => date('Y-m-d H:i:s'),
    'title' => 'BitSensor Web Application Security',
    'vendor' => 'BitSensor',
    'support' => 'support@bitsensor.io'
));
echo "done\n";

echo "Compressing archive...";
//$phar->compress(Phar::BZ2);
$phar->compress(Phar::GZ);
echo "done\n";

echo "Generating checksums...";
generateChecksum($out);
//generateChecksum($out . '.bz2');
generateChecksum($out . '.gz');

echo "done\n";

echo "Build finished.\n";

function generateChecksum($file) {
    $checksumfile = fopen($file . '.sha1.txt', 'w');
    $checksum = sha1_file($file);
    fwrite($checksumfile, $checksum . '  ' . basename($file));
    fclose($checksumfile);
}
