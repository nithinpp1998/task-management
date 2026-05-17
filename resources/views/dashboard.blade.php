<x-app-layout>
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white tracking-tight mb-2">Dashboard</h1>
        <p class="text-slate-400">Welcome back, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Tasks -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border-b-4 border-blue-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Tasks</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalTasks }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
        </div>
        
        <!-- Completed -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border-b-4 border-green-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Completed</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $completedTasks }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border-b-4 border-amber-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">In Progress</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $inProgressTasks }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- High Priority -->
        <div class="bg-white rounded-2xl p-6 shadow-lg border-b-4 border-red-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">High Priority</p>
                    <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $highPriorityTasks }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Dashboard Graphs Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
        <!-- Status Distribution Pie Chart Card -->
        <div class="bg-white rounded-2xl p-6 shadow-lg flex flex-col justify-between min-h-[380px]">
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Task Status Distribution</h3>
                <p class="text-xs text-slate-400 mb-6">Percentage and count of tasks by current progress status</p>
            </div>
            <div class="relative w-full max-w-[240px] mx-auto flex items-center justify-center flex-grow">
                <canvas id="statusPieChart" class="max-h-[240px]"></canvas>
            </div>
        </div>

        <!-- Activity Over Time Line Chart Card -->
        <div class="bg-white rounded-2xl p-6 shadow-lg flex flex-col justify-between min-h-[380px]">
            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Task Activity & Progress</h3>
                <p class="text-xs text-slate-400 mb-6">Number of active tasks recorded over time</p>
            </div>
            <div class="relative w-full h-[240px] flex items-center justify-center flex-grow">
                <canvas id="activityLineChart" class="w-full h-full max-h-[240px]"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Status Pie Chart
            const pieCtx = document.getElementById('statusPieChart');
            if (pieCtx) {
                new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: ['Pending', 'In Progress', 'Completed'],
                        datasets: [{
                            data: [{{ $pendingTasks }}, {{ $inProgressTasks }}, {{ $completedTasks }}],
                            backgroundColor: ['#64748B', '#F59E0B', '#10B981'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 16,
                                    font: {
                                        size: 11,
                                        family: 'Inter, sans-serif',
                                        weight: '500'
                                    },
                                    color: '#64748B'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return ` ${context.label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Activity Line Chart
            const lineCtx = document.getElementById('activityLineChart');
            if (lineCtx) {
                const monthlyData = @json($monthlyData);
                const labels = Object.keys(monthlyData);
                const data = Object.values(monthlyData);

                // Create a beautiful premium blue gradient for the area fill
                const ctx = lineCtx.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 240);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.25)');
                gradient.addColorStop(1, 'rgba(59, 130, 246, 0.00)');

                new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Task Volume',
                            data: data,
                            borderColor: '#3B82F6',
                            borderWidth: 3,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
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
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11,
                                        family: 'Inter, sans-serif'
                                    },
                                    color: '#94A3B8'
                                }
                            },
                            y: {
                                grid: {
                                    color: '#F1F5F9',
                                    drawBorder: false
                                },
                                ticks: {
                                    precision: 0,
                                    font: {
                                        size: 11,
                                        family: 'Inter, sans-serif'
                                    },
                                    color: '#94A3B8'
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>

</x-app-layout>
