<?php

namespace WTotem\ImageStorage;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * @var string
     */
    protected $table = 'image_storage_images';

    /**
     * @var array
     */
    protected $fillable = [
        'filesize',
        'filename',
        'mime',
        'width',
        'height'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'filesize' => 'integer',
        'width'    => 'integer',
        'height'   => 'integer'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function ext()
    {
        return $this->morphTo('ext', 'ext_type', 'ext_id')->withDefault();
    }
}
