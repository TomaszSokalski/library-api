<?php

namespace App\Factory;

use App\Entity\Reader;
use App\Repository\ReaderRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Reader>
 *
 * @method        Reader|Proxy create(array|callable $attributes = [])
 * @method static Reader|Proxy createOne(array $attributes = [])
 * @method static Reader|Proxy find(object|array|mixed $criteria)
 * @method static Reader|Proxy findOrCreate(array $attributes)
 * @method static Reader|Proxy first(string $sortedField = 'id')
 * @method static Reader|Proxy last(string $sortedField = 'id')
 * @method static Reader|Proxy random(array $attributes = [])
 * @method static Reader|Proxy randomOrCreate(array $attributes = [])
 * @method static ReaderRepository|RepositoryProxy repository()
 * @method static Reader[]|Proxy[] all()
 * @method static Reader[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Reader[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Reader[]|Proxy[] findBy(array $attributes)
 * @method static Reader[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Reader[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ReaderFactory extends ModelFactory
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
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'email' => self::faker()->email(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Reader $reader): void {})
            ;
    }

    protected static function getClass(): string
    {
        return Reader::class;
    }
}
