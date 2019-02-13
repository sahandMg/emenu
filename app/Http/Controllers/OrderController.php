<?php

namespace App\Http\Controllers;

use App\Repo\Timing;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Morilog\Jalali\Jalalian;

class OrderController extends Controller
{
    // data per page
    public $skip = 50;
    public function __construct()
    {
    $this->middleware(['activated','auth:manager,cashier'])->except('hasTable','sendOrder','paidStat');

    }
    public function orders(){



       // $ordersNum = DB::table('orders')->where('created_at','>=',Carbon::today()->toDateTimeString())
        $ordersNum = DB::table('orders')
            // ->where('created_at','<',Carbon::tomorrow()->toDateTimeString())
            ->orderBy('id','desc')->count();

            // gives last 500 queries
            if($ordersNum > 500){

                $ordersNum = 500;
            }

            $count = ceil($ordersNum / $this->skip);
            $restaurant = DB::table('restaurants')->first();
        return view('orders',compact('count','restaurant'));
    }
    public function ytd(){
        $restaurant = DB::table('restaurants')->first();
        return view('ytd_orders',compact('restaurant'));
    }

    public function getOldOrders(){
        $orders = DB::table('orders')->where('created_at','>=',Carbon::yesterday()->toDateTimeString())
        ->where('created_at','<',Carbon::today()->toDateTimeString())->orderBy('id','dsc')
        ->skip($this->skip * ($request->num - 1))->take($this->skip)->get();
        $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
           if(Carbon::now()->diffInHours($orders[$i]['created_at'])>24){
                $orders[$i]['day'] = Carbon::now()->diffInDays($orders[$i]['created_at']);
            }
            else if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
                $orders[$i]['hour'] = Carbon::now()->diffInHours($orders[$i]['created_at']);
            }
            else{
                $orders[$i]['minute'] =  Carbon::now()->diffInMinutes($orders[$i]['created_at']);
            }
        }
        return $orders;
    }

    public function getOrders(Request $request){


        // $orders = DB::table('orders')->where('created_at','>=',Carbon::today()->toDateTimeString())
        $orders = DB::table('orders')
            // ->where('created_at','<',Carbon::today()->addHour(24)->toDateTimeString())
            ->orderBy('id','dsc')
            ->skip($this->skip * ($request->num - 1))->take($this->skip)->get();

        $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
            if(Carbon::now()->diffInHours($orders[$i]['created_at'])>24){
                $orders[$i]['day'] = Carbon::now()->diffInDays($orders[$i]['created_at']);
            }
            else if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
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
            $orders = DB::table('orders')
            // ->where('created_at','>=',Carbon::yesterday()->toDateTimeString())
            // ->where('created_at','<',Carbon::today()->toDateTimeString())
            ->orderBy('id','dsc')
            ->skip($this->skip * ($request->num - 1))->take($this->skip)->get();
        }else{
            $orders = DB::table('orders')
            // ->where('created_at','>=',Carbon::today()->toDateTimeString())
                // ->where('created_at','<',Carbon::tomorrow()->toDateTimeString())
                ->orderBy('id','dsc')
                ->skip($this->skip * ($request->num - 1))->take($this->skip)->get();

        }
        $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
           if(Carbon::now()->diffInHours($orders[$i]['created_at'])>24){
                $orders[$i]['day'] = Carbon::now()->diffInDays($orders[$i]['created_at']);
            }
            else if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
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
            $orders = DB::table('orders')
            // ->where('created_at','>=',Carbon::yesterday()->toDateTimeString())
            // ->where('created_at','<',Carbon::today()->toDateTimeString())
            ->orderBy('id','desc')
            ->skip($this->skip * ($request->num - 1))->take($this->skip)->get();
        }else{
            $orders = DB::table('orders')
                // ->where('created_at','>=',Carbon::today()->toDateTimeString())
                // ->where('created_at','<',Carbon::tomorrow()->toDateTimeString())
                ->orderBy('id','desc')
                ->skip($this->skip * ($request->num - 1))->take($this->skip)->get();

        }

        $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
           if(Carbon::now()->diffInHours($orders[$i]['created_at'])>24){
                $orders[$i]['day'] = Carbon::now()->diffInDays($orders[$i]['created_at']);
            }
            else if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
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
            $orders = DB::table('orders')
            // ->where('created_at','>=',Carbon::yesterday()->toDateTimeString())
            // ->where('created_at','<',Carbon::today()->toDateTimeString())
            ->orderBy('id','dsc')
            ->skip($this->skip * ($request->num - 1))->take($this->skip)->get();
        }else{
            $orders = DB::table('orders')
            // ->where('created_at','>=',Carbon::today()->toDateTimeString())
            // ->where('created_at','<',Carbon::tomorrow()->toDateTimeString())
            ->orderBy('id','desc')
                ->skip($this->skip * ($request->num - 1))->take($this->skip)->get();

        }
        $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
           if(Carbon::now()->diffInHours($orders[$i]['created_at'])>24){
                $orders[$i]['day'] = Carbon::now()->diffInDays($orders[$i]['created_at']);
            }
            else if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
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
                    'orderCode'=> rand(1000,10000),
                    'token' => Session::token(),
                    'created_at'=>Carbon::now()->toDateTimeString()
                ]);
            }else{
                DB::table('orders')->insert([
                    'order'=> serialize($request->order),
                    'price'=> $price,
                    'table_id'=> $request->table_id,
                    'order_number' => Cache::get('order_number'),
                    'orderCode'=> rand(1000,10000),
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
                    DB::table('managers')->update(['expired' => 1]);
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

         $paidANDorderNumber = DB::table('orders')->where('id',$request->id)->select('paid','order_number')->get()->toArray();

         $method = DB::table('restaurants')->first()->payMethod;
        $delivered =  DB::table('orders')->where('id',$request->id)->first()->delivered;
        $pending =  DB::table('orders')->where('id',$request->id)->first()->pending;
        return [$paidANDorderNumber[0]->paid,$method,$delivered,$pending,$paidANDorderNumber[0]->order_number];


    }
    public function getOrderId(){

        return DB::table('orders')->orderBy('id','desc')->first()->id;
    }

    public function indexCashier (){

        $types = DB::table('categories')->orderBy('priority','asc')->get()->pluck('type')->toArray();
        $foods = [];
        for($i=0;$i<count($types);$i++){

            $foods[$i] = DB::table('foods')->where([['category',$types[$i]],['valid','1']])->get()->toArray();

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
                'orderCode'=>rand(1000,10000),
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
                'orderCode'=>rand(1000,10000),
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
                DB::table('managers')->update(['expired' => 1]);
                // TODO Send expiration email to user

            }
        }
//        }
        return redirect()->route('orders') ;

    }

public function cancel(Request $request){

    try{
        $cancels = DB::table('cancels')->count();
        $cancels = ceil($cancels / $this->skip);
    // $dates = DB::table('cancels')->orderBy('id','dsc')->pluck('created_at');
    // for($i=0 ; $i<$cancels);$i++){
    //     $cancels[$i]->created_at = Jalalian::fromCarbon(Carbon::parse($dates[$i]))->toString();
    // }
    }catch(\Exception $ex){
        dd($ex);
        return ('سفارشی لغو نشده است. '.'<a href="orders">بازگشت</a>');
    }

    $restaurant = DB::table('restaurants')->first();
    return view('cancel',compact('cancels','restaurant'));
}
/*
    Gets order id from request
find the order in orders table
copy it to cancels table
remove order from orders table
*/
    public function post_cancel(Request $request){

        $num = $request->num;
        $order = DB::table('orders')->where('id',$request->id)->first();
        try{
            DB::table('cancels')->insert([
                'order'=> $order->order,
                'table_id' => $order->table_id,
                'price' => $order->price,
                'info' => $order->info,
                'delivered' => $order->delivered,
                'pending' => $order->pending,
                'paid' => $order->paid,
                'order_number' => $order->order_number,
                'orderCode' => $order->orderCode,
                'token' => $order->token,
                'user_id' => $order->user_id,
                'created_at' => Carbon::now()

                ]);
        }catch(\Exception $ex){

            return $ex;
        }


        DB::table('orders')->where('id',$request->id)->delete();
        if(!Cache::has('order_number')){
            Cache::put('order_number',1,1440);
        }else{
            Cache::put('order_number',Cache::get('order_number')-1,1440);
        }

        $orders = DB::table('orders')
        ->where('created_at','>=',Carbon::today()->toDateTimeString())
        ->where('created_at','<',Carbon::today()->addHour(24)->toDateTimeString())
        ->orderBy('id','asc')->chunkById(1200,function($queries){
            foreach($queries as $keys => $query){
                DB::table('orders')->where('id',$query->id)->update(['order_number'=>$keys + 1]);
            }
        });
        $orders = DB::table('orders')
        // ->where('created_at','>=',Carbon::today()->toDateTimeString())
        // ->where('created_at','<',Carbon::today()->addHour(24)->toDateTimeString())
        ->orderBy('id','desc')
        ->skip($this->skip * ($request->num - 1))->take($this->skip)->get();

        if($request->has('old')){

            return $this->getOldOrders();
        }else{

             $orders = json_decode($orders,true);
        for ($i=0;$i<count($orders) ; $i++){
            $orders[$i]['order'] = unserialize($orders[$i]['order']);
         if(Carbon::now()->diffInHours($orders[$i]['created_at'])>24){
                $orders[$i]['day'] = Carbon::now()->diffInDays($orders[$i]['created_at']);
            }
            else if(Carbon::now()->diffInMinutes($orders[$i]['created_at'])>60){
                $orders[$i]['hour'] = Carbon::now()->diffInHours($orders[$i]['created_at']);
            }
            else{
                $orders[$i]['minute'] =  Carbon::now()->diffInMinutes($orders[$i]['created_at']);
            }
        }

            return $orders;
        }

    }

    public function getCancel(Request $request){

        $orders = DB::table('cancels')->orderBy('id','desc')
        ->skip($this->skip * ($request->num - 1))->take($this->skip)->get();

        try{
         $dates = DB::table('cancels')->orderBy('id','desc')
        ->skip($this->skip * ($request->num - 1))->take($this->skip)->pluck('created_at');

        for($i=0 ; $i<count($orders);$i++){
            $orders[$i]->created_at = Jalalian::fromCarbon(Carbon::parse($dates[$i]))->toString();
            $orders[$i]->order = unserialize($orders[$i]->order);
        }
        }catch(\Exception $ex){

            return ('سفارشی لغو نشده است. '.'<a href="orders">بازگشت</a>');
        }
    return $orders;

}
}
