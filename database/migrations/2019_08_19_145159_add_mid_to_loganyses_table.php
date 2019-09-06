<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMidToLoganysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loganyses', function (Blueprint $table) {
            $table->integer('mid')->default(0);
            $table->index('mid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loganyses', function (Blueprint $table) {
            $table->dropColumn(['mid']);
        });
    }
}
