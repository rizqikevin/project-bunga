<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->unique()->after('id');
            }
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('employee')->after('password');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'position', 'role']);
        });
    }
};