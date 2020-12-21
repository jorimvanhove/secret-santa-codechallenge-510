<?php declare(strict_types=1);

// Check PHP version
if (PHP_VERSION_ID < 70400) {
    exit('PHP7.4+ Required');
}

require_once('src/autoload.php');

$numberOfParticipants = 250;
$numberOfExcludes = 4;

$program = new Main($numberOfParticipants, $numberOfExcludes);
$program->run();