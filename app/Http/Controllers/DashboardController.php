<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // stats cards
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        //transaksi & pendapatan hari ini 
        $todayTransactions = Transaction::whereDate('transaction_date', today())
            ->where('type', 'out')
            ->count();

        $todayRevenue = Transaction::whereDate('transaction_date', today())
            ->where('type', 'out')
            ->sum('total');

        //total pendapatan bulan ini
        $monthlyRevenue = Transaction::whereMonth('transaction_date', '=', now()->month)
            ->where('type', 'out')
            ->sum('total');

        // grafik penjualan 7 hari terakhir
        $last7Days = collect(range(0, 6))->map(function ($days) {
            $date = today()->subDays($days);
            return [
                'date' => $date,
                'revenue' => Transaction::whereDate('transaction_date', $date)
                    ->where('type', 'out')
                    ->sum('total')
            ];
        });

        $salesChart = Transaction::select(
            DB::raw('date(transaction_date) as date'),
            DB::raw('SUM(total) as total'),
            DB::raw('COUNT(*) as count')
        )
            ->where('type', 'out')
            ->whereBetween('transaction_date', [today()->subDays(6), today()])
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(7)
            ->get()
            ->keyBy('date');

        // pastikan semua 7 haru ada (isi 0 jika tidak ada transaksi)
        $last7Days = $last7Days->map(function ($day) use ($salesChart) {
            $date = $day['date'];
            $revenue = $salesChart->get($date->format('Y-m-d'), 0);
            return [
                'date' => $date,
                'revenue' => $revenue
            ];
        });

        // grafik kategori (pie chart)
        $categoryChart = Category::withCount('products')
            ->orderByDesc('products_count')
            ->get();

        // stock menipis
        $lowStockProducts = Product::lowStock()
            ->with('category')
            ->orderBy('stock')
            ->take(5)
            ->get();

        // transaksi terbaru
        $recentTransactions = Transaction::with('user')
            ->latest('transaction_date')
            ->take(5)
            ->get();

        // top 5 produk terlaris 
        $topProducts = DB::table('transactions_details')
            ->join('products', 'products.id', '=', 'transactions_details.product_id')
            ->join('transactions', 'transactions.id', '=', 'transactions_details.transaction_id')
            ->where('transactions.type', 'out')
            ->select(
                'products.name',
                DB::raw('SUM(transactions_details.quantity) as total_qty'),
                DB::raw('SUM(transactions_details.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'todayTransactions',
            'todayRevenue',
            'monthlyRevenue',
            'last7Days',
            'categoryChart',
            'lowStockProducts',
            'recentTransactions',
            'topProducts'
        ));
    }
}
