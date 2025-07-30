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
    Schema::create('sales', function (Blueprint $table) {
        $table->id();
        
        // Chaves Estrangeiras
        $table->foreignId('user_id')->constrained('users'); // Vendedor responsável
        $table->foreignId('client_id')->nullable()->constrained('clients'); // Cliente (opcional)
        $table->foreignId('payment_method_id')->constrained('payment_methods'); // Forma de pagamento
        
        $table->decimal('total_amount', 10, 2); // Valor total da venda
        $table->integer('installments_count')->default(1); // Número de parcelas
        $table->date('sale_date'); // Data da venda
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
