<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateManualOrdersStatusReason extends Migration
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
            $table->text('status_reason')->default('')->after('updated_by')->nullable();
            $table->float('charged_dc',8,2)->default('0.00')->after('fare')->nullable();
            $table->text('payment_status')->default('not recieved')->after('charged_dc')->nullable();
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
