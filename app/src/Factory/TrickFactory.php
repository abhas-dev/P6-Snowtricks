<?php

namespace App\Factory;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Trick>
 *
 * @method static Trick|Proxy createOne(array $attributes = [])
 * @method static Trick[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Trick|Proxy find(object|array|mixed $criteria)
 * @method static Trick|Proxy findOrCreate(array $attributes)
 * @method static Trick|Proxy first(string $sortedField = 'id')
 * @method static Trick|Proxy last(string $sortedField = 'id')
 * @method static Trick|Proxy random(array $attributes = [])
 * @method static Trick|Proxy randomOrCreate(array $attributes = [])
 * @method static Trick[]|Proxy[] all()
 * @method static Trick[]|Proxy[] findBy(array $attributes)
 * @method static Trick[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Trick[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TrickRepository|RepositoryProxy repository()
 * @method Trick|Proxy create(array|callable $attributes = [])
 */
final class TrickFactory extends ModelFactory
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
            'name' => self::faker()->sentence(2),
            'description' => self::faker()->paragraphs(
                self::faker()->numberBetween(1, 4),
                true
            ),
            'createdAt' => self::faker()->dateTimeBetween('-3 month', 'now'), // TODO add DATETIME ORM type manually
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
             ->afterInstantiate(function(Trick $trick) {
                 if(!$trick->getSlug()){
                     $trick->setSlug(strtolower($this->slugger->slug($trick->getName())));
                 }
             })
        ;
    }

    protected static function getClass(): string
    {
        return Trick::class;
    }
}
