<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDownloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('downloads', function (Blueprint $table) {
            $table->increments('id');
			$table->string('url');
			$table->string('destination');
			$table->tinyInteger('upload_status')->default(0);
			$table->tinyInteger('sync_status')->default(0);
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
    	Schema::dropIfExists('downloads');
    }
}
