<?php

namespace App\Factory;

use App\Entity\Level;
use App\Repository\LevelRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Level>
 *
 * @method        Level|Proxy                     create(array|callable $attributes = [])
 * @method static Level|Proxy                     createOne(array $attributes = [])
 * @method static Level|Proxy                     find(object|array|mixed $criteria)
 * @method static Level|Proxy                     findOrCreate(array $attributes)
 * @method static Level|Proxy                     first(string $sortedField = 'id')
 * @method static Level|Proxy                     last(string $sortedField = 'id')
 * @method static Level|Proxy                     random(array $attributes = [])
 * @method static Level|Proxy                     randomOrCreate(array $attributes = [])
 * @method static LevelRepository|RepositoryProxy repository()
 * @method static Level[]|Proxy[]                 all()
 * @method static Level[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Level[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Level[]|Proxy[]                 findBy(array $attributes)
 * @method static Level[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Level[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class LevelFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'level_name' => self::faker()->text(100),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Level $level): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Level::class;
    }
}
