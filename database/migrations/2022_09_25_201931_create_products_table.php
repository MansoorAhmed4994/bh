<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sku')->nullable();
            $table->integer('slug')->nullable();
            $table->integer('category')->nullable();
            $table->string('name')->nullable();
            $table->float('wieght',8,2)->nullable();
            $table->float('sale_price',8,2)->nullable();
            $table->float('cost_price',8,2)->nullable();
            $table->string('images')->nullable();
            $table->string('video_url')->nullable();
            $table->timestamps();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
