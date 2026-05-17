<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Task Manager') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased bg-[#1E293B] text-slate-100 min-h-screen md:h-screen md:overflow-hidden">
        <div class="min-h-screen md:h-screen md:max-h-screen flex flex-col md:flex-row p-4 md:p-8 max-w-7xl mx-auto gap-8 overflow-x-hidden md:overflow-hidden">
            
            <!-- Main Content Area (Left) -->
            <main class="flex-1 md:h-full md:overflow-y-auto no-scrollbar pb-12">
                {{ $slot }}
            </main>

            <!-- Sidebar (Right) -->
            <aside class="w-full md:w-80 flex flex-col md:h-full shrink-0">
                <!-- New Task / Back to List Button Container (Fixed inline with Header) -->
                <div class="flex items-center justify-end mb-8 shrink-0 h-10">
                    @if(request()->routeIs('tasks.create') || request()->routeIs('tasks.edit') || request()->routeIs('tasks.show'))
                        <a href="{{ route('tasks.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-5 rounded-lg transition-colors shadow-lg shadow-blue-500/30 text-sm cursor-pointer">
                            Back to List
                        </a>
                    @else
                        <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-5 rounded-lg transition-colors shadow-lg shadow-blue-500/30 text-sm cursor-pointer">
                            + New Task
                        </a>
                    @endif
                </div>

                <!-- Scrollable Sidebar Content -->
                <div class="flex-1 flex flex-col gap-6 md:overflow-y-auto no-scrollbar pb-12 {{ request()->routeIs('tasks.index') ? 'pt-[26px]' : '' }}">
                    <!-- User Profile & Nav -->
                    <div class="bg-white rounded-xl text-slate-800 overflow-hidden shadow-lg shrink-0">
                        <div class="p-4 flex items-center gap-3 border-b border-slate-100">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg overflow-hidden">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E0F2FE&color=2563EB" alt="avatar">
                            </div>
                            <div class="font-semibold text-lg">{{ Auth::user()->name }}</div>
                        </div>
                        
                        <nav class="flex flex-col">
                            <a href="{{ route('dashboard') }}" class="px-6 py-3 font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('tasks.index') }}" class="px-6 py-3 font-medium border-t border-slate-100 {{ request()->routeIs('tasks.*') ? 'bg-blue-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                                Tasks
                            </a>
                            @if(Auth::user()->isAdmin())
                            <div class="px-6 py-3 font-medium text-slate-600 border-t border-slate-100">
                                Users <span class="text-xs text-slate-400 font-normal ml-2">(Only visible to Admin)</span>
                            </div>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100">
                                @csrf
                                <button type="submit" class="w-full text-left px-6 py-3 font-medium text-slate-600 hover:bg-slate-50">
                                    Logout
                                </button>
                            </form>
                        </nav>

                        <!-- Circular Charts section (mocked) -->
                        <div class="p-6 border-t border-slate-100 flex justify-between items-center text-center">
                            <div>
                                <div class="w-12 h-12 rounded-full border-4 border-blue-500 flex items-center justify-center mx-auto text-sm font-bold text-blue-600">150</div>
                                <div class="text-[10px] text-slate-500 mt-2 uppercase tracking-wide">Total Tasks</div>
                            </div>
                            <div>
                                <div class="w-12 h-12 rounded-full border-4 border-slate-200 flex items-center justify-center mx-auto text-sm font-bold text-slate-600">90</div>
                                <div class="text-[10px] text-slate-500 mt-2 uppercase tracking-wide">Completed</div>
                            </div>
                            <div>
                                <div class="w-12 h-12 rounded-full border-4 border-slate-200 flex items-center justify-center mx-auto text-sm font-bold text-slate-600">60</div>
                                <div class="text-[10px] text-slate-500 mt-2 uppercase tracking-wide">In Progress</div>
                            </div>
                        </div>
                        
                        <!-- Bar Chart Area (White Card) -->
                        <div class="p-4 pt-0">
                            <div class="text-xs font-semibold text-center text-slate-600 mb-2">Monthly Task Completion</div>
                            <canvas id="miniBarChartWhite" height="100"></canvas>
                        </div>
                    </div>

                    <!-- Dark Bar Chart Card -->
                    <div class="bg-[#1E293B] border border-slate-700 rounded-xl p-5 shadow-lg">
                        <h3 class="font-semibold text-white mb-4">Monthly Task Completion</h3>
                        <canvas id="monthlyBarChartDark" height="150"></canvas>
                    </div>
                    
                    @yield('right_sidebar_extra')
                </div>
            </aside>
        </div>

        <script>
            // Initialize charts
            document.addEventListener('DOMContentLoaded', function() {
                const ctxWhite = document.getElementById('miniBarChartWhite');
                if (ctxWhite) {
                    new Chart(ctxWhite, {
                        type: 'bar',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                            datasets: [{
                                data: [5, 12, 18, 9, 15],
                                backgroundColor: '#3B82F6',
                                borderRadius: 4,
                                barThickness: 12
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { display: true, grid: { display: false } },
                                y: { display: false, grid: { display: false } }
                            }
                        }
                    });
                }

                const ctxDark = document.getElementById('monthlyBarChartDark');
                if (ctxDark) {
                    new Chart(ctxDark, {
                        type: 'bar',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                            datasets: [{
                                data: [12, 18, 8, 15, 20],
                                backgroundColor: '#3B82F6',
                                borderRadius: 4,
                                barThickness: 20
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { 
                                    grid: { display: false, drawBorder: false },
                                    ticks: { color: '#94A3B8' }
                                },
                                y: { 
                                    grid: { color: '#334155', drawBorder: false },
                                    ticks: { color: '#94A3B8' }
                                }
                            }
                        }
                    });
                }
            });
        </script>
        
        <x-status-toast />
    </body>
</html>
