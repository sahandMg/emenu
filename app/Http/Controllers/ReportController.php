<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['activated','auth:manager']);
    }
    public function report(){
        $restaurant = DB::table('restaurants')->first();
        $arr = [];
        if(!Cache::has('order')){
            $orders = DB::table('orders')->where('created_at','>=',Carbon::today()->toDateTimeString())
                ->where('created_at','<',Carbon::today()->addHour(24)->toDateTimeString())->pluck('order');
        }
        else{
            $orders = Cache::get('order');
        }

        if(sizeof($orders) == 0){

            return '<h1 style="text-align: center">'.'گزارشی برای امروز ثبت نشده است'.'</h1>'.'<a href="settings" >'.'بازگشت'.'</a>';
        }


        for($i=0;$i<count($orders);$i++){

            $contents[$i] = unserialize($orders[$i]);

            for($t=0;$t<count($contents[$i]);$t++){
                $names[$i][$t] = $contents[$i][$t]['foodName'];
                $numbers[$i][$t] = $contents[$i][$t]['foodNumber'];
            }
            $foods[$i] = array_combine($names[$i],$numbers[$i]);

            foreach ($foods[$i] as $key=>$value){


                if (isset($arr[$key]))
                {
                    $arr[$key] += $value;
                }
                else
                {
                    $arr[$key] = $value;
                }
            }
        }
        $lastYear = Carbon::parse(DB::table('orders')->orderBy('id','dsc')->first()->created_at);
        $lastYear = Jalalian::fromCarbon($lastYear)->getYear();
        $firstYear = Carbon::parse(DB::table('orders')->orderBy('id','asc')->first()->created_at);
        $firstYear = Jalalian::fromCarbon($firstYear)->getYear();
        Cache::put('foodList',$arr,20);
        return view('report',compact('arr','firstYear','lastYear','restaurant'));
    }

    public function post_report(Request $request){

        if(!$request->has('month1') && !$request->has('day1')){
            $date1 = (new Jalalian($request->year1,1,1))->toCarbon()->toDateTimeString();
            $date2 = (new Jalalian($request->year2,1,1))->toCarbon()->toDateTimeString();

        }elseif (!$request->has('day1')){
            $date1 = (new Jalalian($request->year1,$request->month1,1))->toCarbon()->toDateTimeString();
            $date2 = (new Jalalian($request->year2,$request->month2,1))->toCarbon()->toDateTimeString();

        }else{
            $date1 = (new Jalalian($request->year1,$request->month1,$request->day1))->toCarbon()->toDateTimeString();
            $date2 = (new Jalalian($request->year2,$request->month2,$request->day2))->toCarbon()->toDateTimeString();

        }
        if(!Carbon::parse($date2)->greaterThan(Carbon::parse($date1))){
            return redirect()->back()->with(['message'=>'تاریخ، نادرست وارد شده است']);
        }
        $orders = DB::table('orders')->where('created_at','>=',$date1)->where('created_at','<',$date2)->get();
        if(count($orders) == 0){
            return redirect()->back()->with(['message'=>'سفارشی در این تاریخ ثبت نشده است']);
        }


        Cache::forget('order');
        $date1 = (new Jalalian($request->year1,$request->month1,$request->day1))->toCarbon()->toDateTimeString();
        $date2 = (new Jalalian($request->year2,$request->month2,$request->day2))->toCarbon()->toDateTimeString();
        $orders = DB::table('orders')->where('created_at','>=',$date1)->where('created_at','<',$date2)->pluck('order');

        Cache::put('order',$orders,1);

        return redirect()->route('report');

    }
