<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerFoodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_food', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('player_id')->unsigned()->nullable();;
            $table->foreign('player_id')
                ->references('id')
                ->on('players')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->bigInteger('food_id')->unsigned()->nullable();;
            $table->foreign('food_id')
                ->references('id')
                ->on('food')
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
        Schema::dropIfExists('player_food');
    }
}
