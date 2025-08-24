<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    // Masa berlaku QR (detik)
    private const LIFETIME_SECONDS = 60;

    // Key session
    private const KEY_CODE   = 'qr_code';
    private const KEY_GEN_AT = 'qr_generated_at';

    /**
     * Halaman admin: tampilkan QR + timer
     */
    public function index()
    {
        $now   = Carbon::now('Asia/Jakarta');
        $code  = session(self::KEY_CODE);
        $genAt = session(self::KEY_GEN_AT) ? Carbon::parse(session(self::KEY_GEN_AT), 'Asia/Jakarta') : null;

        // generate baru kalau belum ada / sudah habis masa
        if (!$code || !$genAt || $now->diffInSeconds($genAt) >= self::LIFETIME_SECONDS) {
            $code  = $this->generateNewCode();
            $genAt = Carbon::parse(session(self::KEY_GEN_AT), 'Asia/Jakarta');
        }

        $timeLeft = max(self::LIFETIME_SECONDS - $now->diffInSeconds($genAt), 0);
        $qrSvg    = $this->buildSvg($code);

        return view('admin.qrcode.index', [
            'qrSvg'    => $qrSvg,
            'timeLeft' => $timeLeft,
        ]);
    }

    /**
     * Endpoint AJAX untuk refresh QR (reset 60 detik)
     */
    public function refreshQrCode()
    {
        $code = $this->generateNewCode();

        return response()->json([
            'qrSvg'    => $this->buildSvg($code),            // konsisten: key = qrSvg
            'timeLeft' => self::LIFETIME_SECONDS,
            'payload'  => $code, // opsional untuk debug; bisa dihapus
        ]);
    }

    /* ================= Helpers ================= */

    // Payload: JSP-YYYYMMDDHHMMSS-xxxxxx
    private function generateNewCode(): string
    {
        $tsJakarta = Carbon::now('Asia/Jakarta')->format('YmdHis');
        $rand6     = substr(bin2hex(random_bytes(3)), 0, 6);
        $token     = "JSP-{$tsJakarta}-{$rand6}";

        session([
            self::KEY_CODE   => $token,
            self::KEY_GEN_AT => Carbon::now('Asia/Jakarta')->toIso8601String(),
        ]);

        // Jika ingin simpan DB untuk audit/anti-reuse, lakukan di sini (opsional)
        // \App\Models\QRCode::create(['qr_id' => $token, ...]);

        return $token;
    }

    private function buildSvg(string $data): string
    {
        return QrCode::format('svg')
            ->size(360)
            ->margin(4)
            ->errorCorrection('Q')
            ->color(0, 0, 0)
            ->backgroundColor(255, 255, 255)
            ->generate($data);
    }
}
