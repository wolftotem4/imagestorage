<?php

namespace WTotem\ImageStorage;

use Illuminate\Container\Container;
use WTotem\ImageStorage\Exceptions\NotValidImageStorageException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ImageFileHandler
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * ImageFileHandler constructor.
     * @param \Illuminate\Container\Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param  string  $file
     * @return \WTotem\ImageStorage\ImageFileInfo
     *
     * @throws \WTotem\ImageStorage\Exceptions\NotValidImageStorageException
     */
    public function getImageFileInfo($file)
    {
        try {
            $info = new ImageFileInfo($file);
            if (! $info->isValidImage()) {
                throw new NotValidImageStorageException(
                    "The file is an invalid image ($file)"
                );
            }
            return $info;
        } catch (FileNotFoundException $e) {
            throw new NotValidImageStorageException(
                "The file is an invalid image ($file)"
            );
        }
    }

    /**
     * @param  \WTotem\ImageStorage\ImageFileInfo $file
     * @param  string  $targetDir
     * @return array
     */
    public function saveImageFile(ImageFileInfo $file, $targetDir)
    {
        $hashname   = $file->hashName();
        $file       = $file->move($targetDir, $hashname);
        return compact('file', 'hashname');
    }
}