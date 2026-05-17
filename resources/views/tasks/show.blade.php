<x-app-layout>
    @section('right_sidebar_extra')
        <a href="{{ route('tasks.show', $task) }}?refresh_ai=1" class="bg-white hover:bg-slate-50 text-blue-600 font-bold py-3 px-4 rounded-xl flex justify-between items-center shadow-lg border border-slate-100 transition-colors">
            <span>Refresh AI Summary</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        </a>
    @endsection

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-white tracking-tight">Task Detail + AI Summary</h1>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-xl relative">
        <div class="absolute top-6 right-6 text-slate-400 flex gap-1 cursor-pointer">
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
        </div>
        
        <h2 class="text-3xl font-bold text-slate-900 mb-4 w-11/12">{{ $task->title }}</h2>
        
        <div class="flex gap-4 mb-8">
            @if($task->status == 'completed')
                <span class="px-4 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-semibold capitalize flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Status: Completed
                </span>
            @elseif($task->status == 'in_progress')
                <span class="px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-sm font-semibold capitalize flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Status: In Progress
                </span>
            @else
                <span class="px-4 py-1.5 bg-slate-100 text-slate-700 rounded-full text-sm font-semibold capitalize flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span> Status: Pending
                </span>
            @endif
            <span class="px-4 py-1.5 bg-slate-100 text-slate-700 rounded-full text-sm font-semibold capitalize flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $task->priority == 'high' ? 'bg-red-500' : ($task->priority == 'medium' ? 'bg-amber-500' : 'bg-blue-500') }}"></span> Priority: {{ $task->priority }}
            </span>
        </div>

        <div class="bg-slate-50 border border-slate-100 rounded-xl p-6 mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Description</h3>
            
            <div class="text-slate-600 mb-6 font-medium">
                Assigned to: <span class="text-slate-800">{{ $task->user->name ?? 'Unassigned' }}</span>
            </div>
            
            <div class="bg-white border border-slate-200 rounded-lg p-3 mb-6 flex justify-between items-center text-slate-600">
                <span>Due Date: {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'None' }}</span>
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            
            <p class="text-slate-700 leading-relaxed">
                {{ $task->description ?: 'No description provided.' }}
            </p>
        </div>

        <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-3">AI-Generated Summary</h3>
            <p class="text-slate-700 leading-relaxed">
                {{ $task->ai_summary ?: 'AI summary is currently being generated or failed.' }}
            </p>
        </div>

        <div class="bg-slate-50 border border-slate-100 rounded-xl p-6 mb-8">
            <p class="text-slate-700 font-medium">
                <span class="font-bold">AI Priority:</span> <span class="capitalize">{{ $task->ai_priority ?: 'Pending' }}</span>
            </p>
        </div>
        
        @can('updateStatus', $task)
            <form action="{{ route('tasks.update', $task) }}" method="POST" class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
                @csrf
                @method('PUT')
                <input type="hidden" name="title" value="{{ $task->title }}">
                <input type="hidden" name="priority" value="{{ $task->priority }}">
                <input type="hidden" name="assigned_to" value="{{ $task->assigned_to }}">
                <input type="hidden" name="status" value="completed">
                
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-12 rounded-full shadow-lg shadow-blue-500/30 transition-transform transform hover:-translate-y-0.5" {{ $task->status == 'completed' ? 'disabled' : '' }}>
                    Mark as Completed
                </button>
            </form>
        @endcan
    </div>
</x-app-layout>
