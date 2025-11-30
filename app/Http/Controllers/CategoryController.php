<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = DB::select("
            SELECT c.*, COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            GROUP BY c.id, c.name, c.description, c.created_at, c.updated_at
            ORDER BY c.name ASC
        ");

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        DB::insert("
            INSERT INTO categories (name, description, created_at, updated_at)
            VALUES (?, ?, NOW(), NOW())
        ", [
            $validated['name'],
            $validated['description'] ?? null
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Show the form for editing the category
     */
    public function edit($id)
    {
        $category = DB::selectOne("SELECT * FROM categories WHERE id = ?", [$id]);

        if (!$category) {
            abort(404);
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        DB::update("
            UPDATE categories
            SET name = ?, description = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $validated['name'],
            $validated['description'] ?? null,
            $id
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        // Check if category has products
        $productCount = DB::selectOne("
            SELECT COUNT(*) as total
            FROM products
            WHERE category_id = ?
        ", [$id])->total;

        if ($productCount > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki ' . $productCount . ' produk');
        }

        DB::delete("DELETE FROM categories WHERE id = ?", [$id]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
