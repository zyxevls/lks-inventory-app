@extends('layouts.dashboard')

@section('content')
<div class="content-header">
    <h1 class="content-title"><i class="bi bi-speedometer2"></i> Dashboard</h1>
    <p class="content-subtitle">Selamat datang, <strong>{{ Auth::user()->name }}</strong>. Berikut adalah ringkasan inventori Anda.</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-box2"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalProducts }}</h3>
                <p>Total Produk</p>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-tags"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalCategories }}</h3>
                <p>Total Kategori</p>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-truck"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalSuppliers }}</h3>
                <p>Total Supplier</p>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div class="stat-content">
                <h3>Rp{{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                <p>Pendapatan Hari Ini</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Tables Row -->
<div class="row g-4 mb-5">
    <!-- Sales Chart -->
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Penjualan 7 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="80" data-last7days="{{ json_encode($last7Days) }}"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Ringkasan</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="color: var(--text-secondary); font-size: 14px;">Transaksi Hari Ini</span>
                        <strong>{{ $todayTransactions }}</strong>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="color: var(--text-secondary); font-size: 14px;">Pendapatan Bulan Ini</span>
                        <strong style="color: var(--primary);">Rp{{ number_format($monthlyRevenue, 0, ',', '.') }}</strong>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="color: var(--text-secondary); font-size: 14px;">Produk Menipis</span>
                        <strong>{{ $lowStockProducts->count() }}</strong>
                    </div>
                </div>
                <hr style="border-color: var(--border);">
                <a href="{{ route('products.index') }}" class="btn btn-primary w-100" style="font-size: 14px;">
                    <i class="bi bi-arrow-right"></i> Lihat Produk
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-4">
    <!-- Low Stock Products -->
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Produk Menipis</h5>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProducts as $product)
                            <tr>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $product->category->name }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-warning">{{ $product->stock }} unit</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 30px; color: var(--text-secondary);">
                                    <i class="bi bi-inbox"></i> Tidak ada produk dengan stok menipis
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Transaksi Terbaru</h5>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_date->format('d M Y H:i') }}</td>
                                <td>
                                    @if($transaction->type === 'in')
                                        <span class="badge badge-success">Masuk</span>
                                    @else
                                        <span class="badge badge-danger">Keluar</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->user->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 30px; color: var(--text-secondary);">
                                    <i class="bi bi-inbox"></i> Tidak ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-star"></i> Top 5 Produk Terlaris</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th style="text-align: right;">Qty Terjual</th>
                            <th style="text-align: right;">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $key => $product)
                            <tr>
                                <td>
                                    <strong>#{{ $key + 1 }}</strong>
                                </td>
                                <td>{{ $product->name }}</td>
                                <td style="text-align: right;">{{ number_format($product->total_qty, 0) }} unit</td>
                                <td style="text-align: right; font-weight: 600; color: var(--primary);">
                                    Rp{{ number_format($product->total_revenue, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 30px; color: var(--text-secondary);">
                                    <i class="bi bi-inbox"></i> Tidak ada data penjualan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const chartCanvas = document.getElementById('salesChart');
        const last7Days = JSON.parse(chartCanvas.getAttribute('data-last7days'));
        const labels = last7Days.map(day => new Date(day.date).toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' })).reverse();
        const data = last7Days.map(day => day.revenue).reverse();

        const ctx = chartCanvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: data,
                    borderColor: '#0071e3',
                    backgroundColor: 'rgba(0, 113, 227, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#0071e3',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection
