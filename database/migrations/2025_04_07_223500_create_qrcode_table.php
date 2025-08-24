<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('qrcode', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('qr_id')->unique();
            $table->string('location');
            $table->string('qr_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qrcode');
    }
};