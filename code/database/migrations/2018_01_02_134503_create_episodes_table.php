<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('episodes', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('number')->unsigned();
			$table->string('name');
			$table->integer('season_id')->unsigned();
			$table->foreign('season_id')->references('id')->on('seasons');
			$table->integer('download_id')->unsigned();
			$table->foreign('download_id')->references('id')->on('downloads');
			$table->unique(['number', 'season_id'], 'season_id_number');
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
    	Schema::dropIfExists('episodes');
    }
}
