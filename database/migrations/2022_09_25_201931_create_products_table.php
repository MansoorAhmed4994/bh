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
            $table->string('slug')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('name')->nullable();
            $table->float('weight',8,2)->nullable();
            $table->string('weight_type')->nullable();
            $table->float('sale_price',8,2)->nullable();
            $table->float('discount_price',8,2)->nullable();
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
