<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 10/22/18
 * Time: 5:38 PM
 */

namespace App\Repo;


use Illuminate\Support\Facades\DB;

class Timing
{
    // Create a preset preparation time for food categories
    public static function presetTime(){

        $dictionary = ['برگر'=>20,'دسر'=>10,'نوشیدنی'=>1,'سیب زمینی'=>15,
            'سالاد'=>5,'ساندویچ'=>15,'سوخاری'=>15,'همبرگر'=>20,'پیتزا'=>20,
            'پیش غذا'=>10,'پاستا'=>20,'سوپ'=>10
        ];
        $category = DB::table('categories')->orderBy('created_at','desc')->first();


            for ($i = 0; $i < count($dictionary); $i++) {

//                similar_text($category->type, array_keys($dictionary)[$i], $percent);

                if (in_array($category->type,array_keys($dictionary))) {

                    DB::table('categories')->where('type', $category->type)->update(['time' => $dictionary[array_keys($dictionary)[array_search($category->type,array_keys($dictionary))]]]);
                } else {
                    DB::table('categories')->where('type', $category->type)->update(['time' => 15]);
                }

            }


    }

    public static function calculate($order,$prepareTime){

        $cart = unserialize($order->order);
        $totalTime = 0;
        $foodTime = [];
        $foodId = [];
        foreach ($cart as $item ) {
            $id = DB::table('foods')->where('name', $item['foodName'])->first()->category_id;
            $totalTime = $totalTime + DB::table('categories')->where('id', $id)->first()->time;
            array_push($foodTime, DB::table('categories')->where('id', $id)->first()->time);
            array_push($foodId,DB::table('categories')->where('id', $id)->first()->id);
        }


//        diff > 0 || == 0 || < 0
        $diff = $prepareTime - max($foodTime);
        $predictTimeSum = array_sum($foodTime);
        if($predictTimeSum == 0){
            $predictTimeSum = 1;
        }
//        summing all foods time in a cart and comparing with the time specified in categories table
        for ($i = 0; $i < count($foodTime); $i++) {
// modified => modified category preparation time
            $modified[$i] = $foodTime[$i] / $predictTimeSum * $diff;
            $category_time = DB::table('categories')->where('id', $foodId[$i])->first()->time;
            if($modified[$i] + $category_time == 0){

                DB::table('categories')->where('id', $foodId[$i])->update(['time' => 5]);
            }else{
                DB::table('categories')->where('id', $foodId[$i])->update(['time' => round($modified[$i] + $category_time) + 1]);
            }
        }


    }

}
