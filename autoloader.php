<?php
use Exception\InvalidMatchException;
use Model\Matcher;
use Model\Participant;
use Model\ParticipantCollection;

spl_autoload(Main::class);
spl_autoload(Participant::class);
spl_autoload(ParticipantCollection::class);
spl_autoload(Matcher::class);
spl_autoload(InvalidMatchException::class);