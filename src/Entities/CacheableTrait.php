<?php

namespace Origami\Support\Entities;

use Closure;
use Illuminate\Cache\ArrayStore;
use Illuminate\Contracts\Cache\Store;

trait CacheableTrait
{
    /**
     * Cache storage instance
     *
     * @var Store
     */
    private $cacheInstance;

    public function remember($key, Closure $callback)
    {
        if ( ! is_null($value = $this->cacheInstance()->get($key)) ) {
            return $value;
        }

        $this->cacheInstance()->forever($key, $value = $callback());

        return $value;
    }

    public function cache($key = null, $value = null)
    {
        $cache = $this->cacheInstance();

        if ( is_null($key) ) {
            return $cache;
        }

        if ( is_null($value) ) {
            return $cache->get($key);
        }

        return $cache->forever($key, $value);
    }

    public function flushCache()
    {
        $this->cacheInstance()->flush();
    }

    public function cacheInstance()
    {
        if ( ! $this->cacheInstance )  {
            $this->cacheInstance = new ArrayStore();
        }

        return $this->cacheInstance;
    }

}