<?php

namespace WTotem\ImageStorage;

use Closure;
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
        'driver',
        'filesize',
        'filename',
        'mime',
        'width',
        'height',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'filesize' => 'integer',
        'width'    => 'integer',
        'height'   => 'integer',
    ];

    /**
     * @var \Closure
     */
    protected static $imageHandlerResolver;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function ext()
    {
        return $this->morphTo('ext', 'ext_type', 'ext_id')->withDefault();
    }

    /**
     * @return \WTotem\ImageStorage\Contracts\ImageEntity
     */
    public function storageHandler()
    {
        return static::resolveStorageHandler($this);
    }

    /**
     * @param \Closure $resolver
     */
    public static function setStorageHandlerResolver(Closure $resolver)
    {
        static::$imageHandlerResolver = $resolver;
    }

    /**
     * @param  static  $model
     * @return \WTotem\ImageStorage\Contracts\ImageEntity
     */
    public static function resolveStorageHandler($model)
    {
        if (! ($resolver = static::$imageHandlerResolver)) {
            throw new \RuntimeException('An image handler resolver is unset.');
        }

        return $resolver($model);
    }
}
