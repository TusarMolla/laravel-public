<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('products',function(Blueprint $table){

            $table->id();
            $table->string('name',length:500);
            $table->double("price",40,2);
            $table->integer("quantity");
            $table->string("short_des",length:255)->nullable();
            $table->string("description",length:500)->nullable();
            $table->string("image",length:255)->nullable();
            $table->timestamps();
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
    }
};
