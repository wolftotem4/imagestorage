<?php

namespace WTotem\ImageStorage\FileSystem;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WTotem\ImageStorage\Contracts\ImageEntity;
use WTotem\ImageStorage\ImageFile;

class FileImageEntity implements ImageEntity
{
    /**
     * @var \WTotem\ImageStorage\ImageFile
     */
    protected $file;

    /**
     * FileImageEntity constructor.
     * @param \WTotem\ImageStorage\ImageFile $file
     */
    public function __construct(ImageFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return \WTotem\ImageStorage\Image
     */
    public function image()
    {
        return $this->file->image();
    }

    /**
     * @return \Intervention\Image\Image
     */
    public function handler()
    {
        return $this->file->file()->handler();
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->file->getFilename();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showImageResponse()
    {
        if (! $this->file->file()->isFile()) {
            throw new NotFoundHttpException();
        }

        $realpath = $this->file->file()->getRealPath();
        $headers = [
            'Content-Type' => $this->image()->mime,
        ];

        return new BinaryFileResponse($realpath, 200, $headers);
    }
}