<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index()
    {
        $products = DB::select("
            SELECT p.*, c.name as category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC
        ");

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = DB::select("SELECT * FROM categories ORDER BY name ASC");
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer',
            'code' => 'required|unique:products',
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        DB::insert("
            INSERT INTO products (category_id, code, name, description, price, stock, image, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $validated['category_id'],
            $validated['code'],
            $validated['name'],
            $validated['description'] ?? null,
            $validated['price'],
            $validated['stock'],
            $imagePath
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Show the form for editing the product
     */
    public function edit($id)
    {
        $product = DB::selectOne("SELECT * FROM products WHERE id = ?", [$id]);
        $categories = DB::select("SELECT * FROM categories ORDER BY name ASC");

        if (!$product) {
            abort(404);
        }

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer',
            'code' => 'required|unique:products,code,' . $id,
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        // Get old product data
        $oldProduct = DB::selectOne("SELECT image FROM products WHERE id = ?", [$id]);

        $imagePath = $oldProduct->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($oldProduct->image) {
                Storage::disk('public')->delete($oldProduct->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        DB::update("
            UPDATE products
            SET category_id = ?, code = ?, name = ?, description = ?,
                price = ?, stock = ?, image = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $validated['category_id'],
            $validated['code'],
            $validated['name'],
            $validated['description'] ?? null,
            $validated['price'],
            $validated['stock'],
            $imagePath,
            $id
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diupdate');
    }

    /**
     * Remove the specified product
     */
    public function destroy($id)
    {
        // Get product to delete image
        $product = DB::selectOne("SELECT image FROM products WHERE id = ?", [$id]);

        if ($product && $product->image) {
            Storage::disk('public')->delete($product->image);
        }

        DB::delete("DELETE FROM products WHERE id = ?", [$id]);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    /**
     * Search products for POS (AJAX)
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q', '');

        if (empty($keyword)) {
            $products = DB::select("
                SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.stock > 0
                ORDER BY p.name ASC
                LIMIT 20
            ");
        } else {
            $products = DB::select("
                SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (p.name LIKE ? OR p.code LIKE ?)
                AND p.stock > 0
                ORDER BY p.name ASC
                LIMIT 20
            ", ["%$keyword%", "%$keyword%"]);
        }

        return response()->json($products);
    }
}
