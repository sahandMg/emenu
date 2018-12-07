<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;

class CashierController extends Controller
{

    public function getCashiers(){

        return DB::table('cashiers')->get();
    }

    public function editCashiers(Request $request){

//
        if(!is_null($request->name)){

            DB::table('cashiers')->where('id',$request->id)->update(['username'=>$request->name]);
        }
        if(!is_null($request->password)){

            DB::table('cashiers')->where('id',$request->id)->update(['password'=>Hash::make($request->password)]);
        }
        return 200;
    }

    public function removeCashiers(Request $request){

        DB::table('cashiers')->where('id',$request->id)->delete();

        return DB::table('cashiers')->get();
    }
}
