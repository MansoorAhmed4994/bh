<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Createcategorytable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->bigInteger('gallary_id')->unsigned();
            $table->string('category_icon')-> nullable();
            $table->integer('parent_id')->unsigned();
            $table->string('category_slug')->nullable(); 
            $table->timestamps();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->string('status');
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
