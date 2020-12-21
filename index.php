<?php declare(strict_types=1);

// Check PHP version
if (version_compare(phpversion(), '7.4.0', '<')) {
    exit('PHP7.4+ Required');
}

require_once('src/autoload.php');

$program = new Main(9000, 20);
$program->run();