<?php

namespace WTotem\ImageStorage;

use Closure;
use Illuminate\Container\Container;
use InvalidArgumentException;

/**
 * @method string type()
 * @method FileSystem\FileImageEntity createFromFile(string $file, string $filename = null)
 * @method FileSystem\FileImageEntity fromUrl(string $url)
 */

class ImageStorageManager
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * @var array
     */
    protected $systems = [];

    /**
     * @var array
     */
    protected $resolvers = [];

    /**
     * ImageStorageManager constructor.
     * @param \Illuminate\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param  string  $driver
     * @return \WTotem\ImageStorage\Contracts\ImageStorageSystem
     */
    public function system($driver = null)
    {
        $driver = $driver ?: $this->getDefaultDriver();

        if (! isset($this->systems[$driver])) {
            $this->systems[$driver] = $this->resolve($driver);

            $this->systems[$driver]->setContainer($this->app);
        }

        return $this->systems[$driver];
    }

    /**
     * @param  string    $driver
     * @param  \Closure  $resolver
     * @return $this
     */
    public function addDriver($driver, Closure $resolver)
    {
        $this->resolvers[$driver] = $resolver;
        return $this;
    }

    /**
     * @param  string  $driver
     * @return \WTotem\ImageStorage\Contracts\ImageStorageSystem
     */
    protected function resolve($driver)
    {
        if (! isset($this->resolvers[$driver])) {
            throw new InvalidArgumentException("No driver for [$driver]");
        }

        return call_user_func($this->resolvers[$driver]);
    }

    /**
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['imagestorage.default'];
    }

    /**
     * @param  string  $driver
     * @return string
     */
    public function getConfig($driver = null)
    {
        $driver = $driver ?: $this->getDefaultDriver();

        return $this->app['config']["imagestorage.drivers.$driver.storage"];
    }

    /**
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->system()->{$method}(...$parameters);
    }
}
