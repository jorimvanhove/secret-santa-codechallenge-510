<?php declare(strict_types=1);

use Model\Matcher;
use Model\Participant;
use Model\ParticipantCollection;
use PHPUnit\Framework\TestCase;

final class ParticipantsMatcherTest extends TestCase
{
    public function testCanMatchAllParticipants(): void
    {
        $participants = $this->participantCollectionFactory();
        $matcher = new Matcher($participants);
        $matcher->match(false);
        $unmatchedParticipants = $matcher->getUnmatchedParticipants();
        self::assertCount(0, $unmatchedParticipants);
    }

    public function testCanMatchAnyParticipants(): void
    {
        $participants = $this->participantCollectionFactory();
        $matcher = new Matcher($participants);
        $matcher->match();
        self::assertGreaterThan(0, $matcher->getMatchedParticipants()->count());
    }

    public function testUniqueMatchedReceivers(): void
    {
        $participants = $this->participantCollectionFactory();
        $matcher = new Matcher($participants);
        $matcher->match();
        self::assertGreaterThan(0, $matcher->getMatchedParticipants()->count());
        self::assertTrue($matcher->getMatchedParticipants()->validateUniqueMatches(false));
    }

    private function participantCollectionFactory(): ParticipantCollection
    {
        $participants = new ParticipantCollection();

        $participantA = new Participant(0);
        $participantB = new Participant(1);
        $participantC = new Participant(2);
        $participantD = new Participant(3);
        $participantE = new Participant(4);

        $excludesForA = new ParticipantCollection();
        $excludesForA->addParticipant($participantC);
        $excludesForA->addParticipant($participantD);
        $excludesForA->addParticipant($participantE);
        $participantA->setExcludes($excludesForA);

        $excludesForB = new ParticipantCollection();
        $excludesForB->addParticipant($participantA);
        $excludesForB->addParticipant($participantD);
        $excludesForB->addParticipant($participantE);
        $participantB->setExcludes($excludesForB);

        $excludesForC = new ParticipantCollection();
        $excludesForC->addParticipant($participantA);
        $excludesForC->addParticipant($participantB);
        $excludesForC->addParticipant($participantE);
        $participantC->setExcludes($excludesForC);

        $excludesForD = new ParticipantCollection();
        $excludesForD->addParticipant($participantA);
        $excludesForD->addParticipant($participantB);
        $excludesForD->addParticipant($participantC);
        $participantD->setExcludes($excludesForD);

        $excludesForE = new ParticipantCollection();
        $excludesForE->addParticipant($participantB);
        $excludesForE->addParticipant($participantC);
        $excludesForE->addParticipant($participantD);
        $participantE->setExcludes($excludesForE);

        $participants->addParticipant($participantA);
        $participants->addParticipant($participantB);
        $participants->addParticipant($participantC);
        $participants->addParticipant($participantD);
        $participants->addParticipant($participantE);

        return $participants;
    }
}