<?php

namespace WTotem\ImageStorage\Traits;

use WTotem\ImageStorage\Exceptions\InvalidUrlSourceException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

trait UrlDownload
{
    /**
     * @param  string  $url
     * @return \WTotem\ImageStorage\Contracts\ImageEntity
     *
     * @throws \WTotem\ImageStorage\Exceptions\NotValidImageStorageException
     * @throws \WTotem\ImageStorage\Exceptions\InvalidUrlSourceException
     */
    public function fromUrl($url)
    {
        $tmp = tempnam($this->getTmpDir(), 'IDL');

        try {
            $client = new Client();
            $client->get($url, ['sink' => $tmp]);

            return $this->createFromFile($tmp);
        } catch (BadResponseException $e) {
            throw new InvalidUrlSourceException($e);
        } finally {
            if (file_exists($tmp)) {
                @unlink($tmp);
            }
        }
    }
}