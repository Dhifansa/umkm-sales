<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index()
    {
        $customers = DB::select("
            SELECT c.*,
                   COUNT(s.id) as total_transactions,
                   COALESCE(SUM(s.total), 0) as total_spending
            FROM customers c
            LEFT JOIN sales s ON c.id = s.customer_id
            GROUP BY c.id, c.name, c.phone, c.email, c.address, c.created_at, c.updated_at
            ORDER BY c.name ASC
        ");

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'nullable|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable'
        ]);

        DB::insert("
            INSERT INTO customers (name, phone, email, address, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ", [
            $validated['name'],
            $validated['phone'] ?? null,
            $validated['email'] ?? null,
            $validated['address'] ?? null
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the customer
     */
    public function edit($id)
    {
        $customer = DB::selectOne("SELECT * FROM customers WHERE id = ?", [$id]);

        if (!$customer) {
            abort(404);
        }

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'nullable|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable'
        ]);

        DB::update("
            UPDATE customers
            SET name = ?, phone = ?, email = ?, address = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $validated['name'],
            $validated['phone'] ?? null,
            $validated['email'] ?? null,
            $validated['address'] ?? null,
            $id
        ]);

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil diupdate');
    }

    /**
     * Remove the specified customer
     */
    public function destroy($id)
    {
        // Check if customer has transactions
        $transactionCount = DB::selectOne("
            SELECT COUNT(*) as total
            FROM sales
            WHERE customer_id = ?
        ", [$id])->total;

        if ($transactionCount > 0) {
            return redirect()->route('customers.index')
                ->with('error', 'Pelanggan tidak dapat dihapus karena memiliki ' . $transactionCount . ' transaksi');
        }

        DB::delete("DELETE FROM customers WHERE id = ?", [$id]);

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }
}
