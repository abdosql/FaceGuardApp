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
        $engineering_domains = [
            "Mechanical Engineering",
            "Electrical Engineering",
            "Civil Engineering",
            "Chemical Engineering",
            "Computer Engineering",
            "Aerospace Engineering",
            "Biomedical Engineering",
            "Environmental Engineering",
            "Industrial Engineering",
            "Materials Science and Engineering",
            "Nuclear Engineering",
            "Petroleum Engineering",
            "Software Engineering",
            "Robotics Engineering",
            "Ocean Engineering",
            "Systems Engineering",
            "Biomechanical Engineering",
            "Geotechnical Engineering",
            "Structural Engineering",
            "Transportation Engineering",
            "Water Resources Engineering",
            "Telecommunication Engineering",
            "Renewable Energy Engineering",
            "Manufacturing Engineering",
            "Control Systems Engineering",
            "Optical Engineering",
            "Biochemical Engineering",
            "Mining Engineering",
            "Metallurgical Engineering",
            "Electronics Engineering",
            "Power Engineering",
            "Thermal Engineering",
            "Acoustical Engineering",
            "Instrumentation Engineering",
            "Food Engineering",
            "Hydraulic Engineering",
            "Process Engineering",
            "Textile Engineering",
            "Urban Engineering",
            "Fire Protection Engineering",
            "Bioengineering",
            "Photonics Engineering",
            "Space Engineering"
        ];

        return [
            'level_name' => self::faker()->randomElement($engineering_domains)
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
