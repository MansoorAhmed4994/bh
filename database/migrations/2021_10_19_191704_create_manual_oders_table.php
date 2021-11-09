<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualOdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_oders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_number')->nullable();
            $table->string('number');
            $table->string('whatsapp_number')->nullable();
            $table->string('email')->nullable();
            $table->string('order_delivery_location')->nullable();
            $table->string('city')->nullable();
            $table->string('address1');
            $table->string('address2')->nullable();
            $table->string('price')->nullable();
            $table->text('images',100)->nullable();
            $table->string('total_pieces')->nullable();
            $table->string('weight')->nullable();
            $table->string('date_order_paid')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('manual_oders');
    }
}
