<?php

namespace WTotem\ImageStorage;

class ImageFile
{
    /**
     * @var \WTotem\ImageStorage\Image
     */
    protected $image;

    /**
     * @var \WTotem\ImageStorage\ImageFile
     */
    protected $file;

    /**
     * ImageFile constructor.
     * @param \WTotem\ImageStorage\Image $image
     * @param \WTotem\ImageStorage\ImageFileInfo $file
     */
    public function __construct(Image $image, ImageFileInfo $file)
    {
        $this->image    = $image;
        $this->file     = $file;
    }

    /**
     * @return \WTotem\ImageStorage\Image
     */
    public function image()
    {
        return $this->image;
    }

    /**
     * @return \WTotem\ImageStorage\ImageFileInfo
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->image['filename'];
    }
}