//
    public function chart(){

        return view('chart');
    }

    public function post_chart(Request $request){


        $foodName = $request->food;
        $arr = [];
        if(!$request->has('month1') && !$request->has('day1')){
            $date1 = (new Jalalian($request->year1,1,1))->toCarbon()->toDateTimeString();
            $date2 = (new Jalalian($request->year2+1,1,1))->toCarbon()->toDateTimeString();

        }elseif (!$request->has('day1')){
            $date1 = (new Jalalian($request->year1,$request->month1,1))->toCarbon()->toDateTimeString();
            if($request->month2 == 12){
                $date2 = (new Jalalian($request->year2+1,1,1))->toCarbon()->toDateTimeString();
            }else{

                $date2 = (new Jalalian($request->year2,$request->month2,1))->toCarbon()->toDateTimeString();
            }

        }else{
            $date1 = (new Jalalian($request->year1,$request->month1,$request->day1))->toCarbon()->toDateTimeString();
            $date2 = (new Jalalian($request->year2,$request->month2,$request->day2))->toCarbon()->toDateTimeString();

        }

        if(!Carbon::parse($date2)->greaterThan(Carbon::parse($date1))){
            return redirect()->back()->with(['message'=>'تاریخ، نادرست وارد شده است']);
        }

            $orders = DB::table('orders')->where('created_at','>=',$date1)->where('created_at','<',$date2)->get();
            if(count($orders) == 0){
                return redirect()->back()->with(['message'=>'هیچ سفارشی در این تاریخ ثبت نشده است']);
            }

            $dates = DB::table('orders')->pluck('created_at')->toArray();
        for($i=0;$i<count($orders);$i++){

            $contents[$i] = unserialize($orders[$i]->order);
            for($t=0;$t<count($contents[$i]);$t++){
                $names[$i][$t] =   $contents[$i][$t]['foodName'];
                $numbers[$i][$t] = $contents[$i][$t]['foodNumber'];
                $foodDates[$i][$t] = [$names[$i][$t]=>$orders[$i]->created_at,'number'=>$numbers[$i][$t]];

            }

        }

        if($request->duration == 'yearly'){

            for($i=0;$i<count($foodDates);$i++){

                for($t=0;$t<count($foodDates[$i]);$t++){
                    if(array_keys($foodDates[$i][$t])[0]== $foodName){
                        $mydate = Carbon::parse($foodDates[$i][$t][$foodName])->toDateTimeString();
                        $arr[$i]['date'] = Jalalian::fromDateTime($mydate)->getYear();
                        $arr[$i]['number'] = $foodDates[$i][$t]['number'];
                    }

                }

            }

            if(count($arr) == 0){

                return redirect()->back()->with(['message'=>'این غذا در این تاریخ فروخته نشده است'])->withInput();
            }


            $list = array_values($arr);
            $sum = 0;
            for($p=0;$p<count($list);$p++){
                $time[$p] = $list[$p]['date'];

            }
            $time = array_unique($time);
            $time = array_values($time);

            for($s=0;$s<count($time);$s++) {
                for($p=0;$p<count($list);$p++){

                    if ($list[$p]['date'] === $time[$s]) {
                        $sum = $sum + $list[$p]['number'];
                    }
                }
                $total[$time[$s]] = $sum;
                $sum = 0;
            }

            Cache::put('chartValue',array_values($total),40);
            Cache::put('chartTime',$time,40);
            Cache::put('chartName',$foodName,40);
            return redirect()->route('chart');

        }elseif ($request->duration == 'daily'){

            if(Carbon::parse($date2)->diffInDays(Carbon::parse($date1))> 31){
                return redirect()->back()->with(['message'=>'حداکثر بازه زمانی، ۱ ماه می باشد']);
            }

            for($i=0;$i<count($foodDates);$i++){

                for($t=0;$t<count($foodDates[$i]);$t++){
                    if(array_keys($foodDates[$i][$t])[0]== $foodName){
                        $mydate = Carbon::parse($foodDates[$i][$t][$foodName])->toDateTimeString();
                        $arr[$i]['date'] = Jalalian::fromDateTime($mydate)->format('%B %d');
                        $arr[$i]['number'] = $foodDates[$i][$t]['number'];
                    }

                }

            }
            if(count($arr) == 0){

                return redirect()->back()->with(['message'=>'این غذا در این تاریخ فروخته نشده است'])->withInput();
            }


            $list = array_values($arr);
            $sum = 0;
            for($p=0;$p<count($list);$p++){
                $time[$p] = $list[$p]['date'];

            }
            $time = array_unique($time);
            $time = array_values($time);

            for($s=0;$s<count($time);$s++) {
            for($p=0;$p<count($list);$p++){

                    if ($list[$p]['date'] === $time[$s]) {
                        $sum = $sum + $list[$p]['number'];
                    }
                }
                $total[$time[$s]] = $sum;
                $sum = 0;
            }
            Cache::put('chartValue',array_values($total),40);
            Cache::put('chartTime',$time,40);
            Cache::put('chartName',$foodName,40);
            return redirect()->route('chart');

        }elseif ($request->duration == 'monthly'){

            if(Carbon::parse($date2)->diffInMonths(Carbon::parse($date1))> 12){
                return redirect()->back()->with(['message'=>'حداکثر بازه زمانی، ۱۲ ماه می باشد']);
            }
            for($i=0;$i<count($foodDates);$i++){

                for($t=0;$t<count($foodDates[$i]);$t++){
                    if(array_keys($foodDates[$i][$t])[0]== $foodName){
                        $mydate = Carbon::parse($foodDates[$i][$t][$foodName])->toDateTimeString();
                        $arr[$i]['date'] = Jalalian::fromDateTime($mydate)->format('%y %B');
                        $arr[$i]['number'] = $foodDates[$i][$t]['number'];
                    }

                }

            }

            if(count($arr) == 0){

                return redirect()->back()->with(['message'=>'این غذا در این تاریخ فروخته نشده است'])->withInput();
            }


            $list = array_values($arr);
            $sum = 0;
            for($p=0;$p<count($list);$p++){
                $time[$p] = $list[$p]['date'];

            }
            $time = array_unique($time);
            $time = array_values($time);

            for($s=0;$s<count($time);$s++) {
                for($p=0;$p<count($list);$p++){

                    if ($list[$p]['date'] === $time[$s]) {
                        $sum = $sum + $list[$p]['number'];
                    }
                }
                $total[$time[$s]] = $sum;
                $sum = 0;
            }


            Cache::put('chartValue',array_values($total),40);
            Cache::put('chartTime',$time,40);
            Cache::put('chartName',$foodName,40);
            return redirect()->route('chart');
        }


    }

    public function chartSetting(){

        $foods = DB::table('foods')->pluck('name');
        $lastYear = Carbon::parse(DB::table('orders')->orderBy('id','dsc')->first()->created_at);
        $lastYear = Jalalian::fromCarbon($lastYear)->getYear();
        $firstYear = Carbon::parse(DB::table('orders')->orderBy('id','asc')->first()->created_at);
        $firstYear = Jalalian::fromCarbon($firstYear)->getYear();
        return view('chartSetting',compact('foods','lastYear','firstYear'));
    }

    public function ChartPrint(){

        $pdf = PDF::loadView('pdf.chartPrint');

//        return view('pdf.chartPrint');
        return $pdf->stream('document.pdf');

    }

}
