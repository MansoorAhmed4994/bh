<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; 

class UpdateManualordersChangeColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manual_orders', function($table)
        { 
            
            $table->decimal('product_price',16,2)->change();
            $table->decimal('dc',16,2)->change();
            $table->decimal('packaging_cost',16,2)->change();
            $table->decimal('price',16,2)->change();
            // $table->decimal('advance_payment',16,2)->default(300)->change();
            // $table->decimal('cod_amount',16,2)->default(300)->change();
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
