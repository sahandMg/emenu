<?php

namespace App\Http\Controllers;

use App\Repo\Timing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{

    public function __construct()
    {
        $this->middleware(['activated','auth:manager,cashier']);
    }
    public function addFood(Request $request){

        $this->validate($request,[
            'foodName' => 'required|unique:foods',
            'foodPrice' => 'required',
            'foodDes' => 'required',
            'foodImage'=>'required',
            'foodCategory'=>'required|filled'
        ]);
        $extension = $request->file('foodImage')->getClientOriginalExtension();

            if($extension != 'jpg' && $extension != 'png' && $extension != 'jpeg'){

                return redirect()->back()->with(['message'=>'فایل آپلود شده از نوع عکس نیست']);
            }

           if(!is_null(DB::table('foods')->where('name',$request->foodName)->first())){

                return redirect()->back()->with(['message'=>'نام غذا تکراری است']);
           }

        DB::table('foods')->insert([
            'name' => $request->foodName,
            'price' => $request->foodPrice,
            'description' => $request->foodDes,
            'category' => $request->foodCategory,
            'category_id' => DB::table('categories')->where('categories.type',$request->foodCategory)->first()->id,
            'image' => time().'-'.$request->file('foodImage')->getClientOriginalName(),
            'created_at'=>Carbon::now()
        ]);
        $request->file('foodImage')->move('images',time().'-'.$request->file('foodImage')->getClientOriginalName());
//
        return redirect()->back();
    }

    public function validFood(Request $request){
        $stat = DB::table('foods')->where('id',$request->id)->first()->valid;
        DB::table('foods')->where('id',$request->id)->update(['valid'=>!$stat]);
        return 200;
    }

    public function removeFood(Request $request){
        $food =  DB::table('foods')->where('id',$request->id)->first();
//        unlink(public_path('images/'.$food->image));
        DB::table('foods')->where('id',$request->id)->delete();
        return redirect()->back();
    }

    public function editFood($id,Request $request){

        $type = DB::table('foods')->where('id',$id)->first();
        return view('editFood',compact('type'));
    }

    public function post_editFood($id,Request $request){
        $food =  DB::table('foods')->where('id',$id)->first();
        if(!is_null($request->foodName)){
            DB::table('foods')->where('id',$id)->update(['name'=>$request->foodName]);
        }
        if(!is_null($request->foodDes)){
            DB::table('foods')->where('id',$id)->update(['description'=>$request->foodDes]);
        }
        if(!is_null($request->foodPrice)){
            DB::table('foods')->where('id',$id)->update(['price'=>$request->foodPrice]);
        }
        if(!is_null($request->foodCategory)){
            DB::table('foods')->where('id',$id)->update(['category'=>$request->foodCategory]);
        }
        if(!is_null($request->foodImage)){
//            unlink(public_path('images/'. DB::table('foods')->where('id',$id)->first()->image));
            DB::table('foods')->where('id',$id)->first()->image = time().'-'.$request->file('foodImage')->getClientOriginalName();
            $request->file('foodImage')->move('images',time().'-'.$request->file('foodImage')->getClientOriginalName());
            DB::table('foods')->where('id',$id)->update(['image'=>time().'-'.$request->file('foodImage')->getClientOriginalName()]);
        }
//        return redirect()->back()->with(['message'=>'تغییرات ذخیره شد.']);
        return redirect()->route('menu');


    }

    public function addCategory(Request $request){

        if(! is_null(DB::table('categories')->where('type',$request->category)->first())){

            return redirect()->back()->with(['categoryError'=>'نام دسته تکراری است']);
        }

        if(array_search($request->priority,DB::table('categories')->get()
            ->pluck('priority')->toArray())){

            return redirect()->back()->with(['categoryError'=>'اولویت دسته بندی تکراری است']);
        }

        DB::table('categories')->insert([
            'type'=>$request->category,
            'priority'=>$request->priority,
            'created_at'=>Carbon::now()
        ]);
        Timing::presetTime();
//        $cat->type = $request->category;
//        $cat->priority = $request->priority;
//        $cat->save();
        return redirect()->back()->with(['nav'=>'addCategory']);


    }

    public function removeCategory(Request $request){

        DB::table('foods')->where('category_id',$request->id)->delete();
        DB::table('categories')->where('id',$request->id)->delete();
        return 200;
    }

    public function getCats(){

        return  DB::table('categories')->get()->pluck('type');

    }

    public function getFoods(){

        return DB::table('foods')->pluck('name');
    }

    public function catPriority(Request $request){

        $cats = DB::table('categories')->get()->pluck('type');


        for($i=0;$i<count($cats);$i++){

            $priority = $request[$cats[$i]];
            if(!is_null($priority)){

                DB::table('categories')->where('type',$cats[$i])->update(['priority'=>$priority]);

            }
        }
        if(!is_null($request->removeList)){
            $removeList = explode(',',$request->removeList);
            for($t=0;$t<count($removeList);$t++){
                DB::table('categories')->where('id',$removeList[$t])->delete();
                DB::table('foods')->where('category_id',$removeList[$t])->delete();
            }
        }
        return redirect()->back()->with(['nav'=>'addCategory']);
    }

    public function resources(){

    }

}
