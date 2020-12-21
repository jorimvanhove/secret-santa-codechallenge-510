<?php
namespace Model;

use Exception\InvalidMatchException;
use Exception\ParticipantNotFoundException;
use Iterator;

class ParticipantCollection implements Iterator, \Countable
{
    private int $index;
    /** @var Participant[] */
    private array $participants;

    function __construct() {
        $this->index = 0;
        $this->participants = array();
    }

    public function addParticipant(Participant $participant)
    {
        $this->participants[] = $participant;
    }

    public function shuffle()
    {
        shuffle($this->participants);
    }

    public function unshuffle()
    {
        sort($this->participants);
    }

    public function append(ParticipantCollection $participants)
    {
        $this->participants = array_merge($this->participants, $participants);
    }

    public function reset(): Participant
    {
        $this->index = 0;
        return $this->participants[0] ?? false;
    }

    public function atIndex(int $index): ?Participant
    {
        return $this->participants[$index] ?? null;
    }

    public function any(Participant $participant)
    {
        return array_search($participant, $this->participants, true);
    }

    public function first(int $participantId): ?Participant
    {
        foreach($this->participants as $participant){
            if ($participant->getId() === $participantId){
                return $participant;
            }
        }

        return null;
    }

    public function count(): int
    {
        return count($this->participants);
    }

    public function remove(Participant $participant): void
    {
        $index = $this->any($participant);
        if ($index !== false)
        {
            array_splice($this->participants, $index, 1);
        }
    }

    public function current(): ?Participant
    {
        return $this->participants[$this->index] ?? null;
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return isset($this->participants[$this->index]);
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * Validation fails when a receiver is match to a giver more than once
     * Not every participant has to be matched
    */
    public function validateUniqueMatches(bool $throwExceptionOnFail = true): bool
    {
        /** @var int[] */
        $receivers = [];

        foreach($this->participants as $participant)
        {
            if ($participant->getMatch() !== null)
            {
                if (in_array($participant->getMatch()->getId(), $receivers, true))
                {
                    if ($throwExceptionOnFail)
                    {
                        throw new InvalidMatchException("Receiver matched to more than 1 participant");
                    }
                    return false;

                }
                $receivers[] = $participant->getMatch()->getId();
            }
        }

        return true;
    }

    public function dumpAsJson(): void
    {
        var_dump(json_encode(array_map(static function ($v) {
                        return $v->getId();
                    },
                    $this->participants))
        );
    }
}