<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageStorageImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_storage_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ext_id');
            $table->string('ext_type');
            $table->string('driver');
            $table->unsignedInteger('filesize');
            $table->string('filename');
            $table->string('mime', 20);
            $table->unsignedMediumInteger('width');
            $table->unsignedMediumInteger('height');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_storage_images');
    }
}
