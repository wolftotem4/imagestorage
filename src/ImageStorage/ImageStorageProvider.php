<?php

namespace WTotem\ImageStorage;

use WTotem\ImageStorage\FileSystem\FileImageExt;
use WTotem\ImageStorage\FileSystem\FileSystem;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class ImageStorageProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    public function boot()
    {
        Relation::morphMap([
            'imagestorage.file' => FileImageExt::class,
        ]);

        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );

        $this->publishes([
            __DIR__ . '/../config/imagestorage.php' => config_path('imagestorage.php'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(ImageStorageManager::class, function ($app) {
            return tap(new ImageStorageManager($app), function ($manager) {
                $this->registerDrivers($manager);
            });
        });

        $this->app->singleton(FileSystem::class);
        $this->app->singleton(ImageFileHandler::class);

        $this->registerModelHandler();
        $this->registerImageStorageResolver();
    }

    /**
     * @param \WTotem\ImageStorage\ImageStorageManager $manager
     */
    protected function registerDrivers(ImageStorageManager $manager)
    {
        $manager->addDriver('file', function () {
            return $this->app->make(FileSystem::class);
        });
    }

    protected function registerModelHandler()
    {
        $this->app->singleton('imagestorage.image', function () {
            return new Image();
        });

        $this->app->singleton('imagestorage.fileimageext', function () {
            return new FileImageExt();
        });
    }

    protected function registerImageStorageResolver()
    {
        Image::setStorageHandlerResolver(function ($image) {
            $manager = $this->app->make(ImageStorageManager::class);
            return $manager->system($image->driver)->getStorageHandler($image);
        });
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            ImageStorageManager::class, FileSystem::class, Image::class,
            ImageFileHandler::class,
        ];
    }
}
