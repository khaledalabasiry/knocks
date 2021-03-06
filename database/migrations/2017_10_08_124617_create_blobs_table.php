<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('type');
            $table->string('extension');
            $table->bigInteger('object_id')->unsigned(); //foreign key object
            $table->string('album')->nullable();
            $table->json('index')->nullable();
            $table->bigInteger('parent_object')->nullable();
        });
        Schema::table('blobs', function(Blueprint $table){
            // $table->foreign('object_id')->references('id')->on('objects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blobs');
    }
}
