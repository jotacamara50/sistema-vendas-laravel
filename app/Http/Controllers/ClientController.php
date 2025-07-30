<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email',
            'phone' => 'nullable|string|max:20',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')
                         ->with('success', 'Cliente criado com sucesso.');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')
                         ->with('success', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
                         ->with('success', 'Cliente excluído com sucesso.');
    }
}