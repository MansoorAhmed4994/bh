<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('products_id')->unsigned();
            $table->integer('warehouse_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->string('stock_status')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('reference_id')->nullable();
            $table->string('stock_type')->nullable();
            $table->float('cost',8,2)->nullable(); 
            $table->float('sale',8,2)->nullable();
            $table->float('discount',8,2)->nullable(); 
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
        Schema::dropIfExists('inventories');
    }
}
