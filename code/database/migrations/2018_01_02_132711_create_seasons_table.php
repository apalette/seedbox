<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('seasons', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('number')->unsigned();
			$table->boolean('poster')->default(0);
		    $table->integer('tvshow_id')->unsigned();
			$table->foreign('tvshow_id')->references('id')->on('tvshows');
			$table->unique(['number', 'tvshow_id'], 'tvshow_id_number');
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
    	Schema::dropIfExists('seasons');
    }
}
