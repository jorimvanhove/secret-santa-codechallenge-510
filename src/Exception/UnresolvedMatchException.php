<?php


namespace Exception;


use Model\ParticipantCollection;

class UnresolvedMatchException extends \Exception
{

    private ParticipantCollection $unresolvedParticipants;

    /**
     * UnresolvedMatchException constructor.
     * @param string $message
     * @param ParticipantCollection $unresolvedParticipants
     */
    public function __construct(string $message, ParticipantCollection $unresolvedParticipants)
    {
        parent::__construct($message);
        $this->unresolvedParticipants = $unresolvedParticipants;
    }

    public function getUnresolvedParticipants() : ParticipantCollection
    {
        return $this->unresolvedParticipants;
    }

    public function __toString(): string
    {
        $string = get_class($this);
        $string .= "\n\r";

        $this->unresolvedParticipants->unshuffle();

        foreach ($this->unresolvedParticipants as $participant) {
            $string .= "No compatible match found for participant " . $participant->getId();

            if ($participant->getExcludes() !== null) {
                $string .= " - excludes: ";
                $tmp = [];
                foreach ($participant->getExcludes() as $exclude) {
                    $tmp[] = $exclude->getId();
                }
                $string .= "[" . implode(',', $tmp) . "]";
            }

            $string .= "\n\r";
        }

        return $string;
    }
}