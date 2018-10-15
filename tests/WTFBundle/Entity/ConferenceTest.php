<?php

namespace Tests\WTFBundle\Entity;

use WTFBundle\Entity\Conference;
use PHPUnit\Framework\TestCase;

class ConferenceTest extends TestCase
{
    // …

    public function testConference()
    {
        $conference = new Conference();
        $conference->setTitle("THSF 10");
        $this->assertSame("THSF 10", $conference->getTitle());
        $this->assertEquals('WTFBundle\Entity\Conference', get_class($conference));
    }
}
