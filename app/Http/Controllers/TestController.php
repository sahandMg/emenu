<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 11/29/2018
 * Time: 03:32 PM
 */

namespace App\Http\Controllers;

include(app_path().'/Http/Controllers/CallerId/PhpSerial.php');

use Illuminate\Support\Facades\Artisan;
use Mike42\Escpos\CapabilityProfiles\DefaultCapabilityProfile;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintBuffers\ImagePrintBuffer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use lepiaf\SerialPort\SerialPort;
use lepiaf\SerialPort\Parser\SeparatorParser;
use lepiaf\SerialPort\Configure\TTYMacConfigure;
use lepiaf\SerialPort\Configure\TTYConfigure;
use App\Http\Controllers\CallerId\PhpSerial;

class TestController extends Controller
{
 public function tester(){

     $connector = new WindowsPrintConnector("GP-80250N Series");
//     $connector = new FilePrintConnector(public_path('prints/test.pdf'));
//
//     $printer = new Printer($connector);
////     $buffer = new ImagePrintBuffer();
////     $buffer -> setFont('C:\xampp\htdocs\ewaiter\vendor\mpdf\mpdf\ttfonts\LateefRegOT.ttf');
//     $printer -> textRaw("\nاکباتان فاز 1.");
//     $printer -> textChinese("\nاکباتان فاز 1.");
//     $printer -> text("\nاکباتان فاز 1.");
//     $printer -> text("\nاکباتان فاز 1.");
//     $printer -> text("\nاکباتان فاز 1.");
//     $printer -> text("\nاکباتان فاز 1.");
//     $printer -> text("\nاکباتان فاز 1.");
//     $printer -> cut();
//     $printer -> pulse();
//     $printer -> close();

     $pdf = public_path('prints/test.pdf');
     $printer = new Printer($connector);
     try {
         $pages = ImagickEscposImage::loadPdf($pdf);
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


 }

 public function serial(){

     $cmd = app_path().'/Http/Controllers/IDCaller/IdCaller.exe';

     $last_line = passthru ($cmd, $retval);
    dd($retval);

     // ====================== Unlimited Loop Happens =========================

//      $serialPort = new SerialPort(new SeparatorParser(), new TTYConfigure());
//      $serialPort->open("/dev/tty.usbmodem14201");
//     dd( $serialPort->read());
//      while ($data = $serialPort->read()) {
//          echo $data."\n";
//
//          if ($data === "OK") {
//              $serialPort->write("1\n");
//              $serialPort->close();
//          }
//      }

     // =================  Is not implemented for Windows !! ==============================


//   $serial = new PhpSerial();
//   $serial->findPhpSerial();
//
//// First we must specify the device. This works on both linux and windows (if
//// your linux serial device is /dev/ttyS0 for COM1, etc)
//$serial->deviceSet("COM6");
//
//// We can change the baud rate, parity, length, stop bits, flow control
//$serial->confBaudRate(9600);
//$serial->confParity("none");
//$serial->confCharacterLength(8);
//$serial->confStopBits(1);
//$serial->confFlowControl("none");
//
//// Then we need to open it
//$serial->deviceOpen();
//
//// To write into
////$serial->sendMessage("Hello !");
//     $serial->readPort();
 }


}