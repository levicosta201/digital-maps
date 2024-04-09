<?php
namespace App\src\Domain\Cache;

interface CacheInterface
{
    public function get(string $key);
    public function set(string $key, $value, int $ttl = 0);
    public function delete(string $key);
}
