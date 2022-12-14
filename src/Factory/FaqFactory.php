<?php

namespace App\Factory;

use App\Entity\Faq;
use App\Repository\FaqRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Faq>
 *
 * @method        Faq|Proxy                     create(array|callable $attributes = [])
 * @method static Faq|Proxy                     createOne(array $attributes = [])
 * @method static Faq|Proxy                     find(object|array|mixed $criteria)
 * @method static Faq|Proxy                     findOrCreate(array $attributes)
 * @method static Faq|Proxy                     first(string $sortedField = 'id')
 * @method static Faq|Proxy                     last(string $sortedField = 'id')
 * @method static Faq|Proxy                     random(array $attributes = [])
 * @method static Faq|Proxy                     randomOrCreate(array $attributes = [])
 * @method static FaqRepository|RepositoryProxy repository()
 * @method static Faq[]|Proxy[]                 all()
 * @method static Faq[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Faq[]|Proxy[]                 createSequence(array|callable $sequence)
 * @method static Faq[]|Proxy[]                 findBy(array $attributes)
 * @method static Faq[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Faq[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class FaqFactory extends ModelFactory
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
            'question' => self::faker()->text(100),
            'reponse' => self::faker()->text(200),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Faq $faq): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Faq::class;
    }
}
