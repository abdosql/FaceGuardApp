<?php

namespace App\Factory;

use App\Entity\Branch;
use App\Repository\BrancheRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Branch>
 *
 * @method        Branch|Proxy                      create(array|callable $attributes = [])
 * @method static Branch|Proxy                      createOne(array $attributes = [])
 * @method static Branch|Proxy                      find(object|array|mixed $criteria)
 * @method static Branch|Proxy                      findOrCreate(array $attributes)
 * @method static Branch|Proxy                      first(string $sortedField = 'id')
 * @method static Branch|Proxy                      last(string $sortedField = 'id')
 * @method static Branch|Proxy                      random(array $attributes = [])
 * @method static Branch|Proxy                      randomOrCreate(array $attributes = [])
 * @method static BrancheRepository|RepositoryProxy repository()
 * @method static Branch[]|Proxy[]                  all()
 * @method static Branch[]|Proxy[]                  createMany(int $number, array|callable $attributes = [])
 * @method static Branch[]|Proxy[]                  createSequence(iterable|callable $sequence)
 * @method static Branch[]|Proxy[]                  findBy(array $attributes)
 * @method static Branch[]|Proxy[]                  randomRange(int $min, int $max, array $attributes = [])
 * @method static Branch[]|Proxy[]                  randomSet(int $number, array $attributes = [])
 */
final class BranchFactory extends ModelFactory
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
            'branch_name' => self::faker()->text(100),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Branch $branch): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Branch::class;
    }
}
