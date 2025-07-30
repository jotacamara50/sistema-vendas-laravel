<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SaleController extends Controller
{
public function index(Request $request)
{
    
    $query = Sale::with(['client', 'user']);

    $query->when($request->filled('start_date'), function ($q) use ($request) {
        return $q->whereDate('sale_date', '>=', $request->start_date);
    });

    $query->when($request->filled('end_date'), function ($q) use ($request) {
        return $q->whereDate('sale_date', '<=', $request->end_date);
    });

    $query->when($request->filled('client_id'), function ($q) use ($request) {
        return $q->where('client_id', $request->client_id);
    });

    $sales = $query->latest()->paginate(15)->appends($request->query());

    $clients = Client::orderBy('name')->get();

    return view('sales.index', compact('sales', 'clients'));
}
    
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('name')->get();

        return view('sales.create', compact('clients', 'products', 'paymentMethods'));
    }

    public function edit(Sale $sale)
{
    $sale->load('items.product', 'installments');

    $clients = Client::orderBy('name')->get();
    $products = Product::orderBy('name')->get();
    $paymentMethods = PaymentMethod::orderBy('name')->get();

    return view('sales.edit', compact('sale', 'clients', 'products', 'paymentMethods'));
}

public function store(Request $request)
{
    $validatedData = $request->validate([
        'client_id' => 'nullable|exists:clients,id',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'installments' => 'required|array|min:1',
        'installments.*.installment_number' => 'required|integer', // <-- REGRA CORRIGIDA/ADICIONADA
        'installments.*.due_date' => 'required|date',
        'installments.*.value' => 'required|numeric|min:0.01',
    ], [
        'items.required' => 'É necessário adicionar pelo menos um produto à venda.',
        'installments.required' => 'É necessário gerar as parcelas antes de finalizar a venda.',
    ]);

    try {
        DB::beginTransaction();

        $totalAmount = 0;
        foreach ($validatedData['items'] as $itemData) {
            $product = Product::find($itemData['product_id']);
            $totalAmount += $product->price * $itemData['quantity'];
        }
        
        $installmentsTotal = array_sum(array_column($validatedData['installments'], 'value'));
        if (bccomp((string)$totalAmount, (string)$installmentsTotal, 2) !== 0) {
            throw new \Exception("A soma das parcelas (R$ " . number_format($installmentsTotal, 2, ',', '.') . ") não corresponde ao total dos itens (R$ " . number_format($totalAmount, 2, ',', '.') . ").");
        }

        $sale = Sale::create([
            'user_id' => Auth::id(),
            'client_id' => $validatedData['client_id'],
            'payment_method_id' => $validatedData['payment_method_id'],
            'total_amount' => $totalAmount,
            'installments_count' => count($validatedData['installments']),
            'sale_date' => Carbon::now(),
        ]);

        $itemsToSave = [];
        foreach ($validatedData['items'] as $itemData) {
            $product = Product::find($itemData['product_id']);
            $itemsToSave[] = [
                'product_id' => $product->id,
                'quantity' => $itemData['quantity'],
                'unit_price' => $product->price,
            ];
        }
        $sale->items()->createMany($itemsToSave);

        $installmentsToSave = [];
        // Agora $validatedData['installments'] contém todos os campos necessários
        foreach ($validatedData['installments'] as $installmentData) {
            $installmentsToSave[] = [
                'installment_number' => $installmentData['installment_number'],
                'value' => $installmentData['value'],
                'due_date' => $installmentData['due_date'],
                'status' => 'Pendente',
            ];
        }
        $sale->installments()->createMany($installmentsToSave);

        DB::commit();

        return redirect()->route('sales.index')->with('success', 'Venda registrada com sucesso!');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Ocorreu um erro: ' . $e->getMessage())->withInput();
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        try {
            $sale->delete();

            return redirect()->route('sales.index')
                             ->with('success', 'Venda excluída com sucesso.');

        } catch (\Exception $e) {
            return redirect()->route('sales.index')
                             ->with('error', 'Ocorreu um erro ao excluir a venda.');
        }
    }
}