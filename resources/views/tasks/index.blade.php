<x-app-layout>
    <!-- Header Area -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-white tracking-tight">Task List</h1>
    </div>

    <!-- Filters Area -->
    <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap gap-4 mb-8 text-sm">
        <div class="relative w-full md:w-64">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-slate-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" class="bg-white text-slate-800 rounded-lg block w-full pl-10 p-2.5 border-none focus:ring-2 focus:ring-blue-500 shadow-sm font-medium placeholder-slate-400" placeholder="Search Filter Task">
        </div>
        
        <select name="status" class="bg-white text-slate-700 rounded-lg p-2.5 border-none focus:ring-2 focus:ring-blue-500 shadow-sm font-medium min-w-[140px]" onchange="this.form.submit()">
            <option value="all">Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        
        <select name="priority" class="bg-white text-slate-700 rounded-lg p-2.5 border-none focus:ring-2 focus:ring-blue-500 shadow-sm font-medium min-w-[140px]" onchange="this.form.submit()">
            <option value="all">Priority</option>
            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
        </select>
        
        <div class="text-slate-400 font-medium py-2 ml-2">Filter User Task</div>
    </form>

    <!-- Tasks Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse ($tasks as $task)
            <div class="bg-white rounded-2xl p-6 text-slate-800 shadow-lg relative flex flex-col h-full">
                <!-- Status & Menu Header -->
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-2">
                        @if($task->status == 'completed')
                            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center text-white">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-green-600 capitalize">
                                Completed
                            </span>
                        @elseif($task->status == 'in_progress')
                            <div class="w-5 h-5 rounded-full bg-amber-500 flex items-center justify-center text-white">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-amber-600 capitalize">
                                In Progress
                            </span>
                        @else
                            <div class="w-5 h-5 rounded-full bg-slate-400 flex items-center justify-center text-white">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-sm font-medium text-slate-500 capitalize">
                                Pending
                            </span>
                        @endif
                    </div>
                    <div class="text-slate-400 flex gap-1 cursor-pointer hover:text-slate-600">
                        <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                    </div>
                </div>

                <!-- Title & Badges -->
                <h3 class="text-xl font-bold text-slate-900 mb-3 line-clamp-1">{{ $task->title }}</h3>
                <div class="flex gap-2 mb-4">
                    @if($task->status == 'completed')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold capitalize">Status Completed</span>
                    @elseif($task->status == 'in_progress')
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold capitalize">Status In Progress</span>
                    @else
                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold capitalize">Status Pending</span>
                    @endif
                    
                    @if($task->priority == 'high')
                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-semibold capitalize">Priority High</span>
                    @elseif($task->priority == 'medium')
                        <span class="px-3 py-1 bg-amber-100 text-amber-600 rounded-full text-xs font-semibold capitalize">Priority Medium</span>
                    @else
                        <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs font-semibold capitalize">Priority Low</span>
                    @endif
                </div>

                <!-- Description & Details -->
                <div class="text-slate-500 text-sm mb-6 flex-grow">
                    <p class="mb-2 line-clamp-2">
                        @if($task->description)
                            Description: {{ $task->description }}
                        @else
                            No description provided.
                        @endif
                    </p>
                    @if($task->ai_priority)
                        <p class="font-medium">AI Priority: <span class="capitalize">{{ $task->ai_priority }}</span></p>
                    @endif
                </div>

                <!-- Footer (Assignee, Date, Buttons) -->
                <div class="mt-auto">
                    <div class="text-sm text-slate-600 mb-1">Assigned to: {{ $task->user->name ?? 'Unassigned' }}</div>
                    <div class="text-sm text-slate-600 mb-4">Due Date: {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'None' }}</div>
                    
                    <div class="flex justify-between items-center border-t border-slate-100 pt-4">
                        <div class="text-blue-500 font-bold capitalize text-sm">{{ $task->priority }}</div>
                        <div class="flex gap-2">
                            @can('update', $task)
                                <a href="{{ route('tasks.edit', $task) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-1.5 rounded-full text-sm font-semibold transition-colors">Edit</a>
                            @endcan
                            <a href="{{ route('tasks.show', $task) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-full text-sm font-semibold transition-colors shadow-md shadow-blue-500/20">View</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl p-8 text-center shadow-lg">
                <div class="text-slate-400 mb-2">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-1">No Tasks Found</h3>
                <p class="text-slate-500">There are no tasks matching your filters or you have no assigned tasks.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $tasks->withQueryString()->links() }}
    </div>
</x-app-layout>
