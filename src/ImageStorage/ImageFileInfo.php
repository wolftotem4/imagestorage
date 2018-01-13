<?php

namespace WTotem\ImageStorage;

use Illuminate\Http\File;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image as ImageFacade;

class ImageFileInfo extends File
{
    /**
     * @var \Intervention\Image\Image
     */
    protected $handler;

    /**
     * Determine if the file is a valid image.
     *
     * @return bool
     */
    public function isValidImage()
    {
        try {
            $this->handler();
            return true;
        } catch (NotReadableException $e) {
            return false;
        }
    }

    /**
     * @param  string  $directory
     * @param  string  $name
     * @return static
     *
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException if the target file could not be created
     */
    public function move($directory, $name = null)
    {
        $file = parent::move($directory, $name);
        $image = new static($file->getRealPath(), false);
        return $image;
    }

    /**
     * @return \Intervention\Image\Image
     */
    public function handler()
    {
        return $this->handler ?: $this->handler = ImageFacade::make($this->path());
    }
}