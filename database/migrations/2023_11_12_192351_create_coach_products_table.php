<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoachProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('coach_id')->unsigned()->nullable();;
            $table->foreign('coach_id')
                ->references('id')
                ->on('coachs')
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
        Schema::dropIfExists('coach_products');
    }
}
