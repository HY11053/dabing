<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWchatappletindicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wchatappletindices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('navtitle1')->nullable();
            $table->string('navtitle2')->nullable();
            $table->string('navtitle3')->nullable();
            $table->text('imagepics')->nullable();
            $table->text('navpics')->nullable();
            $table->string('buttonone')->nullable();
            $table->text('longpics')->nullable();
            $table->text('buttontow')->nullable();
            $table->text('longtwopics')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wchatappletindices');
    }
}
