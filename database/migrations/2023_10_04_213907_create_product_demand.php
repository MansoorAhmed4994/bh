<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDemand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_demands', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('name');    
            $table->text('image')->nullable();  
            $table->text('table_name')->nullable(); 
            $table->bigInteger('ref_id')->nullable();
            $table->timestamps();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->string('status')->nullable();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_demand');
    }
}
