<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sms_sents', function (Blueprint $table) {
            $table->enum('status', ['pending', 'sent', 'failed', 'verified'])->default('pending')->change(); // pending, sent, failed, verified
            $table->dateTime('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_sents', function (Blueprint $table) {
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending')->change(); // pending, sent, failed
            $table->dropColumn('verified_at');
        });
    }
};
