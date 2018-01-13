<?php

namespace Tests\Unit\ImageStorage;

use Intervention\Image\Facades\Image as ImageFacade;
use Intervention\Image\Image;
use WTotem\ImageStorage\ImageFileHandler;
use WTotem\ImageStorage\ImageFileInfo;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class ImageFileHandlerTest extends TestCase
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * @var \WTotem\ImageStorage\ImageFileHandler
     */
    protected $imgFileHandler;

    /**
     * @var \Mockery\MockInterface|\WTotem\ImageStorage\ImageFileInfo
     */
    protected $imgFileInfoMock;

    /**
     * @var \Mockery\MockInterface|\Intervention\Image\Image
     */
    protected $imgMock;

    protected function setUp()
    {
        parent::setUp();

        $this->app = new Container();

        $this->imgFileHandler  = new ImageFileHandler($this->app);
        $this->imgFileInfoMock = Mockery::mock(ImageFileInfo::class);
        $this->imgMock         = Mockery::mock(Image::class);
    }

    protected function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testGetImageFileInfo()
    {
        $faker = \Faker\Factory::create();

        $img = $faker->image(sys_get_temp_dir(), 1, 1);

        ImageFacade::shouldReceive('make')
            ->once()
            ->with($img)
            ->andReturn($this->imgMock);

        $info = $this->imgFileHandler->getImageFileInfo($img);

        $this->assertEquals($img, $info->path());

        unlink($img);
    }

    public function testSaveImageFile()
    {
        $faker = \Faker\Factory::create();

        $extension = $faker->fileExtension;
        $hashName = Str::random(40) . '.' . $extension;
        $targetDir = '/mock_path/dir';

        $movedImgInfoMock = Mockery::mock(ImageFileInfo::class);

        $this->imgFileInfoMock
            ->shouldReceive('hashName')
            ->once()
            ->andReturn($hashName);

        $this->imgFileInfoMock
            ->shouldReceive('move')
            ->once()
            ->with($targetDir, $hashName)
            ->andReturn($movedImgInfoMock);

        $data = $this->imgFileHandler->saveImageFile($this->imgFileInfoMock, $targetDir);

        $this->assertEquals($movedImgInfoMock, $data['file']);
        $this->assertEquals($hashName, $data['hashname']);
    }
}
