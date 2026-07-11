<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('month_year', 7);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('total_operational_expenses', 12, 2)->default(0);
            $table->decimal('total_cost_of_goods', 12, 2)->default(0);
            $table->decimal('total_manager_salaries', 12, 2)->default(0);
            $table->decimal('net_profit', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_summaries');
    }
};