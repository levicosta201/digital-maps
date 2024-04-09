<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\src\Infrastructure\Cache\RedisCache;
use Illuminate\Support\Facades\Cache;

class RedisCacheTest extends TestCase
{
    /**
     * Testa a obtenção de valores do cache.
     *
     * @return void
     */
    public function testGet()
    {
        Cache::shouldReceive('get')
            ->once()
            ->with('mock-mock-mock-key')
            ->andReturn('mock-mock-mock-value');

        $cache = new RedisCache();

        $this->assertEquals('mock-mock-mock-value', $cache->get('mock-mock-mock-key'));
    }

    /**
     * Testa a definição de valores no cache.
     *
     * @return void
     */
    public function testSet()
    {
        Cache::shouldReceive('put')
            ->once()
            ->with('mock-mock-mock-key', 'mock-mock-mock-value', 0);

        $cache = new RedisCache();
        $cache->set('mock-mock-mock-key', 'mock-mock-mock-value');
    }

    /**
     * Testa a exclusão de valores do cache.
     *
     * @return void
     */
    public function testDelete()
    {
        Cache::shouldReceive('forget')
            ->once()
            ->with('mock-mock-mock-key');

        $cache = new RedisCache();
        $cache->delete('mock-mock-mock-key');
    }
}
