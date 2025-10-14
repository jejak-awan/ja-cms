@extends('admin.layouts.admin')

@section('title', 'User Statistics Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">User Statistics Dashboard</h2>
            <p class="text-sm text-gray-600 mt-1">Analytics and insights for user management</p>
        </div>
        <div class="flex space-x-3">
            <button id="refresh-data" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh Data
            </button>
            <button id="export-stats" class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Data
            </button>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['totalUsers'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['activeUsers'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">New This Month</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['newUsersThisMonth'] }}</p>
                    <p class="text-xs text-gray-500">{{ $stats['growthRate'] }}% growth</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">With Profile</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['usersWithProfile'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- User Growth Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">User Growth Trend</h3>
                <div class="flex space-x-2">
                    <button class="text-sm text-blue-600 hover:text-blue-800" onclick="exportChart('growth')">Export</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <!-- Role Distribution Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Role Distribution</h3>
                <div class="flex space-x-2">
                    <button class="text-sm text-blue-600 hover:text-blue-800" onclick="exportChart('roles')">Export</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="roleDistributionChart"></canvas>
            </div>
        </div>

        <!-- Login Activity Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Login Activity (30 Days)</h3>
                <div class="flex space-x-2">
                    <button class="text-sm text-blue-600 hover:text-blue-800" onclick="exportChart('activity')">Export</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="loginActivityChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Status Distribution</h3>
                <div class="flex space-x-2">
                    <button class="text-sm text-blue-600 hover:text-blue-800" onclick="exportChart('status')">Export</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Top Users -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                <a href="{{ route('admin.users.activity-logs') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recentActivity as $activity)
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full {{ $activity['status'] == 'success' ? 'bg-green-100' : ($activity['status'] == 'failed' ? 'bg-red-100' : 'bg-yellow-100') }} flex items-center justify-center">
                            <svg class="w-4 h-4 {{ $activity['status'] == 'success' ? 'text-green-600' : ($activity['status'] == 'failed' ? 'text-red-600' : 'text-yellow-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['user'] }}</p>
                        <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                    </div>
                    <div class="text-xs text-gray-500">{{ $activity['created_at'] }}</div>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-sm text-gray-500">No recent activity</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Top Users -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Most Active Users</h3>
                <a href="{{ route('admin.users.search') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($topUsers as $user)
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">{{ substr($user['name'], 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $user['name'] }}</p>
                        <p class="text-sm text-gray-500">{{ $user['email'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ $user['activity_count'] }}</p>
                        <p class="text-xs text-gray-500">activities</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-sm text-gray-500">No user data available</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Geographic Distribution -->
    @if($charts['geographicData']->count() > 0)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Geographic Distribution</h3>
            <button class="text-sm text-blue-600 hover:text-blue-800" onclick="exportChart('geographic')">Export</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($charts['geographicData'] as $location)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-900">{{ $location->location }}</span>
                <span class="text-sm text-gray-500">{{ $location->count }} users</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data from backend
const chartData = @json($charts);
const stats = @json($stats);

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    initUserGrowthChart();
    initRoleDistributionChart();
    initLoginActivityChart();
    initStatusDistributionChart();
});

function initUserGrowthChart() {
    const ctx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.userGrowth.map(item => item.month),
            datasets: [{
                label: 'New Users',
                data: chartData.userGrowth.map(item => item.users),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

function initRoleDistributionChart() {
    const ctx = document.getElementById('roleDistributionChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartData.roleDistribution.map(item => item.role),
            datasets: [{
                data: chartData.roleDistribution.map(item => item.count),
                backgroundColor: [
                    '#3B82F6',
                    '#8B5CF6',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function initLoginActivityChart() {
    const ctx = document.getElementById('loginActivityChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.loginActivity.map(item => item.date),
            datasets: [{
                label: 'Logins',
                data: chartData.loginActivity.map(item => item.logins),
                backgroundColor: '#10B981',
                borderColor: '#059669',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

function initStatusDistributionChart() {
    const ctx = document.getElementById('statusDistributionChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: chartData.statusDistribution.map(item => item.status),
            datasets: [{
                data: chartData.statusDistribution.map(item => item.count),
                backgroundColor: [
                    '#10B981',
                    '#F59E0B',
                    '#EF4444'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Export functionality
function exportChart(type) {
    const format = prompt('Export format (csv/json):', 'csv');
    if (format && ['csv', 'json'].includes(format.toLowerCase())) {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("admin.users.statistics.export") }}';
        form.style.display = 'none';

        // Add form data
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = type;
        form.appendChild(typeInput);

        const formatInput = document.createElement('input');
        formatInput.type = 'hidden';
        formatInput.name = 'format';
        formatInput.value = format.toLowerCase();
        form.appendChild(formatInput);

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
}

// Refresh data
document.getElementById('refresh-data').addEventListener('click', function() {
    location.reload();
});

// Export stats
document.getElementById('export-stats').addEventListener('click', function() {
    const type = prompt('Export type (overview/growth/activity/geographic):', 'overview');
    if (type && ['overview', 'growth', 'activity', 'geographic'].includes(type.toLowerCase())) {
        exportChart(type.toLowerCase());
    }
});
</script>
@endpush
@endsection
