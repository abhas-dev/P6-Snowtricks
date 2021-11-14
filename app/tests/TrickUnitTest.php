<?php

namespace App\Tests;

use App\Entity\Trick;
use App\Entity\TrickCategory;
use DateTime;
use PHPUnit\Framework\TestCase;

class TrickUnitTest extends TestCase
{
    /** @test */
    public function TrickIsTrue()
    {
        $trick = new Trick();
        $datetime = DateTime::createFromFormat('U', time());
        $trickCategory = new TrickCategory();

        $trick->setName('name')
            ->setSlug('slug')
            ->setDescription('trick is true')
            ->setTrickCategory($trickCategory)
            ->setCreatedAt($datetime)
            ->setUpdatedAt($datetime);

        $this->assertTrue($trick->getName() === 'name');
        $this->assertTrue($trick->getSlug() === 'slug');
        $this->assertTrue($trick->getDescription() === 'trick is true');
        $this->assertTrue($trick->getTrickCategory() === $trickCategory);
        $this->assertTrue($trick->getCreatedAt() === $datetime);
        $this->assertTrue($trick->getUpdatedAt() === $datetime);
    }

    /** @test */
    public function TrickIsFalse()
    {
        $trick = new Trick();

        $trick->setName('name')
            ->setSlug('slug')
            ->setDescription('trick is true')
            ->setTrickCategory(new TrickCategory())
            ->setCreatedAt(DateTime::createFromFormat('U', time()) )
            ->setUpdatedAt(DateTime::createFromFormat('U', time()) );

        $this->assertFalse($trick->getName() === false);
        $this->assertFalse($trick->getSlug() === false);
        $this->assertFalse($trick->getDescription() === false);
        $this->assertFalse($trick->getTrickCategory() === false);
        $this->assertFalse($trick->getCreatedAt() === false);
        $this->assertFalse($trick->getUpdatedAt() === false);

    }

    /** @test */
    public function TrickIsEmpty()
    {
        $trick = new Trick();

        $this->assertEmpty($trick->getName());
        $this->assertEmpty($trick->getSlug());
        $this->assertEmpty($trick->getDescription());
        $this->assertEmpty($trick->getTrickCategory());
        $this->assertEmpty($trick->getCreatedAt());
        $this->assertEmpty($trick->getUpdatedAt());
    }
}
