<?php

namespace App\Http\Controllers;

use App\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function userBill(Request $request){
        $restaurant = DB::table('restaurants')->first();
       try{
        $order = DB::table('orders')->where('token',$request->id)->orderBy('id','dsc')->first();
        $temp = unserialize($order->order);
       }
        catch(\Exception $ex){
            return redirect()->route('main');
        }

        $totalTime = [];
        for($i=0;$i<count($temp);$i++){

                $id = DB::table('foods')->where('name',$temp[$i]['foodName'])->first()->category_id;
                $time = DB::table('categories')->where('id',$id)->first()->time;
                array_push($totalTime, $time);


        }
        $totalTime = max($totalTime);
        return view('userBill',compact('restaurant','order','totalTime'));
    }

    public function timeUpdate(Request $request){
        $order = $request->order;
        $totalTime = $request->totalTime;
       $time = Carbon::now()->diffInMinutes(Carbon::parse($order['created_at'])->addMinutes($totalTime));
        if(Carbon::now()->greaterThan(Carbon::parse($order['created_at'])->addMinutes($totalTime))){
            return 0 ;
        }else{
            return $time;
        }
    }
}
