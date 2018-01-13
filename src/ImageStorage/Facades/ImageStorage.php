<?php

namespace WTotem\ImageStorage\Facades;

use Illuminate\Support\Facades\Facade;
use WTotem\ImageStorage\ImageStorageManager;

/**
 * @method \WTotem\ImageStorage\Contracts\ImageStorageSystem system(string $driver = null)
 * @method \WTotem\ImageStorage\ImageStorageManager addDriver(string $driver, \Closure $resolver)
 * @method string getDefaultDriver()
 * @method string getConfig(string $driver = null)
 *
 * @method string type()
 * @method \WTotem\ImageStorage\ImageEntity\FileImageEntity createFromFile(string $file, string $filename = null)
 * @method \WTotem\ImageStorage\ImageEntity\FileImageEntity fromUrl(string $url)
 */

class ImageStorage extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ImageStorageManager::class;
    }
}