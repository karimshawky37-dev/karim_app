<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained()->cascadeOnDelete();
            $table->enum('movement_type', ['purchase', 'sale', 'return', 'adjustment', 'repair_use', 'transfer']);
            $table->integer('quantity');
            $table->foreignId('reference_id')->nullable(); // رقم الفاتورة أو الجهاز
            $table->string('reference_type')->nullable(); // Sale::class, Device::class
            $table->decimal('price_at_movement', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['inventory_id', 'movement_type']);
        });
    }
    public function down() { Schema::dropIfExists('inventory_movements'); }
};