<?php

namespace App\Tests;

use App\Entity\TrickCategory;
use PHPUnit\Framework\TestCase;

class TrickCategoryUnitTest extends TestCase
{
    /** @test */
    public function TrickCategoryIsTrue()
    {
        $trickCategory = new TrickCategory();

        $trickCategory->setName('name')
            ->setSlug('slug');

        $this->assertTrue($trickCategory->getName() === 'name');
        $this->assertTrue($trickCategory->getSlug() === 'slug');
    }

    /** @test */
    public function TrickCategoryIsFalse()
    {
        $trickCategory = new TrickCategory();

        $trickCategory->setName('name')
            ->setSlug('slug');

        $this->assertFalse($trickCategory->getName() === false);
        $this->assertFalse($trickCategory->getSlug() === false);

    }

    /** @test */
    public function TrickCategoryIsEmpty()
    {
        $trickCategory = new TrickCategory();

        $this->assertEmpty($trickCategory->getName());
        $this->assertEmpty($trickCategory->getSlug());


    }
}
