<?php

namespace App\Http\Controllers;

use App\Category;

use App\Http\Middleware\customRedirect;
use App\Order;
use App\Restaurant;

use App\Food;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


class PageController extends Controller
{

    public function index(Request $request){
        $types = DB::table('categories')->orderBy('priority','asc')->get()->pluck('type');
        $foods = [];
        for($i=0;$i<count($types);$i++){

            $foods[$i] = DB::table('foods')->where([['category',$types[$i]],['valid','1']])->get();

        }
        $order = DB::table('orders')->where('token',Session::token())->orderBy('id','dsc')->first();
        $info = DB::table('restaurants')->first();

        if(!Session::has('login_cashier_59ba36addc2b2f9401580f014c7f58ea4e30989d')
            && !Session::has('login_manager_59ba36addc2b2f9401580f014c7f58ea4e30989d')
            && !is_null($order)
            && $order->delivered == 1
        ){
            Session::flush();
            $request->session()->regenerate(true);
            Session::save();


        }
        $token = Session::token();
        return view('index',compact('foods','types','info','order','token'));

    }
    public function indexPage(){

        $types = DB::table('categories')->orderBy('priority','asc')->get()->pluck('type');
        $foods = [];
        for($i=0;$i<count($types);$i++){

            $foods[$i] = DB::table('foods')->where([['category',$types[$i]],['valid','1']])->get();
        }
        return [$foods,$types];
    }
    public function rmSession(Request $request){



        return Session::token();



    }

    public function testPrinter(){
        $restaurant = DB::table('restaurants')->first();
        $connector = new WindowsPrintConnector("$restaurant->printer");
        $printer = new Printer($connector);
        $printer -> text("These example images are printed with the older\nbit image print command. You should only use\n\$p -> bitImage() if \$p -> graphics() does not\nwork on your printer.\n\n");
        $printer -> cut();
        $printer -> close();


    }
    public function generateBill(Request $request){

        $order = DB::table('orders')->where('id',$request->id)->first();
        $restaurant = DB::table('restaurants')->first();

//        // TODO To change generated pdf font just copy SCRIPT_ARABIC to mpdf/src/Config/ConfigVariable as baseScript
//        // TODO add fonts to FontVariable.php and copy .ttf files to ttfonts folder
        $pdf = PDF::loadView('pdf.bill',compact('order','restaurant'));
        $pdf->save('prints/document.pdf');
        $connector = new WindowsPrintConnector("$restaurant->printer");
        $file = public_path('prints/document.pdf');
        $printer = new Printer($connector);
        $printer -> feed();
        try {
            $pages = ImagickEscposImage::loadPdf($file);
            foreach ($pages as $page) {
                $printer -> bitImage($page);
            }
            $printer -> cut();
        } catch (Exception $e) {
            /*
             * loadPdf() throws exceptions if files or not found, or you don't have the
             * imagick extension to read PDF's
             */
            echo $e -> getMessage() . "\n";
        } finally {
            $printer -> close();
        }

//        try {
//            // Enter the share name for your USB printer here
//            //$connector = "POS-58";
//
//            $connector = new FilePrintConnector("/dev/usb/lp0");
//            $printer = new Printer($connector);
//            /* Name of shop */
////            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
//            $printer->setJustification(Printer::JUSTIFY_CENTER);
//            $printer->text("POS Mart\n");
//            $printer->selectPrintMode();
//            $printer->text("Today Closing.\n");
//
//            $printer->feed();
//            /* Title of receipt */
//            $printer->setEmphasis(true);
//
//            $printer->feed(2);
//
//            /* Cut the receipt and open the cash drawer */
//            $printer->cut();
//            $printer->pulse();
//            /* Close printer */
//            $printer->close();
//            // echo "Sudah di Print";
//            return true;
//        } catch (Exception $e) {
//            $message = "Couldn't print to this printer: " . $e->getMessage() . "\n";
//            return false;
//        }
    }

    public function orderStatus(Request $request){

        $order = DB::table('orders')->where('token',$request->token)->orderBy('id','dsc')->first();

        if(is_null($order)){
            return 404;
        }
        return $order->delivered;

    }

    public function Tax(){

        return DB::table('restaurants')->first()->tax;
    }


}
