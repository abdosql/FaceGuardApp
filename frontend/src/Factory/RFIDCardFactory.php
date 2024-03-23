<?php

namespace App\Factory;

use App\Entity\RFIDCard;
use App\Repository\RFIDCardRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<RFIDCard>
 *
 * @method        RFIDCard|Proxy                     create(array|callable $attributes = [])
 * @method static RFIDCard|Proxy                     createOne(array $attributes = [])
 * @method static RFIDCard|Proxy                     find(object|array|mixed $criteria)
 * @method static RFIDCard|Proxy                     findOrCreate(array $attributes)
 * @method static RFIDCard|Proxy                     first(string $sortedField = 'id')
 * @method static RFIDCard|Proxy                     last(string $sortedField = 'id')
 * @method static RFIDCard|Proxy                     random(array $attributes = [])
 * @method static RFIDCard|Proxy                     randomOrCreate(array $attributes = [])
 * @method static RFIDCardRepository|RepositoryProxy repository()
 * @method static RFIDCard[]|Proxy[]                 all()
 * @method static RFIDCard[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static RFIDCard[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static RFIDCard[]|Proxy[]                 findBy(array $attributes)
 * @method static RFIDCard[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static RFIDCard[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class RFIDCardFactory extends ModelFactory
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
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(RFIDCard $rFIDCard): void {})
        ;
    }

    protected static function getClass(): string
    {
        return RFIDCard::class;
    }
}
