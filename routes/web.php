<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|   Salam
*/

use App\Activation;
use App\Repo\Timing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;
use Mike42\Escpos\CapabilityProfiles\DefaultCapabilityProfile;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use App\Repo\item;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintBuffers\ImagePrintBuffer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Mike42\Escpos\CapabilityProfiles\EposTepCapabilityProfile;


Route::get('fa',function (){

   return Hash::make('reset_password');
});

Route::get('font',function (){

    $connector = new WindowsPrintConnector("GP-80250N Series");
    $printer = new Printer($connector);

    try {
        $tux = EscposImage::load(public_path('tux.png'), false);

        $printer -> text("These example images are printed with the older\nbit image print command. You should only use\n\$p -> bitImage() if \$p -> graphics() does not\nwork on your printer.\n\n");

        $printer -> bitImage($tux);
        $printer -> text("Regular Tux (bit image).\n");
        $printer -> feed();

        $printer -> bitImage($tux, Printer::IMG_DOUBLE_WIDTH);
        $printer -> text("Wide Tux (bit image).\n");
        $printer -> feed();

        $printer -> bitImage($tux, Printer::IMG_DOUBLE_HEIGHT);
        $printer -> text("Tall Tux (bit image).\n");
        $printer -> feed();

        $printer -> bitImage($tux, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
        $printer -> text("Large Tux in correct proportion (bit image).\n");
    } catch (Exception $e) {
        /* Images not supported on your PHP, or image file not found */
        $printer -> text($e -> getMessage() . "\n");
    }

    $printer -> cut();
    $printer -> close();

});

Route::get('print',function (){
    /* Print a "Hello world" receipt" */
//    $connector = new FilePrintConnector(public_path("/prints/print"));
    $connector = new WindowsPrintConnector("GP-80250N Series");

    $printer = new Printer($connector);
    $tux = EscposImage::load(public_path("tux.png"), false);

    $printer -> text("These example images are printed with the older\nbit image print command. You should only use\n\$p -> bitImage() if \$p -> graphics() does not\nwork on your printer.\n\n");

    $printer -> bitImage($tux);
    $printer -> text("Regular Tux (bit image).\n");
    $printer -> feed();

    $printer -> bitImage($tux, Printer::IMG_DOUBLE_WIDTH);
    $printer -> text("Wide Tux (bit image).\n");
    $printer -> feed();

    $printer -> bitImage($tux, Printer::IMG_DOUBLE_HEIGHT);
    $printer -> text("Tall Tux (bit image).\n");
    $printer -> feed();

    $printer -> bitImage($tux, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
    $printer -> text("Large Tux in correct proportion (bit image).\n");

    $printer -> cut();
    $printer -> pulse();

    $printer -> close();

});

Route::get('tester','TestController@tester');
Route::get('serial','TestController@serial');



Route::get('/',function (){

    if(is_null(DB::table('managers')->first())){
        return redirect()->route('activation');
    }else{

        return redirect()->route('login');
    }
})->middleware(['guest','device']);


Route::get('menu',['as'=>'main','uses'=>'PageController@index']);
Route::get('main-page',['uses'=>'PageController@indexPage'])->name('indexPage');
Route::get('bill',['as'=>'bill','uses'=>'PageController@generateBill']);
Route::get('contact',['as'=>'contact','uses'=>'PageController@contact']);
Route::get('order/status','PageController@orderStatus')->name('orderStatus');
Route::post('remove-session','PageController@rmSession')->name('rmSession');
Route::get('test-printer','PageController@testPrinter')->name('testPrinter');
Route::get('tax','PageController@Tax')->name('getTax');


Route::get('user-bill',['as'=>'userBill','uses'=>'UserController@userBill']);


Route::get('generate','AuthController@codeGenerator');
Route::get('emenu/{code}','AuthController@checkMe');
Route::get('activation',['as'=>'activation','uses'=>'AuthController@activation']);
Route::post('activation',['as'=>'activation','uses'=>'AuthController@post_activation']);
Route::get('reset-activation',['as'=>'activationReset','uses'=>'AuthController@activationReset']);
Route::post('reset-activation',['as'=>'activationReset','uses'=>'AuthController@post_activationReset']);
Route::get('app/login',['as'=>'login','uses'=>'AuthController@login'])->middleware('guest');;
Route::post('app/login',['as'=>'login','uses'=>'AuthController@post_login']);
Route::get('app/logout',['as'=>'logout','uses'=>'AuthController@logout']);



Route::get('orders',['as'=>'orders','uses'=>'OrderController@orders']);
Route::get('orders/ytd',['as'=>'ytd','uses'=>'OrderController@ytd']);
Route::post('get-orders',['as'=>'getOrders','uses'=>'OrderController@getOrders']);
Route::post('get-old-orders',['as'=>'getOldOrders','uses'=>'OrderController@getOldOrders']);
Route::post('send-order','OrderController@sendOrder')->name('sendOrder');
Route::get('reset','OrderController@reset')->name('reset');
Route::post('delivered',['as'=>'delivered','uses'=>'OrderController@delivered']);
Route::post('pending',['as'=>'pending','uses'=>'OrderController@pending']);
Route::post('paid',['as'=>'paid','uses'=>'OrderController@paid']);
Route::get('paid-stat','OrderController@paidStat')->name('paidStat');
Route::get('get-id','OrderController@getOrderId')->name('getOrderId');
Route::get('cashier-menu',['as'=>'indexCashier','uses'=>'OrderController@indexCashier']);
Route::post('cashier-menu',['as'=>'indexCashier','uses'=>'OrderController@post_indexCashier']);
Route::get('cashier-receipt','OrderController@cashierReceipt')->name('cashierReceipt');
Route::post('cashier-receipt','OrderController@postCashierReceipt')->name('cashierReceipt');
Route::get('cancel-order','OrderController@cancel')->name('OrderCancel');
Route::post('cancel-order','OrderController@post_cancel')->name('OrderCancel');
Route::get('getCancel','OrderController@getCancel')->name('getCancel');



Route::get('report',['as'=>'report','uses'=>'ReportController@report']);
Route::post('report',['as'=>'report','uses'=>'ReportController@post_report']);
Route::get('report/chart',['as'=>'chart','uses'=>'ReportController@chart']);
Route::post('report/chart',['as'=>'chart','uses'=>'ReportController@post_chart']);
Route::get('chart/setting',['as'=>'chartSetting','uses'=>'ReportController@chartSetting']);
Route::get('chart/print',['as'=>'ChartPrint','uses'=>'ReportController@ChartPrint']);


Route::get('main',['as'=>'menu','uses'=>'ManagerController@menu']);
Route::get('get-stat','ManagerController@getStat')->name('getStat');
Route::get('settings',['as'=>'settings','uses'=>'ManagerController@settings']);
Route::post('settings',['as'=>'settings','uses'=>'ManagerController@post_settings']);
Route::get('get-msg',['as'=>'GetMsg','uses'=>'ManagerController@GetMsg']);
Route::get('message',['as'=>'message','uses'=>'ManagerController@message']);



Route::get('edit-food/{id}',['as'=>'editFood','uses'=>'FoodController@editFood']);
Route::post('edit-food/{id}',['as'=>'editFood','uses'=>'FoodController@post_editFood']);
Route::get('get-cats','FoodController@getCats')->name('getCats');
Route::get('get-foods','FoodController@getFoods')->name('getFoods');
Route::get('valid-food','FoodController@validFood')->name('valid');
Route::post('remove-food/{id}','FoodController@removeFood')->name('remove');
Route::post('category/add',['as'=>'addCategory','uses'=>'FoodController@addCategory']);
Route::post('category/remove',['as'=>'removeCategory','uses'=>'FoodController@removeCategory']);
Route::post('category/edit',['as'=>'catPriority','uses'=>'FoodController@catPriority']);
Route::post('food/add',['as'=>'addFood','uses'=>'FoodController@addFood']);
Route::get('resources',['as'=>'resources','uses'=>'FoodController@resources']);



Route::get('get-cashier','CashierController@getCashiers')->name('getCashiers');
Route::post('edit-cashier','CashierController@editCashiers')->name('editCashiers');
Route::post('remove-cashier','CashierController@removeCashiers')->name('removeCashiers');

Route::post('update-time','UserController@timeUpdate')->name('timeUpdate');

Route::get('users','UsersController@getUsers')->name('users');

