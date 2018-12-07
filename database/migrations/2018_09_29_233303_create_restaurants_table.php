<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('slogan')->nullable();
            $table->text('image')->nullable();
            $table->text('logo')->nullable();
            $table->string('tel')->nullable();
            $table->text('address')->nullable();
            $table->text('info')->nullable();
            $table->tinyInteger('tax')->nullable();
            $table->string('printer')->nullable();
            $table->tinyInteger('payMethod')->default(1);
            $table->tinyInteger('tableCounting')->default(1);
            $table->tinyInteger('complete')->default(0);
            $table->timestamps();
        });
        DB::table('restaurants')->insert([
            'name'=>'نام اغذیه',
            'tax'=>0,
            'created_at'=>Carbon::now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}
