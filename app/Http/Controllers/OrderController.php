<?php

namespace App\Http\Controllers;

use App\Repo\Timing;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function __construct()
    {
    $this->middleware(['activated','auth:manager,cashier'])->except('hasTable','sendOrder','paidStat');

    }
    public function orders(){
        $orders = DB::table('orders')->where('created_at','>=',Carbon::today()->toDateTimeString())
            ->where('created_at','<',Carbon::today()->addHour(24)->toDateTimeString())->get();
        return view('orders',compact('orders'));
    }
    public function ytd(){

        return view('ytd_orders');
    }

    public function getOldOrders(){
        $orders = DB::table('orders')->where('created_at','>=',Carbon::yesterday()->toDateTimeString())->
        where('created_at','<',Carbon::today()->toDateTimeString())->orderBy('id','dsc')->get();
        $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
            if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
                $orders[$i]['hour'] = Carbon::now()->diffInHours($orders[$i]['created_at']);
            }
            else{
                $orders[$i]['minute'] =  Carbon::now()->diffInMinutes($orders[$i]['created_at']);
            }
        }
        return $orders;
    }

    public function getOrders(){
        $orders = DB::table('orders')->where('created_at','>=',Carbon::today()->toDateTimeString())
            ->where('created_at','<',Carbon::tomorrow()->toDateTimeString())
            ->orderBy('created_at','desc')->get();
        $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
            if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
                $orders[$i]['hour'] = Carbon::now()->diffInHours($orders[$i]['created_at']);
            }
            else{
                $orders[$i]['minute'] =  Carbon::now()->diffInMinutes($orders[$i]['created_at']);
            }
        }
        return $orders;
    }


    public function delivered(Request $request){


        DB::table('orders')->where('id',$request->id)->update(['delivered'=>'1']);
        DB::table('orders')->where('id',$request->id)->update(['pending'=>'0']);
        if($request->has('time')){
            $orders = DB::table('orders')->where('created_at','>=',Carbon::yesterday()->toDateTimeString())->
            where('created_at','<',Carbon::today()->toDateTimeString())->orderBy('id','dsc')->get();
        }else{
            $orders = DB::table('orders')->where('created_at','>=',Carbon::today()->toDateTimeString())
                ->where('created_at','<',Carbon::tomorrow()->toDateTimeString())->orderBy('created_at','dsc')->get();

        }
        $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
            if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
                $orders[$i]['hour'] = Carbon::now()->diffInHours($orders[$i]['created_at']);
            }
            else{
                $orders[$i]['minute'] =  Carbon::now()->diffInMinutes($orders[$i]['created_at']);
            }
        }
        $order = DB::table('orders')->where('id',$request->id)->first();
