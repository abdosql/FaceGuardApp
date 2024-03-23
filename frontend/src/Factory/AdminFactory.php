<?php

namespace App\Factory;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Admin>
 *
 * @method        Admin|Proxy                     create(array|callable $attributes = [])
 * @method static Admin|Proxy                     createOne(array $attributes = [])
 * @method static Admin|Proxy                     find(object|array|mixed $criteria)
 * @method static Admin|Proxy                     findOrCreate(array $attributes)
 * @method static Admin|Proxy                     first(string $sortedField = 'id')
 * @method static Admin|Proxy                     last(string $sortedField = 'id')
 * @method static Admin|Proxy                     random(array $attributes = [])
 * @method static Admin|Proxy                     randomOrCreate(array $attributes = [])
 * @method static AdminRepository|RepositoryProxy repository()
 * @method static Admin[]|Proxy[]                 all()
 * @method static Admin[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Admin[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Admin[]|Proxy[]                 findBy(array $attributes)
 * @method static Admin[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Admin[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class AdminFactory extends ModelFactory
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
            'email' => self::faker()->email(),
            'first_name' => self::faker()->firstName(),
            'gender' => self::faker()->randomElement(["Male","Female"]),
            'last_name' => self::faker()->lastName(),
            'password' => '$2y$13$RgSrTjVqoKV4j9wt/psV/.TUwT.m5O4Bkp5lbCD.dg5/ySVjgkR.6',
            'phone_number' => self::faker()->text(10),
            'profile_image' => self::faker()->text(255),
            'roles' => ["ROLE_ADMIN"],
            'username' => self::faker()->text(180),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Admin $admin): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Admin::class;
    }
}
