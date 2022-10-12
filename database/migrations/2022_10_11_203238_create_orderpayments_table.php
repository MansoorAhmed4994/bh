<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderpayments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('consignment_id')->unsigned();
            $table->integer('order_id')->nullable();
            $table->float('cash_handling_charges',8,2)->nullable();
            $table->float('fuel_surcharge',8,2)->nullable();
            $table->float('weight_charges',8,2)->nullable();
            $table->text('current_payment_status')->nullable();
            $table->text('message')->nullable();
            $table->float('amount',8,2)->nullable();
            $table->float('charges',8,2)->nullable();
            $table->dateTime('datetime')->nullable();
            $table->float('gst',8,2)->nullable();
            $table->string('payment_id')->nullable();
            $table->float('payable',8,2)->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('orderpayments');
    }
}
