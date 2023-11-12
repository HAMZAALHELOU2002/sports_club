<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('player_id')->unsigned()->nullable();;
            $table->foreign('player_id')
                ->references('id')
                ->on('players')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->bigInteger('product_id')->unsigned()->nullable();;
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
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
        Schema::dropIfExists('player_products');
    }
}
