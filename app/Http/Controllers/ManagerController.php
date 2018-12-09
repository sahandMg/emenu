<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Morilog\Jalali\Jalalian;


class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:manager')->except('getStat','menu','message','GetMsg');
        $this->middleware('activated')->except('getStat');
    }

    public function menu(){

        $types = DB::table('categories')->orderBy('priority','asc')->get()->pluck('type');
        $foods = [];
        for($i=0;$i<count($types);$i++){

            $foods[$i] = DB::table('foods')->where('category',$types[$i])->get();

        }
        $cats = DB::table('categories')->orderBy('priority','asc')->get();
        return view('menu',compact('foods','types','cats'));
    }

    public function settings(){
        $restaurant = DB::table('restaurants')->first();
        return view('setting',compact('restaurant'));
    }

    public function post_settings(Request $request){

        $restaurant =  DB::table('restaurants')->first();

        if(!is_null($request->printer)){

            DB::table('restaurants')->update(['printer' => $request->printer]);
        }
        if(!is_null($request->tax)){

            DB::table('restaurants')->update(['tax' => $request->tax]);
        }

        if(!is_null($request->name)){

            DB::table('restaurants')->update(['name' => $request->name]);
        }
        if(!is_null($request->tel)){

            DB::table('restaurants')->update(['tel' => $request->tel]);
        }
        if(!is_null($request->logo)){
            $extension = $request->file('logo')->getClientOriginalExtension();

            if($extension != 'jpg' && $extension != 'png' && $extension != 'jpeg' && $extension != 'svg'){

                return redirect()->back()->with(['error'=>'فایل آپلود شده از نوع عکس نیست']);
            }
            $request->file('logo')->move('images',time().'-'.$request->file('logo')->getClientOriginalName());
//            unlink('storage/images/'.DB::table('restaurants')->find('1')->image);
            DB::table('restaurants')->update(['logo' => time().'-'.$request->file('logo')->getClientOriginalName()]);
        }
        if(!is_null($request->address)){

            DB::table('restaurants')->update(['address' => $request->address]);
        }

          if($request->has('orderCode')){

           if($request->orderCode == 'on'){

                DB::table('restaurants')->update(['orderCode' => '1']);
            }
        }else{
            DB::table('restaurants')->update(['orderCode' => '0']);
        }
        
        if($request->has('payMethod')){

            if($request->payMethod == 'on'){

                DB::table('restaurants')->update(['payMethod' => '1']);
            }
        }else{
            DB::table('restaurants')->update(['payMethod' => '0']);
        }
        if($request->has('tableCounting')){

            if($request->tableCounting == 'on'){

                DB::table('restaurants')->update(['tableCounting' => '1']);
            }
        }else{
            DB::table('restaurants')->update(['tableCounting' => '0']);
        }
        if($request->has('image')){
            $extension = $request->file('image')->getClientOriginalExtension();

            if($extension != 'jpg' && $extension != 'png' && $extension != 'jpeg' && $extension != 'svg'){

                return redirect()->back()->with(['error'=>'فایل آپلود شده از نوع عکس نیست']);
            }
            $request->file('image')->move('images',time().'-'.$request->file('image')->getClientOriginalName());
//            unlink('storage/images/'.DB::table('restaurants')->find('1')->image);
            DB::table('restaurants')->update(['image' => time().'-'.$request->file('image')->getClientOriginalName()]);
        }
        if($request->has('username')){

            DB::table('cashiers')->insert([
                'username'=>$request->username,
                'password'=>Hash::make($request->password),
                'reset_password'=> str_random(10),
                'created_at' => Carbon::now()
            ]);
            Session::put('cashiers' ,DB::table('cashiers')->get());
            return redirect()->back()->with(['message'=>'تغییرات ذخیره شد',]);
        }
        DB::table('restaurants')->update(['complete' => 1]);

        return redirect()->back()->with(['message'=>'تغییرات ذخیره شد']);



    }

    public function getStat(){
        $orders = DB::table('orders')->where('created_at','>=',Carbon::today()->toDateTimeString())
            ->where('created_at','<',Carbon::today()->addHour(24)->toDateTimeString())->get();
        return $orders;
    }

    public function message(){

        return view('message');
    }

    public function GetMsg(){

        $messages = DB::table('messages')->get();
        foreach ($messages as $message){
            $message->created_at = Jalalian::forge(Carbon::parse($message->created_at))->toString();
        }
        return $messages;
    }
}
