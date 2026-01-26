@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Admin Dashboard</h2>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-semibold text-indigo-600">Total Products</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalProducts ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-semibold text-indigo-600">Categories</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalCategories ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-semibold text-indigo-600">Users</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalUsers ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-semibold text-indigo-600">Orders</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalOrders ?? 0 }}</p>
        </div>
    </div>

    <!-- Hidden data containers for JavaScript -->
    <div id="chart-data" 
         data-sales-labels='<?php echo json_encode($salesLabels ?? []); ?>'
         data-sales-data='<?php echo json_encode($salesData ?? []); ?>'
         data-status-labels='<?php echo json_encode($orderStatusLabels ?? []); ?>'
         data-status-data='<?php echo json_encode($orderStatusData ?? []); ?>'
         style="display: none;">
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Sales Chart -->
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-semibold text-indigo-600 mb-4">Sales Report</h3>
            <div class="h-80">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="bg-white p-6 rounded-2xl shadow">
            <h3 class="text-lg font-semibold text-indigo-600 mb-4">Order Status</h3>
            <div class="h-80">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the data container
    const dataContainer = document.getElementById('chart-data');
    
    // Parse the data from HTML attributes
    let salesLabels = [];
    let salesData = [];
    let statusLabels = [];
    let statusData = [];
    
    try {
        salesLabels = JSON.parse(dataContainer.getAttribute('data-sales-labels') || '[]');
        salesData = JSON.parse(dataContainer.getAttribute('data-sales-data') || '[]');
        statusLabels = JSON.parse(dataContainer.getAttribute('data-status-labels') || '[]');
        statusData = JSON.parse(dataContainer.getAttribute('data-status-data') || '[]');
    } catch (error) {
        console.error('Error parsing chart data:', error);
    }
    
    // Use default data if arrays are empty
    if (salesLabels.length === 0) {
        salesLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    }
    if (salesData.length === 0) {
        salesData = [1200, 1900, 3000, 5000, 2000, 3000, 4500, 3800, 5200, 4800, 6100, 7500];
    }
    if (statusLabels.length === 0) {
        statusLabels = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
    }
    if (statusData.length === 0) {
        statusData = [12, 8, 25, 45, 5];
    }
    
    // 1. Sales Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        const salesChart = new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Sales ($)',
                    data: salesData,
                    backgroundColor: 'rgba(79, 70, 229, 0.7)',
                    borderColor: 'rgb(79, 70, 229)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    // 2. Order Status Chart
    const statusCtx = document.getElementById('orderStatusChart');
    if (statusCtx) {
        const statusColors = [
            'rgba(255, 159, 64, 0.7)',  // Orange
            'rgba(54, 162, 235, 0.7)',  // Blue
            'rgba(75, 192, 192, 0.7)',  // Teal
            'rgba(75, 192, 75, 0.7)',   // Green
            'rgba(255, 99, 132, 0.7)'    // Red
        ];

        const orderStatusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: statusColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    }
});
</script>
@endsection