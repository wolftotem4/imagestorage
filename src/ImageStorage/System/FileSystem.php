<?php

namespace WTotem\ImageStorage\System;

use WTotem\ImageStorage\Contracts\ImageStorageSystem;
use WTotem\ImageStorage\ImageEntity\FileImageEntity;
use WTotem\ImageStorage\ImageFile;
use WTotem\ImageStorage\ImageFileHandler;
use WTotem\ImageStorage\ImageFileInfo;
use WTotem\ImageStorage\Traits\UrlDownload;

class FileSystem extends ImageStorageSystem
{
    use UrlDownload;

    /**
     * @return \WTotem\ImageStorage\ImageFileHandler
     */
    protected function fileHandler()
    {
        return $this->container->make(ImageFileHandler::class);
    }

    /**
     * @return \WTotem\ImageStorage\ImageExt\FileImageExt
     */
    protected function fileImageExt()
    {
        return $this->container->make('imagestorage.fileimageext');
    }

    /**
     * @param  string  $file
     * @param  string  $filename
     * @return \WTotem\ImageStorage\ImageEntity\FileImageEntity
     *
     * @throws \WTotem\ImageStorage\Exceptions\NotValidImageStorageException
     */
    public function createFromFile($file, $filename = null)
    {
        $fileHandler = $this->fileHandler();

        $fileInfo  = $fileHandler->getImageFileInfo($file);
        $filename  = $filename ?: $fileInfo->getFilename();

        $target    = $fileHandler->saveImageFile($fileInfo, $this->storagePath());

        $image     = $this->saveImage($target['file'], $filename, $target['hashname']);
        $imageFile = new ImageFile($image, $target['file']);

        return new FileImageEntity($imageFile);
    }

    /**
     * @param  string  $path
     * @return string
     */
    public function storagePath($path = null)
    {
        $basePath = $this->container['config']['imagestorage.drivers.file.storage'];

        return $path ? rtrim($basePath, '/\\') . DIRECTORY_SEPARATOR . $path : $basePath;
    }

    /**
     * @param  \WTotem\ImageStorage\ImageFileInfo $file
     * @return array
     */
    protected function saveImageFile(ImageFileInfo $file)
    {
        $saveName = $file->hashName();
        $target = $file->move($this->storagePath(), $saveName);
        return array($target, $target->getFilename());
    }

    /**
     * @param  \WTotem\ImageStorage\ImageFileInfo  $file
     * @param  string  $filename
     * @param  string  $hashname
     * @return \WTotem\ImageStorage\Image
     */
    protected function saveImage(ImageFileInfo $file, $filename, $hashname)
    {
        $image = $this->createImage($file, $filename);
        ($ext = $this->fileImageExt()->newInstance(compact('hashname')))->save();
        $ext->image()->save($image);
        return $image;
    }
}