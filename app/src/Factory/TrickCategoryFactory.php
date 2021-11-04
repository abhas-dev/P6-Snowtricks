<?php

namespace App\Factory;

use App\Entity\TrickCategory;
use App\Repository\TrickCategoryRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<TrickCategory>
 *
 * @method static TrickCategory|Proxy createOne(array $attributes = [])
 * @method static TrickCategory[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static TrickCategory|Proxy find(object|array|mixed $criteria)
 * @method static TrickCategory|Proxy findOrCreate(array $attributes)
 * @method static TrickCategory|Proxy first(string $sortedField = 'id')
 * @method static TrickCategory|Proxy last(string $sortedField = 'id')
 * @method static TrickCategory|Proxy random(array $attributes = [])
 * @method static TrickCategory|Proxy randomOrCreate(array $attributes = [])
 * @method static TrickCategory[]|Proxy[] all()
 * @method static TrickCategory[]|Proxy[] findBy(array $attributes)
 * @method static TrickCategory[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static TrickCategory[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TrickCategoryRepository|RepositoryProxy repository()
 * @method TrickCategory|Proxy create(array|callable $attributes = [])
 */
final class TrickCategoryFactory extends ModelFactory
{
    protected SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        parent::__construct();
        $this->slugger = $slugger;
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'name' => self::faker()->sentence(2)
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
             ->afterInstantiate(function(TrickCategory $trickCategory) {
                 if(!$trickCategory->getSlug()){
                     $trickCategory->setSlug(strtolower($this->slugger->slug($trickCategory->getName())));
                 }
             })
        ;
    }

    protected static function getClass(): string
    {
        return TrickCategory::class;
    }
}
