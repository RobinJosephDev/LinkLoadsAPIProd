<?php

namespace App\Providers;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Elastic\Transport\NodePool\NodePoolInterface;
use Elastic\Transport\NodePool\Resurrect\ElasticsearchResurrect;
use Elastic\Transport\NodePool\Resurrect\ResurrectInterface;
use Elastic\Transport\NodePool\Selector\RoundRobin;
use Elastic\Transport\NodePool\Selector\SelectorInterface;
use Elastic\Transport\NodePool\SimpleNodePool;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Client\ClientInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Simulate data retrieval and caching.
     */
    public function getData()
    {
        // Use Cache::remember to automatically cache the data if not already cached
        return Cache::remember('key', now()->addMinutes(10), function () {
            // Simulate data retrieval (e.g., from a database)
            return 'fetched data'; // Replace with actual data source
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // Bind the Psr HTTP Client Interface to Guzzle
        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(NodePoolInterface::class, SimpleNodePool::class);
        $this->app->bind(SelectorInterface::class, RoundRobin::class);
        $this->app->bind(ResurrectInterface::class, ElasticsearchResurrect::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Check and register custom Enum type with Doctrine DBAL
        if (class_exists(\Doctrine\DBAL\Types\Type::class)) {
            if (!Type::hasType('enum')) {
                Type::addType('enum', EnumType::class);
            }
        }
    }
}

/**
 * Custom Doctrine Enum Type.
 */
class EnumType extends Type
{
    const ENUM = 'enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('" . implode("','", $fieldDeclaration['allowed']) . "')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        // Here, you can map the DB value to the appropriate PHP value.
        return $value; // Modify if needed to convert data
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        // Here, you can convert the PHP value to the appropriate DB value.
        return $value; // Modify if needed to convert data
    }

    public function getName()
    {
        return self::ENUM;
    }
}
