<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancels', function (Blueprint $table) {
            $table->increments('id');
            $table->text('order');
            $table->unsignedInteger('table_id')->nullable();
            $table->string('price');
            $table->text('info')->nullable();
            $table->string('delivered')->default(0);
            $table->string('pending')->default(0);
            $table->string('paid')->default(0);
            $table->string('order_number')->default(0);
            $table->string('orderCode')->nullable();
            $table->text('token')->nullable();
            $table->unsignedInteger('user_id')->default(0);
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
        Schema::dropIfExists('cancels');
    }
}
