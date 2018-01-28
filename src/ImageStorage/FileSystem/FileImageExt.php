<?php

namespace WTotem\ImageStorage\FileSystem;

use WTotem\ImageStorage\Image;
use Illuminate\Database\Eloquent\Model;

class FileImageExt extends Model
{
    /**
     * @var string
     */
    protected $table = 'image_storage_file';

    /**
     * @var array
     */
    protected $fillable = [
        'hashname',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function image()
    {
        return $this->morphOne(Image::class, 'ext', 'ext_type', 'ext_id', 'id');
    }
}