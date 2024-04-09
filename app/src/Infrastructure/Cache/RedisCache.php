<?php
namespace App\src\Infrastructure\Cache;

use App\src\Domain\Cache\CacheInterface;
use Illuminate\Support\Facades\Cache;

class RedisCache implements CacheInterface
{
    public function get(string $key)
    {
        return Cache::get($key);
    }

    public function set(string $key, $value, int $ttl = 0)
    {
        Cache::put($key, $value, $ttl * 60);
    }

    public function delete(string $key)
    {
        Cache::forget($key);
    }
}
