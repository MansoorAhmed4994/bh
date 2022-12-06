<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customers_id')->unsigned();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_number')->nullable();
            $table->bigInteger('cities_id')->nullable(); 
            $table->string('reciever_address')->nullable();
            $table->string('order_delivery_location')->nullable();
            $table->text('images',100)->nullable();
            $table->string('total_pieces')->nullable();
            $table->string('weight')->nullable();
            $table->string('price')->nullable();
            $table->string('cod_amount')->nullable();
            $table->string('advance_payment')->nullable();
            $table->string('date_order_paid')->nullable();
            $table->string('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('service_type')->nullable();
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
        Schema::dropIfExists('manual_orders');
    }
}
