<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_trainings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('player_id')->unsigned()->nullable();;
            $table->foreign('player_id')
                ->references('id')
                ->on('players')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->bigInteger('training_id')->unsigned()->nullable();;
            $table->foreign('training_id')
                ->references('id')
                ->on('trainees')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('player_trainings');
    }
}
