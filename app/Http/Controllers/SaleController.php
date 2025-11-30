<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    /**
     * Display a listing of sales
     */
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        $sales = DB::select("
            SELECT s.*,
                   u.name as user_name,
                   c.name as customer_name,
                   (SELECT COUNT(*) FROM sale_items WHERE sale_id = s.id) as total_items
            FROM sales s
            LEFT JOIN users u ON s.user_id = u.id
            LEFT JOIN customers c ON s.customer_id = c.id
            WHERE DATE(s.sale_date) BETWEEN ? AND ?
            ORDER BY s.sale_date DESC
        ", [$dateFrom, $dateTo]);

        return view('sales.index', compact('sales', 'dateFrom', 'dateTo'));
    }

    /**
     * Show the form for creating a new sale (POS)
     */
    public function create()
    {
        $customers = DB::select("SELECT * FROM customers ORDER BY name ASC");
        return view('sales.create', compact('customers'));
    }

    /**
     * Store a newly created sale
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|integer',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric',
            'tax' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'total' => 'required|numeric',
            'paid' => 'required|numeric'
        ]);

        DB::beginTransaction();

        try {
            // Generate invoice number
            $lastInvoice = DB::selectOne("
                SELECT invoice_number
                FROM sales
                WHERE DATE(sale_date) = CURDATE()
                ORDER BY id DESC
                LIMIT 1
            ");

            if ($lastInvoice) {
                $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            $change = $validated['paid'] - $validated['total'];

            // Insert sale
            DB::insert("
                INSERT INTO sales (invoice_number, customer_id, user_id, subtotal, tax, discount, total, paid, change_amount, sale_date, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())
            ", [
                $invoiceNumber,
                $validated['customer_id'] ?? null,
                Auth::id(),
                $validated['subtotal'],
                $validated['tax'] ?? 0,
                $validated['discount'] ?? 0,
                $validated['total'],
                $validated['paid'],
                $change
            ]);

            // Get last insert ID
            $saleId = DB::getPdo()->lastInsertId();

            // Insert sale items and update stock
            foreach ($validated['items'] as $item) {
                $subtotal = $item['quantity'] * $item['price'];

                // Insert sale item
                DB::insert("
                    INSERT INTO sale_items (sale_id, product_id, quantity, price, subtotal, created_at, updated_at)
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())
                ", [
                    $saleId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price'],
                    $subtotal
                ]);

                // Update product stock
                DB::update("
                    UPDATE products
                    SET stock = stock - ?, updated_at = NOW()
                    WHERE id = ?
                ", [$item['quantity'], $item['product_id']]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'invoice_number' => $invoiceNumber,
                'sale_id' => $saleId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Transaksi gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified sale
     */
    public function show($id)
    {
        $sale = DB::selectOne("
            SELECT s.*,
                   u.name as user_name,
                   c.name as customer_name,
                   c.phone as customer_phone
            FROM sales s
            LEFT JOIN users u ON s.user_id = u.id
            LEFT JOIN customers c ON s.customer_id = c.id
            WHERE s.id = ?
        ", [$id]);

        if (!$sale) {
            abort(404);
        }

        $items = DB::select("
            SELECT si.*,
                   p.name as product_name,
                   p.code as product_code
            FROM sale_items si
            LEFT JOIN products p ON si.product_id = p.id
            WHERE si.sale_id = ?
        ", [$id]);

        return view('sales.show', compact('sale', 'items'));
    }
}
