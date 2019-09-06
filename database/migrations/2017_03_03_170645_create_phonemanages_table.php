<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhonemanagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phonemanages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phoneno',500);
            $table->string('name')->nullable();
            $table->string('gender')->nullable('');
            $table->string('address')->nullable('');
            $table->ipAddress('ip')->nullable();
            $table->string('note')->nullable();
            $table->string('host')->nullable();
            $table->text('referer')->nullable();
            $table->text('category')->nullable();
            $table->text('cid')->nullable();
            $table->text('brandname')->nullable();
            $table->index('phoneno');
            $table->index('host');
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
        Schema::dropIfExists('phonemanages');
    }
}
