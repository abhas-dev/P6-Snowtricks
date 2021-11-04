<?php

namespace App\DataFixtures;

use App\Factory\TrickCategoryFactory;
use App\Factory\TrickFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function getTricksArrayFromJson(): array
    {
//        $tricksJson = file_get_contents('src/DataFixtures/dataTricks.json');
        $tricksJson = file_get_contents('src/DataFixtures/dataTricks.json');
        return json_decode($tricksJson, true);
    }

    public function getTrickCategories(): array
    {
        return ['Grabs', 'Rotations', 'Slides', 'Jumps', 'Flips'];
    }

    public function load(ObjectManager $manager): void
    {
        $list = $this->getTricksArrayFromJson();

//        foreach($tricks as $trick)
//        {
//            TrickFactory::createOne(['name' => $trick['name']];
//        }
        foreach ($list as $key => $value) {
            $category = TrickCategoryFactory::findOrCreate(['name' => $key]);

            foreach ($value as $trick) {
                TrickFactory::createOne(['name' => $trick, 'trickCategory' => $category]);
            }
        }



        $manager->flush();
    }
}
