<?php

namespace App\DataFixtures;

use App\Factory\TrickCategoryFactory;
use App\Factory\TrickFactory;
use App\Factory\TrickImageFactory;
use App\Factory\VideoProviderFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function getTricksArrayFromJson(): array
    {
        $tricksJson = file_get_contents('src/DataFixtures/dataTricks.json');
        return json_decode($tricksJson, true);
    }

    public function getTrickCategories(): array
    {
        return ['Grabs', 'Rotations', 'Slides', 'Jumps', 'Flips'];
    }

    public function getVideoProviders(): array
    {
        return ['Youtube', 'Vimeo', 'Daylimotion'];
    }


    public function load(ObjectManager $manager): void
    {
        $files = glob('public/uploads/tricks/*'); // get all file names
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        // Tricks + Categories
        $list = $this->getTricksArrayFromJson();

        foreach ($list as $key => $value) {
            $category = TrickCategoryFactory::findOrCreate(['name' => $key]);

            foreach ($value as $trick) {
                TrickFactory::createOne(['name' => $trick, 'trickCategory' => $category, 'trickImages' => TrickImageFactory::createMany(3)]);
            }
        }

        // Video Providers
        foreach ($this->getVideoProviders() as $provider) {
            VideoProviderFactory::createOne(['name' => $provider]);
        }

        $manager->flush();
    }
}
