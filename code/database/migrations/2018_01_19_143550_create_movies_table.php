<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedTinyInteger('type');
			$table->string('name');
			$table->unsignedTinyInteger('poster');
			$table->integer('download_id')->unsigned();
			$table->foreign('download_id')->references('id')->on('downloads');
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
    	Schema::dropIfExists('movies');
    }
}
