<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod; // Importe o Model

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create(['name' => 'Dinheiro']);
        PaymentMethod::create(['name' => 'Cartão de Crédito']);
        PaymentMethod::create(['name' => 'Cartão de Débito']);
        PaymentMethod::create(['name' => 'Pix']);
        PaymentMethod::create(['name' => 'Boleto Bancário']);
    }
}