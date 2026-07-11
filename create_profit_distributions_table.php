<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profit_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('financial_summary_id')->constrained()->cascadeOnDelete();
            $table->decimal('ownership_percentage', 5, 2); // نسبة الملكية في هذا الشهر
            $table->decimal('share_amount', 12, 2); // نصيبه من صافي الربح
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profit_distributions');
    }
};