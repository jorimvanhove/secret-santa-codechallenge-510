<?php

use Exception\InvalidMatchException;
use Model\Matcher;
use Model\Participant;
use Model\ParticipantCollection;

class Main
{
    private int $numberOfParticipants;
    private int $maxAllowedNumberOfExcludes;

    public function __construct(int $numberOfParticipants, int $maxAllowedNumberOfExcludes)
    {
        $this->numberOfParticipants = $numberOfParticipants;
        $this->maxAllowedNumberOfExcludes = $maxAllowedNumberOfExcludes;
    }

    public function run(): void
    {
        $rustart = getrusage();

        $participants = $this->ParticipantCollectionFactory();
        $this->AddExcludesRandomized($participants);

        $matcher = new Matcher($participants);
        $matcher->match();

        $this->OutputResult($matcher);

        $ru = getrusage();
        echo "This process used " . $this->rutime($ru, $rustart, "utime") .
            " ms for its computations\n";
        echo "It spent " . $this->rutime($ru, $rustart, "stime") .
            " ms in system calls\n";
    }

    private function AddExcludesRandomized(ParticipantCollection $participants)
    {
        foreach ($participants as $index => $participant)
        {
            $excludes = new ParticipantCollection();
            for ($i = 0 ; $i < $this->maxAllowedNumberOfExcludes; $i++)
            {
                if ($i === random_int(0, $this->maxAllowedNumberOfExcludes))
                {
                    continue;
                }

                do {
                    $randomIndex = random_int(0, $this->numberOfParticipants);
                } while ($randomIndex === $index);

                $participantToExclude = $participants->atIndex($randomIndex);
                if ($participantToExclude !== null && $participantToExclude !== $participant && $excludes->any($participantToExclude) === false){
                    $excludes->addParticipant($participantToExclude);
                }
            }

            if ($excludes->count() > 0){
                $participant->setExcludes($excludes);
            }
        }
    }

    private function ParticipantCollectionFactory()
    {
        $participants = new ParticipantCollection();

        for ($i = 0; $i <= $this->numberOfParticipants; $i++) {
            $participants->addParticipant(new Participant($i));
        }

        return $participants;
    }

    private function OutputResult(Matcher $matcher)
    {
        $matcher->getMatchedParticipants()->unshuffle();
        echo("Matched participants:\n\r");
        foreach ($matcher->getMatchedParticipants() as $participant) {
            if ($participant->getMatch() === null) {
                throw new Exception('Participant was not matched!');
            }

            echo($participant->getId() . ' -> ' . $participant->getMatch()->getId() . "\n\r");
        }

        if ($matcher->getUnmatchedParticipants()->count()){
            $matcher->getUnmatchedParticipants()->unshuffle();
            echo ("\n\r");
            echo("Unmatched participants:\n\r");
            foreach ($matcher->getUnmatchedParticipants() as $participant) {
                echo("No compatible match found for participant " . $participant->getId());

                if ($participant->getExcludes() !== null){
                    echo (" - excludes: ");
                    $tmp = [];
                    foreach($participant->getExcludes() as $exclude)
                    {
                        $tmp[] = $exclude->getId();
                    }
                    echo("[" . implode(',', $tmp) . "]");
                }

                echo ("\n\r");
            }
        }
    }

    public function rutime($ru, $rus, $index) {
        return ($ru["ru_$index.tv_sec"]*1000 + (int)($ru["ru_$index.tv_usec"] / 1000))
            -  ($rus["ru_$index.tv_sec"]*1000 + (int)($rus["ru_$index.tv_usec"] / 1000));
    }
}


