<?php

namespace App\Factory;

use App\Entity\TrickImage;
use App\Repository\TrickImageRepository;
use App\Service\ImageService;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<TrickImage>
 *
 * @method static TrickImage|Proxy createOne(array $attributes = [])
 * @method static TrickImage[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static TrickImage|Proxy find(object|array|mixed $criteria)
 * @method static TrickImage|Proxy findOrCreate(array $attributes)
 * @method static TrickImage|Proxy first(string $sortedField = 'id')
 * @method static TrickImage|Proxy last(string $sortedField = 'id')
 * @method static TrickImage|Proxy random(array $attributes = [])
 * @method static TrickImage|Proxy randomOrCreate(array $attributes = [])
 * @method static TrickImage[]|Proxy[] all()
 * @method static TrickImage[]|Proxy[] findBy(array $attributes)
 * @method static TrickImage[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static TrickImage[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TrickImageRepository|RepositoryProxy repository()
 * @method TrickImage|Proxy create(array|callable $attributes = [])
 */
final class TrickImageFactory extends ModelFactory
{
    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    private function getImages()
    {
        $images = [];
        for($i = 1; $i <= 5 ; $i++)
        {
            $images[] = "snowboard$i.jpg";
        }
        return $images;
    }

    protected function getDefaults(): array
    {
        $randomImage = self::faker()->randomElement($this->getImages());
        // Creation d'une copie de l'image pour ne pas la deplacer elle meme car on utilise move dans le service pour le deplacer du temp a l'upload directory
        $fs = new Filesystem();
        $targetPath = sys_get_temp_dir().'/'.$randomImage;
        $fs->copy(dirname(__DIR__) . '/DataFixtures/images/' . $randomImage, $targetPath, true);

        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'name' => self::faker()->word(),
            'file' => new File($targetPath)
//            'filename' => self::faker()->randomElements()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
             ->afterInstantiate(function(TrickImage $trickImage) {
                 if(!$trickImage->getFilename()){
                     $this->imageService->moveImageToFinalDirectory($trickImage);
                 }
             })
        ;
    }

    protected static function getClass(): string
    {
        return TrickImage::class;
    }
}
