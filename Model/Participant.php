<?php
namespace Model;

use Exception\InvalidMatchException;

class Participant
{
    private int $id;
    private ?ParticipantCollection $excludes;
    private ?Participant $match;

    public function __construct(int $id, ?ParticipantCollection $excludes = null)
    {
        $this->id = $id;

        if ($excludes) {
            $this->excludes = $excludes;
        } else {
            $this->excludes = null;
        }

        $this->match = null;
    }

    /**
     * @return Participant|null
     */
    public function getMatch(): ?Participant
    {
        return $this->match;
    }

    /**
     * @param Participant $match
     */
    public function setMatch(Participant $match): void
    {
        if ($match->id === $this->id){
            throw new InvalidMatchException("Participant can not be matched to itself");
        }

        $this->match = $match;
    }

    /**
     * @return ParticipantCollection|null
     */
    public function getExcludes(): ?ParticipantCollection
    {
        return $this->excludes;
    }

    /**
     * @param ParticipantCollection $excludes
     */
    public function setExcludes(ParticipantCollection $excludes): void
    {
        $this->excludes = $excludes;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


}