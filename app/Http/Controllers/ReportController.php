<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display sales reports with charts
     */
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        // Total Sales
        $totalSales = DB::selectOne("
            SELECT COALESCE(SUM(total), 0) as total
            FROM sales
            WHERE DATE(sale_date) BETWEEN ? AND ?
        ", [$dateFrom, $dateTo])->total;

        // Total Transactions
        $totalTransactions = DB::selectOne("
            SELECT COUNT(*) as count
            FROM sales
            WHERE DATE(sale_date) BETWEEN ? AND ?
        ", [$dateFrom, $dateTo])->count;

        // Average Transaction
        $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Total Products Sold
        $totalProducts = DB::selectOne("
            SELECT COALESCE(SUM(si.quantity), 0) as total
            FROM sale_items si
            JOIN sales s ON si.sale_id = s.id
            WHERE DATE(s.sale_date) BETWEEN ? AND ?
        ", [$dateFrom, $dateTo])->total;

        // Sales Trend Daily
        $salesTrend = DB::select("
            SELECT DATE(sale_date) as date, SUM(total) as total
            FROM sales
            WHERE DATE(sale_date) BETWEEN ? AND ?
            GROUP BY DATE(sale_date)
            ORDER BY date ASC
        ", [$dateFrom, $dateTo]);

        // Sales by Category
        $salesByCategory = DB::select("
            SELECT c.name, COALESCE(SUM(si.subtotal), 0) as total
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            LEFT JOIN sale_items si ON p.id = si.product_id
            LEFT JOIN sales s ON si.sale_id = s.id
                AND DATE(s.sale_date) BETWEEN ? AND ?
            GROUP BY c.id, c.name
            HAVING total > 0
            ORDER BY total DESC
        ", [$dateFrom, $dateTo]);

        // Top 5 Products
        $topProducts = DB::select("
            SELECT p.name, p.code,
                   SUM(si.quantity) as total_sold,
                   SUM(si.subtotal) as total_amount
            FROM sale_items si
            JOIN products p ON si.product_id = p.id
            JOIN sales s ON si.sale_id = s.id
            WHERE DATE(s.sale_date) BETWEEN ? AND ?
            GROUP BY p.id, p.name, p.code
            ORDER BY total_sold DESC
            LIMIT 5
        ", [$dateFrom, $dateTo]);

        // Top 5 Customers
        $topCustomers = DB::select("
            SELECT
                COALESCE(c.name, 'Umum') as name,
                COUNT(s.id) as total_transactions,
                SUM(s.total) as total_spending
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            WHERE DATE(s.sale_date) BETWEEN ? AND ?
            GROUP BY c.id, c.name
            ORDER BY total_spending DESC
            LIMIT 5
        ", [$dateFrom, $dateTo]);

        // Hourly Transactions (Peak Hours)
        $hourlyTransactions = DB::select("
            SELECT HOUR(sale_date) as hour, COUNT(*) as count
            FROM sales
            WHERE DATE(sale_date) BETWEEN ? AND ?
            GROUP BY HOUR(sale_date)
            ORDER BY hour ASC
        ", [$dateFrom, $dateTo]);

        return view('reports.index', compact(
            'dateFrom',
            'dateTo',
            'totalSales',
            'totalTransactions',
            'averageTransaction',
            'totalProducts',
            'salesTrend',
            'salesByCategory',
            'topProducts',
            'topCustomers',
            'hourlyTransactions'
        ));
    }

    /**
     * Export report to PDF (placeholder)
     */
    public function exportPDF(Request $request)
    {
        // TODO: Implement PDF export
        return response()->json([
            'message' => 'Export PDF feature coming soon'
        ]);
    }

    /**
     * Export report to Excel (placeholder)
     */
    public function exportExcel(Request $request)
    {
        // TODO: Implement Excel export
        return response()->json([
            'message' => 'Export Excel feature coming soon'
        ]);
    }
}
