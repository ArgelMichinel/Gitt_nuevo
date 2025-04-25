<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MELIService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Controller_QRgenerator extends Controller
{
    public function generarQR(Request $request, string $tag)
    {
        $tag = rawurldecode($tag);
        $title = "QR envío " . $tag;

        try {
            
            $codeText = substr($tag,1); //str_replace(" ", "+", $tag);  // remember to sanitize that - it is user input!
    
            $qrCode = QrCode::size(300)->generate($codeText);
    
            // outputs image directly into browser, as PNG stream
            return response($qrCode)->header('Content-Type', 'image/svg+xml');

        } catch (\Throwable $th) {
            echo ('Problema durante la consulta ' . $th);
        }

    }
}
