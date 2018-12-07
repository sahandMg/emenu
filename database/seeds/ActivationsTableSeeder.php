<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Activation::class,1000)->create();
        DB::table('activations')->where('original',0)->update(['trial'=>1]);
    }
}
