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
require_once 'version.php';

$out = isset($argv[1]) ? $argv[1] : __DIR__ . '/../build/BitSensor.phar';

echo "Building $out\n";
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
$phar->buildFromDirectory( __DIR__ . '/../src/');
echo "done\n";

echo "Setting default stub...";
$phar->setStub($phar->createDefaultStub('index.php', 'index.php'));
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