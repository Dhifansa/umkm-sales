<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display dashboard with statistics and charts
     */
    public function index()
    {
        // Total Products
        $totalProducts = DB::selectOne("SELECT COUNT(*) as total FROM products")->total;

        // Total Customers
        $totalCustomers = DB::selectOne("SELECT COUNT(*) as total FROM customers")->total;

        // Today's Sales
        $todaySales = DB::selectOne("
            SELECT COALESCE(SUM(total), 0) as total
            FROM sales
            WHERE DATE(sale_date) = CURDATE()
        ")->total;

        // Monthly Sales
        $monthlySales = DB::selectOne("
            SELECT COALESCE(SUM(total), 0) as total
            FROM sales
            WHERE MONTH(sale_date) = MONTH(CURDATE())
            AND YEAR(sale_date) = YEAR(CURDATE())
        ")->total;

        // Low Stock Products (stock < 10)
        $lowStockProducts = DB::select("
            SELECT * FROM products
            WHERE stock < 10
            ORDER BY stock ASC
            LIMIT 5
        ");

        // Top 5 Best Selling Products This Month
        $topProducts = DB::select("
            SELECT p.name, SUM(si.quantity) as total_sold
            FROM sale_items si
            JOIN products p ON si.product_id = p.id
            JOIN sales s ON si.sale_id = s.id
            WHERE MONTH(s.sale_date) = MONTH(CURDATE())
            AND YEAR(s.sale_date) = YEAR(CURDATE())
            GROUP BY p.id, p.name
            ORDER BY total_sold DESC
            LIMIT 5
        ");

        // Sales Chart - Last 7 Days
        $salesChart = DB::select("
            SELECT DATE(sale_date) as date, SUM(total) as total
            FROM sales
            WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(sale_date)
            ORDER BY date ASC
        ");

        return view('dashboard', compact(
            'totalProducts',
            'totalCustomers',
            'todaySales',
            'monthlySales',
            'lowStockProducts',
            'topProducts',
            'salesChart'
        ));
    }
}
