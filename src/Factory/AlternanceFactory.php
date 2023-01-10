<?php

namespace App\Factory;

use App\Entity\Alternance;
use App\Repository\AlternanceRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Alternance>
 *
 * @method        Alternance|Proxy                     create(array|callable $attributes = [])
 * @method static Alternance|Proxy                     createOne(array $attributes = [])
 * @method static Alternance|Proxy                     find(object|array|mixed $criteria)
 * @method static Alternance|Proxy                     findOrCreate(array $attributes)
 * @method static Alternance|Proxy                     first(string $sortedField = 'id')
 * @method static Alternance|Proxy                     last(string $sortedField = 'id')
 * @method static Alternance|Proxy                     random(array $attributes = [])
 * @method static Alternance|Proxy                     randomOrCreate(array $attributes = [])
 * @method static AlternanceRepository|RepositoryProxy repository()
 * @method static Alternance[]|Proxy[]                 all()
 * @method static Alternance[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Alternance[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Alternance[]|Proxy[]                 findBy(array $attributes)
 * @method static Alternance[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Alternance[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class AlternanceFactory extends ModelFactory
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

    protected function getDefaults(): array
    {
        return [
            'author' => UserFactory::createOne(['roles' => ['ROLE_ENTREPRISE']]),
            'description' => self::faker()->realTextBetween(100, 255),
            'titre' => self::faker()->realText(100),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Alternance $alternance): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Alternance::class;
    }
}
