<?php


namespace Model;

use Exception\InvalidMatchException;

class Matcher
{
    /**
     * @var ParticipantCollection
     */
    private ParticipantCollection $matchedParticipants;
    /**
     * @var ParticipantCollection
     */
    private ParticipantCollection $unmatchedParticipants;
    /**
     * @var ParticipantCollection
     */
    private ParticipantCollection $participants;

    public function __construct(ParticipantCollection $participants)
    {
        $this->matchedParticipants = new ParticipantCollection();
        $this->unmatchedParticipants = new ParticipantCollection();
        $this->participants = $participants;
    }

    public function match()
    {
        $this->TryMatchParticipants($this->participants);
        if ($this->unmatchedParticipants->count() > 0) {
            //$this->TryMatchUnmatchedParticipants($this->unmatchedParticipants);
        }
    }

    public function getMatchedParticipants(): ParticipantCollection
    {
        return $this->matchedParticipants;
    }

    public function getUnmatchedParticipants(): ParticipantCollection
    {
        return $this->unmatchedParticipants;
    }


    private function TryMatchParticipants(ParticipantCollection $participants)
    {
        $participants->shuffle();

        /**
         * @var  int $index
         * @var  Participant $participant
         */
        foreach ($participants as $index => $participant) {
            if ($participant->getMatch() !== null) {
                continue;
            }

            if ($participant->getExcludes() !== null &&
                $participant->getExcludes()->count() > 0 &&
                $participant
                    ->getExcludes()
                    ->search($participants->atIndex($index + 1) ?? $participants->reset()) !== false
            )
            {
                $this->unmatchedParticipants->addParticipant($participant);
            } else {
                try {
                    $participant->setMatch($participants->atIndex($index + 1) ?? $participants->reset());
                    $this->matchedParticipants->addParticipant($participant);
                } catch (InvalidMatchException $exception){
                    $this->unmatchedParticipants->addParticipant($participant);
                }
            }
        }

        return $participants;
    }

    private function TryMatchUnmatchedParticipants(ParticipantCollection $participants)
    {
        /**
         * @var  int $index
         * @var  Participant $participant
         */
        foreach ($participants as $index => $participant) {
            if ($participant->getMatch() !== null) {
                continue;
            }

            if ($participant->getExcludes() !== null &&
                $participant->getExcludes()->count() > 0 &&
                $participant
                    ->getExcludes()
                    ->search($participants->atIndex($index + 1) ?? $participants->reset()) !== false
            )
            {
                $this->unmatchedParticipants->addParticipant($participant);
            } else {
                try {
                    $participant->setMatch($participants->atIndex($index + 1) ?? $participants->reset());
                    $this->matchedParticipants->addParticipant($participant);
                } catch (InvalidMatchException $exception){
                    $this->unmatchedParticipants->addParticipant($participant);
                }
            }
        }

        return $participants;
    }

}