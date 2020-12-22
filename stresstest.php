<?php declare(strict_types=1);

// Check PHP version
if (PHP_VERSION_ID < 70400) {
    exit('PHP7.4+ Required');
}

require_once('src/autoload.php');

ini_set('memory_limit','4G');

$loops = 200;

for ($i = 0; $i <= $loops; $i++)
{
    $numberOfParticipants = $i * 100;
    $numberOfExcludes = $i * $i;

    $program = new Main($numberOfParticipants, $numberOfExcludes);

    try
    {
        gc_disable();
        $program->run();
        gc_collect_cycles();
    } catch (Exception $exception)
    {
        echo $exception->__ToString();
    }

    echo ("-------------------------------------");
    echo ("\n\r");
}