//      preparationTime
        $prTime = Carbon::now()->diffInMinutes(Carbon::parse($order->created_at));
        // Calculating order time and modify it
        Timing::calculate($order,$prTime);



        return $orders;

    }

    public function pending(Request $request){

        DB::table('orders')->where('id',$request->id)->update(['pending'=>1]);
        if($request->has('time')){
            $orders = DB::table('orders')->where('created_at','>=',Carbon::yesterday()->toDateTimeString())->
            where('created_at','<',Carbon::today()->toDateTimeString())->orderBy('id','dsc')->get();
        }else{
            $orders = DB::table('orders')->where('created_at','>=',Carbon::today()->toDateTimeString())
                ->where('created_at','<',Carbon::tomorrow()->toDateTimeString())->orderBy('created_at','dsc')->get();

        }

        $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
            if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
                $orders[$i]['hour'] = Carbon::now()->diffInHours($orders[$i]['created_at']);
            }
            else{
                $orders[$i]['minute'] =  Carbon::now()->diffInMinutes($orders[$i]['created_at']);
            }
        }


        return $orders;

    }

    public function paid(Request $request){

        DB::table('orders')->where('id',$request->id)->update(['paid'=>'1']);
        if($request->has('time')){
            $orders = DB::table('orders')->where('created_at','>=',Carbon::yesterday()->toDateTimeString())->
            where('created_at','<',Carbon::today()->toDateTimeString())->orderBy('id','dsc')->get();
        }else{
            $orders = DB::table('orders')->where('created_at','>=',Carbon::today()->toDateTimeString())
                ->where('created_at','<',Carbon::tomorrow()->toDateTimeString())->orderBy('created_at','dsc')->get();

        }
        $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
            if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
                $orders[$i]['hour'] = Carbon::now()->diffInHours($orders[$i]['created_at']);
            }
            else{
                $orders[$i]['minute'] =  Carbon::now()->diffInMinutes($orders[$i]['created_at']);
            }
        }


        return $orders;
    }

    public function sendOrder(Request $request){
            $limit = 1000;
            $price = 0;
            $orders = DB::table('orders')->where('created_at','>',Carbon::today()->addHours(7)->toDateTimeString())->
            where('created_at','<',Carbon::tomorrow()->toDateTimeString())->get();
            if( count($orders) == 0 && Carbon::now() > Carbon::today()->addHours(7)){
                if(Cache::has('order_number')){
                    Cache::forget('order_number');
                }
            }
        if(!Cache::has('order_number')){
            Cache::put('order_number',1,1440);
        }else{
            Cache::put('order_number',Cache::get('order_number')+1,1440);
        }

            $orderArr = $request->order;
            for($i=0;$i<count($orderArr);$i++){
                $price = $price + $orderArr[$i]['price']*$orderArr[$i]['foodNumber'];
            }
            if(!$request->has('table_id')){
                $request['table_id'] = null;
            }
            if(!is_null($request->info)){
                DB::table('orders')->insert([
                    'order'=> serialize($request->order),
                    'price'=> $price,
                    'table_id'=> $request->table_id,
                    'info'=> $request->info,
                    'order_number' => Cache::get('order_number'),
                    'token' => Session::token(),
                    'created_at'=>Carbon::now()
                ]);
            }else{
                DB::table('orders')->insert([
                    'order'=> serialize($request->order),
                    'price'=> $price,
                    'table_id'=> $request->table_id,
                    'order_number' => Cache::get('order_number'),
                    'token' => Session::token(),
                    'created_at'=>Carbon::now()->toDateTimeString()
                ]);
            }
// TODO Add ajax request to index.blade page
            for($i=0;$i<count($orderArr);$i++){
                $sold = DB::table('foods')->where('id',$orderArr[$i]['foodId'])->first()->sold;
                DB::table('foods')->where('id',$orderArr[$i]['foodId'])
                    ->update(['sold'=> $sold + $orderArr[$i]['foodNumber']]);
            }
            if(DB::table('managers')->first()->original == 0) {

                $id = DB::table('orders')->orderBy('id', 'desc')->first()->id;
                if ($id < 51 && $id >= 50) {
                    DB::table('messages')->insert([
                        'message' => 'مشترک گرامی، تنها ۱۰۰ سفارش دیگر قابل ثبت می باشد. برای خرید نسخه کامل این برنامه، با شماره ۰۹۱۰۴۹۶۳۷۳۴ و یا ۰۹۳۷۱۸۶۹۵۶۸ تماس بگیرید',
                        'created_at'=>Carbon::now()
                    ]);
                } else if ($id < 101 && $id >= 100) {
                    DB::table('messages')->insert([
                        'message' => 'مشترک گرامی، تنها ۵۰ سفارش دیگر قابل ثبت می باشد. برای خرید نسخه کامل این برنامه، با شماره ۰۹۱۰۴۹۶۳۷۳۴ و یا ۰۹۳۷۱۸۶۹۵۶۸ تماس بگیرید',
                        'created_at'=>Carbon::now()
                    ]);
                } else if ($id >= 130 && $id < 131) {
                    DB::table('messages')->insert([
                        'message' => 'مشترک گرامی، تنها ۲۰ سفارش دیگر قابل ثبت می باشد. برای خرید نسخه کامل این برنامه، با شماره ۰۹۱۰۴۹۶۳۷۳۴ و یا ۰۹۳۷۱۸۶۹۵۶۸ تماس بگیرید',
                        'created_at'=>Carbon::now()
                    ]);
                } else if ($id >= 150) {
                    DB::table('managers')->where('id', '1')->update(['expired' => 1]);
                    // TODO Send expiration email to user

                }
            }
//        }
            return 200 ;
        }


    public function reset(Request $request){

        Cache::forget('order_number');
        return 200;
    }

    public function paidStat(Request $request){

         $paid = DB::table('orders')->where('id',$request->id)->first()->paid;
         $method = DB::table('restaurants')->first()->payMethod;
        $delivered =  DB::table('orders')->where('id',$request->id)->first()->delivered;
        $pending =  DB::table('orders')->where('id',$request->id)->first()->pending;
        return [$paid,$method,$delivered,$pending];


    }
    public function getOrderId(){

        return DB::table('orders')->orderBy('id','desc')->first()->id;
    }

    public function indexCashier (){

        $types = DB::table('categories')->orderBy('priority','asc')->get()->pluck('type');
        $foods = [];
        for($i=0;$i<count($types);$i++){

            $foods[$i] = DB::table('foods')->where([['category',$types[$i]],['valid','1']])->get();

        }
        $order = DB::table('orders')->where('token',Session::token())->orderBy('id','dsc')->first();
        $info = DB::table('restaurants')->first();


        return view('indexCashier',compact('foods','types','info','order','token'));
    }

    /*
     * Puts foods id with their numbers in cache and send it to cashierReceipt
     */
    public function post_indexCashier(Request $request){

        if(Cache::has('orderRequest')){
            Cache::forget('orderRequest');
        }
        Cache::put('orderRequest',$request->all(),50);
        return redirect()->route('cashierReceipt');
    }
    /*
     * Shows the receipt after submitting an order to cashier
     */
    public function cashierReceipt(){
        $foods = [];
        $request = Cache::get('orderRequest');
        $restaurant = DB::table('restaurants')->first();
         unset($request['_token']);
        $request = array_filter($request);

        foreach($request as $key=>$item){

            $foods[$key]['foodName'] = DB::table('foods')->where('id',$key)->first()->name;
            $foods[$key]['price'] = DB::table('foods')->where('id',$key)->first()->price;
            $foods[$key]['foodNumber'] = $item;
        }

        $foods = array_values($foods);
//        dd($foods);
        return view('cashierRec',compact('foods','restaurant'));

    }

    public function postCashierReceipt(Request $request){
        $foods = [];
        $orders = DB::table('orders')->where('created_at','>',Carbon::today()->addHours(7)->toDateTimeString())->
        where('created_at','<',Carbon::tomorrow()->toDateTimeString())->get();
        if( count($orders) == 0 && Carbon::now() > Carbon::today()->addHours(7)){
            if(Cache::has('order_number')){
                Cache::forget('order_number');
            }
        }
        if(!Cache::has('order_number')){
            Cache::put('order_number',1,1440);
        }else{
            Cache::put('order_number',Cache::get('order_number')+1,1440);
        }
        $orderRequest = Cache::get('orderRequest');

        $restaurant = DB::table('restaurants')->first();
        unset($orderRequest['_token']);
        $orderRequest = array_filter($orderRequest);

        foreach($orderRequest as $key=>$item){

            $foods[$key]['foodName'] = DB::table('foods')->where('id',$key)->first()->name;
            $foods[$key]['price'] = DB::table('foods')->where('id',$key)->first()->price;
            $foods[$key]['foodNumber'] = $item;
        }

        $foods = array_values($foods);



        if(is_null($request->table_id)){
            $request['table_id'] = null;
        }

        if(!is_null($request->description)){
            DB::table('orders')->insert([
                'order'=> serialize($foods),
                'price'=> $request->price,
                'table_id'=> $request->table_id,
                'info'=> $request->description,
                'order_number' => Cache::get('order_number'),
                'token' => Session::token(),
                'created_at'=>Carbon::now()
            ]);
        }else{
            DB::table('orders')->insert([
                'order'=> serialize($foods),
                'price'=> $request->price,
                'table_id'=> $request->table_id,
                'order_number' => Cache::get('order_number'),
                'token' => Session::token(),
                'created_at'=>Carbon::now()->toDateTimeString()
            ]);
        }
// TODO Add ajax request to index.blade page
        for($i=0;$i<count($foods);$i++){
            $sold = DB::table('foods')->where('name',$foods[$i]['foodName'])->first()->sold;
            DB::table('foods')->where('name',$foods[$i]['foodName'])
                ->update(['sold'=> $sold + $foods[$i]['foodNumber']]);
        }
        if(DB::table('managers')->first()->original == 0) {

            $id = DB::table('orders')->orderBy('id', 'desc')->first()->id;
            if ($id < 51 && $id >= 50) {
                DB::table('messages')->insert([
                    'message' => 'مشترک گرامی، تنها ۱۰۰ سفارش دیگر قابل ثبت می باشد. برای خرید نسخه کامل این برنامه، با شماره ۰۹۱۰۴۹۶۳۷۳۴ و یا ۰۹۳۷۱۸۶۹۵۶۸ تماس بگیرید',
                    'created_at'=>Carbon::now()
                ]);
            } else if ($id < 101 && $id >= 100) {
                DB::table('messages')->insert([
                    'message' => 'مشترک گرامی، تنها ۵۰ سفارش دیگر قابل ثبت می باشد. برای خرید نسخه کامل این برنامه، با شماره ۰۹۱۰۴۹۶۳۷۳۴ و یا ۰۹۳۷۱۸۶۹۵۶۸ تماس بگیرید',
                    'created_at'=>Carbon::now()
                ]);
            } else if ($id >= 130 && $id < 131) {
                DB::table('messages')->insert([
                    'message' => 'مشترک گرامی، تنها ۲۰ سفارش دیگر قابل ثبت می باشد. برای خرید نسخه کامل این برنامه، با شماره ۰۹۱۰۴۹۶۳۷۳۴ و یا ۰۹۳۷۱۸۶۹۵۶۸ تماس بگیرید',
                    'created_at'=>Carbon::now()
                ]);
            } else if ($id >= 150) {
                DB::table('managers')->where('id', '1')->update(['expired' => 1]);
                // TODO Send expiration email to user

            }
        }
//        }
        return redirect()->route('orders') ;

    }

}
