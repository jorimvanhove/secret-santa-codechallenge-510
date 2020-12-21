<?php


namespace Model;

use Exception\InvalidMatchException;
use Exception\UnresolvedMatchException;

class Matcher
{
    private ParticipantCollection $matchedParticipants;
    private ParticipantCollection $unmatchedParticipants;
    private ParticipantCollection $participants;
    private ParticipantCollection $receivers;

    public function __construct(ParticipantCollection $participants)
    {
        $this->matchedParticipants = new ParticipantCollection();
        $this->unmatchedParticipants = new ParticipantCollection();

        $this->participants = $participants;

        $this->receivers = new ParticipantCollection();
        foreach($participants as $participant){
            $this->receivers->addParticipant($participant);
        }
    }

    /**
     * @param bool $shuffleParticipants
     * @param bool $validateUniqueMatches
     * @param bool $throwOnUnresolved
     * @throws InvalidMatchException
     * @throws UnresolvedMatchException
     */
    public function match(bool $shuffleParticipants = true, bool $validateUniqueMatches = true, bool $throwOnUnresolved = false)
    {
        if ($shuffleParticipants)
        {
            $this->participants->shuffle();
        }

        $this->TryMatchParticipants($this->participants);
        if ($this->receivers->count() && $this->unmatchedParticipants->count())
        {
            $this->unmatchedParticipants->shuffle();
            $this->TryMatchParticipants($this->unmatchedParticipants);
        }

        if ($validateUniqueMatches)
        {
            $this->matchedParticipants->validateUniqueMatches();
        }

        if ($throwOnUnresolved && $this->unmatchedParticipants->count())
        {
            throw new UnresolvedMatchException("Some participants could not be matched", $this->unmatchedParticipants);
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

    private function simpleMatchParticipants(ParticipantCollection $participants): void
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
                    ->any($participants->atIndex($index + 1) ?? $participants->reset()) !== false
            )
            {
                continue;
            }

            try {
                $participant->setMatch($participants->atIndex($index + 1) ?? $participants->reset());
                $this->matchedParticipants->addParticipant($participant);
                $this->receivers->remove($participant->getMatch());
            } catch (InvalidMatchException $exception){
                continue;
            }
        }
    }

    private function TryMatchParticipants(ParticipantCollection $participants)
    {
        foreach ($participants as $participant)
        {
            if ($this->matchedParticipants->any($participant) !== false)
            {
                // Already matched this participant
                $this->unmatchedParticipants->remove($participant);
                continue;
            }

            $this->receivers->rewind();
            if ($this->receivers->current() === null && $this->receivers->count() === 0) {
                // We ran out of receivers
                return;
            }

            while($this->receivers->current() === $participant)
            {
                $this->receivers->next();
            }

            $potentialMatch = $this->receivers->current();

            if ($potentialMatch === null) {
                if ($this->unmatchedParticipants->any($participant) === false)
                {
                    $this->unmatchedParticipants->addParticipant($participant);
                }
                continue;
            }

            if ($participant->getExcludes() !== null && $participant->getExcludes()->any($potentialMatch) !== false)
            {
                $this->receivers->next();
                $potentialMatch = $this->receivers->current();

                if ($potentialMatch === null) {
                    if ($this->unmatchedParticipants->any($participant) === false)
                    {
                        $this->unmatchedParticipants->addParticipant($participant);
                    }
                    continue;
                }
            }

            try
            {
                $participant->setMatch($potentialMatch);
                $this->matchedParticipants->addParticipant($participant);

                if ($this->unmatchedParticipants->any($participant) !== false)
                {
                    $this->unmatchedParticipants->remove($participant);
                }
                $this->receivers->remove($potentialMatch);
            }
            catch (InvalidMatchException $exception)
            {
                if ($this->unmatchedParticipants->any($participant) === false)
                {
                    $this->unmatchedParticipants->addParticipant($participant);
                }
            }
        }
    }
}