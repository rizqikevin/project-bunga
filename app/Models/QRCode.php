<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    protected $table = 'qrcode';
    
    protected $fillable = [
        'name',
        'qr_id',
        'location',
        'qr_path'
    ];
}