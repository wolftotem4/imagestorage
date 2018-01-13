<?php

namespace WTotem\ImageStorage\Facades;

use Illuminate\Support\Facades\Facade;
use WTotem\ImageStorage\ImageStorageManager;

/**
 * @method static \WTotem\ImageStorage\Contracts\ImageStorageSystem system(string $driver = null)
 * @method static \WTotem\ImageStorage\ImageStorageManager addDriver(string $driver, \Closure $resolver)
 * @method static string getDefaultDriver()
 * @method static string getConfig(string $driver = null)
 *
 * @method static string type()
 * @method static \WTotem\ImageStorage\ImageEntity\FileImageEntity createFromFile(string $file, string $filename = null)
 * @method static \WTotem\ImageStorage\ImageEntity\FileImageEntity fromUrl(string $url)
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