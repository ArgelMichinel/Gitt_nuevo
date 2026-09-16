<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MELIService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Controller_QRgenerator extends Controller
{
    public function generarQR(Request $request, string $tag)
    {
        $tag = base64_decode($tag);
        $tag = rawurldecode($tag);
        $title = "QR envío " . $tag;
        //dd($tag);

        try {
            
            $codeText = substr($tag,1); //str_replace(" ", "+", $tag);  // remember to sanitize that - it is user input!
    
            $qrCode = QrCode::size(300)->generate($codeText);
    
            $html = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
            <meta charset='UTF-8'>
            <title>$title</title>
            </head>
            <body style='display:flex; justify-content:center; align-items:center; height:100vh;'>
            $qrCode
            </body>
            </html>
            ";

            return response($html)->header('Content-Type', 'text/html');

        } catch (\Throwable $th) {
            echo ('Problema durante la consulta ' . $th);
        }

    }
}
