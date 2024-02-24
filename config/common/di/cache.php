<?php

declare(strict_types=1);

namespace App\Provider;

use Psr\SimpleCache\CacheInterface;
use Yiisoft\Cache\File\FileCache;

return [
    CacheInterface::class => FileCache::class,
];
