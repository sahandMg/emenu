<?php
/**
 * Created by PhpStorm.
 * User: Sahand
 * Date: 11/29/2018
 * Time: 03:32 PM
 */

namespace App\Http\Controllers;


use Mike42\Escpos\CapabilityProfiles\DefaultCapabilityProfile;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintBuffers\ImagePrintBuffer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

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


}