<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_limits', function (Blueprint $table) {
            $table->string('key')->primary(); // ip_hash:window
            $table->unsignedSmallInteger('hit_count')->default(0);
            $table->dateTime('window_start');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_limits');
    }
};
