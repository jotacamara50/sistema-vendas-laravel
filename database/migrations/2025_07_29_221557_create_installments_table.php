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
    Schema::create('installments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
        $table->integer('installment_number');
        $table->decimal('value', 10, 2);
        $table->date('due_date');
        $table->string('status')->default('Pendente');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
