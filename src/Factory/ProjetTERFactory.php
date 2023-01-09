<?php

namespace App\Factory;

use App\Entity\ProjetTER;
use App\Repository\ProjetTERRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<ProjetTER>
 *
 * @method        ProjetTER|Proxy                     create(array|callable $attributes = [])
 * @method static ProjetTER|Proxy                     createOne(array $attributes = [])
 * @method static ProjetTER|Proxy                     find(object|array|mixed $criteria)
 * @method static ProjetTER|Proxy                     findOrCreate(array $attributes)
 * @method static ProjetTER|Proxy                     first(string $sortedField = 'id')
 * @method static ProjetTER|Proxy                     last(string $sortedField = 'id')
 * @method static ProjetTER|Proxy                     random(array $attributes = [])
 * @method static ProjetTER|Proxy                     randomOrCreate(array $attributes = [])
 * @method static ProjetTERRepository|RepositoryProxy repository()
 * @method static ProjetTER[]|Proxy[]                 all()
 * @method static ProjetTER[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static ProjetTER[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static ProjetTER[]|Proxy[]                 findBy(array $attributes)
 * @method static ProjetTER[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static ProjetTER[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class ProjetTERFactory extends ModelFactory
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
            'author' => UserFactory::createOne(['roles' => ['ROLE_ENSEIGNANT']]),
            'date' => self::faker()->dateTime(),
            'description' => self::faker()->realTextBetween(100, 300),
            'libProjet' => 'Master 1',
            'titre' => self::faker()->realText(100),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(ProjetTER $projetTER): void {})
        ;
    }

    protected static function getClass(): string
    {
        return ProjetTER::class;
    }
}
