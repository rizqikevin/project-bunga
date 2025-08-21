<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Str;

class QrCodeController extends Controller
{
    public function index()
    {
        $currentQr = session('current_qr');
        $expiryTime = session('qr_expiry');
        
        if (!$currentQr || Carbon::now() > $expiryTime) {
            $currentQr = $this->generateNewQrCode();
        }
        
        $timeLeft = Carbon::parse($expiryTime)->diffInSeconds(Carbon::now());
        
        return view('admin.qrcode.index', [
            'qrCode' => $currentQr,
            'expiryTime' => $timeLeft
        ]);
    }

    public function generateNewQrCode()
    {
        $uniqueCode = Str::random(32);
        $expiryTime = Carbon::now()->addMinutes(15);
        
        session(['current_qr' => $uniqueCode]);
        session(['qr_expiry' => $expiryTime]);
        
        return $uniqueCode;
    }

    public function refreshQrCode()
    {
        $newQrCode = $this->generateNewQrCode();
        $expiryTime = Carbon::parse(session('qr_expiry'))->diffInSeconds(Carbon::now());
        
        return response()->json([
            'qrCode' => $newQrCode,
            'timeLeft' => $expiryTime
        ]);
    }
}