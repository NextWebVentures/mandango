<?php

/*
 * This file is part of Mandango.
 *
 * (c) Adam Kusmierz (adam@kusmierz.be)
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mandango\Cache;

/**
 * NullCache. The way to turn off the cache (sometimes it brakes down things)
 *
 * @author Adam Kusmierz (adam@kusmierz.be)
 */
class NullCache implements CacheInterface
{
    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {}

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {}

    /**
     * {@inheritdoc}
     */
    public function clear()
    {}
}
