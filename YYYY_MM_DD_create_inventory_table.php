<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();
            $table->string('name');
            $table->string('category');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('supplier_name')->nullable();
            
            // الأسعار
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->decimal('min_selling_price', 10, 2)->default(0);
            
            // الكميات
            $table->integer('alert_quantity')->default(5);
            $table->integer('current_quantity')->default(0);
            $table->integer('reserved_quantity')->default(0); // للطلبات المعلقة
            
            $table->string('unit')->default('قطعة');
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->softDeletes();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('inventory'); }
};