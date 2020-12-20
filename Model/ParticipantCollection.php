<?php
namespace Model;

use ArrayIterator;
use Iterator;
use IteratorAggregate;

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
        return reset($this->participants);
    }

    public function atIndex(int $index): ?Participant
    {
        return $this->participants[$index] ?? null;
    }

    public function search(Participant $participant)
    {
        return array_search($participant, $this->participants, true);
    }

    public function count()
    {
        return count($this->participants);
    }
    public function current(): Participant
    {
        return $this->participants[$this->index];
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
}