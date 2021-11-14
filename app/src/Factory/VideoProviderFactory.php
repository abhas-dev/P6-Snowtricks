<?php

namespace App\Factory;

use App\Entity\VideoProvider;
use App\Repository\VideoProviderRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<VideoProvider>
 *
 * @method static VideoProvider|Proxy createOne(array $attributes = [])
 * @method static VideoProvider[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static VideoProvider|Proxy find(object|array|mixed $criteria)
 * @method static VideoProvider|Proxy findOrCreate(array $attributes)
 * @method static VideoProvider|Proxy first(string $sortedField = 'id')
 * @method static VideoProvider|Proxy last(string $sortedField = 'id')
 * @method static VideoProvider|Proxy random(array $attributes = [])
 * @method static VideoProvider|Proxy randomOrCreate(array $attributes = [])
 * @method static VideoProvider[]|Proxy[] all()
 * @method static VideoProvider[]|Proxy[] findBy(array $attributes)
 * @method static VideoProvider[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static VideoProvider[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static VideoProviderRepository|RepositoryProxy repository()
 * @method VideoProvider|Proxy create(array|callable $attributes = [])
 */
final class VideoProviderFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
//            'name' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(VideoProvider $videoProvider) {})
        ;
    }

    protected static function getClass(): string
    {
        return VideoProvider::class;
    }
}
