<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->bigInteger('order_id');
            $table->bigInteger('transaction_id');
            $table->string('sender_name')->nullable();  
            $table->string('transfer_to')->nullable(); 
            $table->datetime('datetime');
            $table->string('images')->nullable();
            $table->string('description')->nullable();
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
        //
        Schema::dropIfExists('customer_payments');
    }
}
