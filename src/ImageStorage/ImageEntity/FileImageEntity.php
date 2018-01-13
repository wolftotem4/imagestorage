<?php

namespace WTotem\ImageStorage\ImageEntity;

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
}