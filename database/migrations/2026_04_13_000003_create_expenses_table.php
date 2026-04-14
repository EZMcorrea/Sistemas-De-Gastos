<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('description')->nullable();
            $table->decimal('value', 12, 2);
            $table->date('due_date')->nullable();
            $table->string('status')->default('Pendente'); // Pendente, Pago
            $table->foreignId('payment_method_id')->nullable()->constrained()->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
