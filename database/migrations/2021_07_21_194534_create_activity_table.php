<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->id();
            $table->date('activity_date');
            $table->text('description');
            $table->integer('score');
            $table->bigInteger('pet_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('activity', function(Blueprint $table) {
            $table->foreign('pet_id')->references('id')->on('pet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity');
    }
}
