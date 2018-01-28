<?php

namespace WTotem\ImageStorage\Contracts;

interface ImageEntity
{
    /**
     * @return \WTotem\ImageStorage\Image
     */
    public function image();

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @return \Intervention\Image\Image
     */
    public function handler();

    /**
     * @return mixed
     */
    public function showImageResponse();
}