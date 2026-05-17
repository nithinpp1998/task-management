<x-app-layout>
    @section('right_sidebar_extra')
        @can('update', $task)
        {{-- Regenerate AI Summary button --}}
        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="title"       value="{{ $task->title }}">
            <input type="hidden" name="priority"    value="{{ $task->priority }}">
            <input type="hidden" name="status"      value="{{ $task->status }}">
            <input type="hidden" name="assigned_to" value="{{ $task->assigned_to }}">
            <input type="hidden" name="description" value="{{ $task->description . ' ' }}">{{-- tiny whitespace forces description change --}}
            <button type="submit"
                class="w-full bg-white hover:bg-slate-50 text-blue-600 font-bold py-3 px-4 rounded-xl flex justify-between items-center shadow-lg border border-slate-100 transition-colors">
                <span>Regenerate AI Summary</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </form>
        @endcan
    @endsection

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-white tracking-tight">Task Detail</h1>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-xl relative">
        {{-- Three-dot menu icon --}}
        <div class="absolute top-6 right-6 text-slate-400 flex gap-1 cursor-pointer">
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
        </div>

        {{-- Title --}}
        <h2 class="text-3xl font-bold text-slate-900 mb-4 w-11/12">{{ $task->title }}</h2>

        {{-- Status & Priority badges --}}
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
                <span class="w-2 h-2 rounded-full {{ $task->priority == 'high' ? 'bg-red-500' : ($task->priority == 'medium' ? 'bg-amber-500' : 'bg-blue-500') }}"></span>
                Priority: {{ $task->priority }}
            </span>
        </div>

        {{-- Description Section --}}
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Description</h3>
            <div class="text-slate-600 mb-4 font-medium">
                Assigned to: <span class="text-slate-800">{{ $task->user->name ?? 'Unassigned' }}</span>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg p-3 mb-4 flex justify-between items-center text-slate-600">
                <span>Due Date: {{ $task->due_date ? $task->due_date->format('Y-m-d') : 'None' }}</span>
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-slate-700 leading-relaxed">
                {{ $task->description ?: 'No description provided.' }}
            </p>
        </div>

        {{-- AI-Generated Summary Section --}}
        <div class="rounded-xl border mb-6 overflow-hidden
            {{ $task->ai_summary ? 'bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-100' : 'bg-slate-50 border-slate-200' }}">

            {{-- Header --}}
            <div class="flex items-center gap-3 px-6 py-4 border-b {{ $task->ai_summary ? 'border-blue-100' : 'border-slate-200' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                    {{ $task->ai_summary ? 'bg-blue-500' : 'bg-slate-300' }}">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-slate-800 text-sm">AI-Generated Summary</h3>
                    <p class="text-xs text-slate-500">Powered by Gemini AI</p>
                </div>
                @if($task->ai_priority)
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                        {{ $task->ai_priority == 'high' ? 'bg-red-100 text-red-700' :
                           ($task->ai_priority == 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                        AI Priority: {{ $task->ai_priority }}
                    </span>
                @endif
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                @if($task->ai_summary)
                    <p class="text-slate-700 leading-relaxed text-sm">{{ $task->ai_summary }}</p>
                @else
                    <div class="flex items-center gap-3">
                        {{-- Animated spinner --}}
                        <svg class="animate-spin w-5 h-5 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <div>
                            <p class="text-slate-600 font-medium text-sm">Generating AI summary…</p>
                            <p class="text-slate-400 text-xs mt-0.5">The queue worker is processing this task. Refresh the page in a few moments.</p>
                        </div>
                    </div>

                    {{-- Auto-refresh if summary is pending --}}
                    <script>
                        setTimeout(() => { window.location.reload(); }, 8000);
                    </script>
                @endif
            </div>
        </div>

        {{-- Mark as Completed --}}
        @can('updateStatus', $task)
            <form action="{{ route('tasks.update_status', $task) }}" method="POST"
                  class="mt-6 pt-6 border-t border-slate-100 flex justify-center">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-12 rounded-full shadow-lg shadow-blue-500/30 transition-transform transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ $task->status == 'completed' ? 'disabled' : '' }}>
                    Mark as Completed
                </button>
            </form>
        @endcan
    </div>
</x-app-layout>
