<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateManualOrders extends Migration
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
            $table->bigInteger('consignment_id')->default(0)->after('id')->nullable();
            $table->Integer('riders_id')->default(1)->after('consignment_id')->nullable();
            $table->float('fare',8,2)->after('service_type')->nullable();
            $table->integer('dc')->default(250)->before('fare')->nullable();
            $table->float('charged_dc',8,2)->default(0)->after('fare')->nullable();
            $table->integer('packaging_cost')->default(0)->before('dc')->nullable();
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
