<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemainingInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remaining_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('products_id')->unsigned(); 
            $table->integer('qty')->nullable(); 
            $table->float('cost',8,2)->nullable();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remaining_inventories');
    }
}
