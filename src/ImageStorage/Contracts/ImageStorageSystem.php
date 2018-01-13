<?php

namespace WTotem\ImageStorage\Contracts;

use WTotem\ImageStorage\Image;
use WTotem\ImageStorage\ImageFileInfo;
use Illuminate\Container\Container;

abstract class ImageStorageSystem
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * @return \WTotem\ImageStorage\Image
     */
    protected function imageModel()
    {
        return $this->container->make('imagestorage.image');
    }

    /**
     * @param  string  $file
     * @param  string  $filename
     * @return \WTotem\ImageStorage\ImageEntity\FileImageEntity
     *
     * @throws \WTotem\ImageStorage\Exceptions\NotValidImageStorageException
     */
    abstract public function createFromFile($file, $filename = null);

    /**
     * @param  string
     * @return \WTotem\ImageStorage\ImageEntity\FileImageEntity
     *
     * @throws \WTotem\ImageStorage\Exceptions\NotValidImageStorageException
     * @throws \WTotem\ImageStorage\Exceptions\InvalidUrlSourceException
     */
    abstract public function fromUrl($url);

    /**
     * @param  \WTotem\ImageStorage\ImageFileInfo  $file
     * @param  string  $filename
     * @return \WTotem\ImageStorage\Image
     */
    protected function createImage(ImageFileInfo $file, $filename = null)
    {
        return $this->imageModel()->newInstance([
            'filesize' => $file->getSize(),
            'filename' => $filename ?: $file->getFilename(),
            'mime'     => $this->normalizeMimeType($file->handler()->mime()),
            'width'    => $file->handler()->width(),
            'height'   => $file->handler()->height()
        ]);
    }

    /**
     * Set the IoC container instance.
     *
     * @param  \Illuminate\Container\Container  $container
     * @return void
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getTmpDir()
    {
        return $this->container['config']->get('imagestorage.temp_dir', sys_get_temp_dir());
    }

    /**
     * @param  string  $mime
     * @return string
     */
    protected function normalizeMimeType($mime)
    {
        switch (strtolower($mime)) {
            case 'image/png':
            case 'image/x-png':
                return 'image/png';

            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                return 'image/jpg';

            case 'image/gif':
                return 'image/gif';

            case 'image/webp':
            case 'image/x-webp':
                return 'image/webp';

            default:
                throw new \InvalidArgumentException(
                    "Unsupported image mime type. Only JPG, GIF, PNG, and WEBP allow."
                );
        }
    }
}