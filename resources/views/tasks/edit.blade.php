<x-app-layout>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-white tracking-tight">Edit Task</h1>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-xl relative">
        <div class="absolute top-6 right-6 text-slate-400 flex gap-1 cursor-pointer">
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
        </div>
        
        <h2 class="text-2xl font-bold text-slate-900 mb-8 w-3/4">{{ $task->title }}</h2>

        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Title</label>
                <div class="relative">
                    <input type="text" name="title" value="{{ old('title', $task->title) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg p-3 pl-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                    <div class="absolute inset-y-0 right-3 flex items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($task->user->name ?? 'Unknown') }}&background=E0F2FE&color=2563EB" class="w-6 h-6 rounded-full" alt="avatar">
                    </div>
                </div>
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-8">
                <label class="block text-slate-700 font-bold mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg p-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">{{ old('description', $task->description) }}</textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-8 flex flex-col md:flex-row gap-6 md:items-center">
                <div class="flex-1">
                    <label class="block text-slate-700 font-bold mb-3">Priority</label>
                    <div class="flex flex-wrap gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="low" class="peer sr-only" {{ old('priority', $task->priority) == 'low' ? 'checked' : '' }}>
                            <div class="px-5 py-2 rounded-full bg-slate-100 text-slate-600 font-medium peer-checked:bg-blue-500 peer-checked:text-white transition-colors">Low</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="medium" class="peer sr-only" {{ old('priority', $task->priority) == 'medium' ? 'checked' : '' }}>
                            <div class="px-5 py-2 rounded-full bg-slate-100 text-slate-600 font-medium peer-checked:bg-blue-500 peer-checked:text-white transition-colors">Medium</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="high" class="peer sr-only" {{ old('priority', $task->priority) == 'high' ? 'checked' : '' }}>
                            <div class="px-5 py-2 rounded-full bg-slate-100 text-slate-600 font-medium peer-checked:bg-blue-500 peer-checked:text-white transition-colors">High</div>
                        </label>
                    </div>
                    @error('priority') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex-1">
                    <label class="block text-slate-700 font-bold mb-3">Status</label>
                    <div class="flex flex-wrap gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="pending" class="peer sr-only" {{ old('status', $task->status) == 'pending' ? 'checked' : '' }}>
                            <div class="px-4 py-2 rounded-full bg-slate-100 text-slate-600 font-medium peer-checked:bg-blue-500 peer-checked:text-white transition-colors text-sm">Pending</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="in_progress" class="peer sr-only" {{ old('status', $task->status) == 'in_progress' ? 'checked' : '' }}>
                            <div class="px-4 py-2 rounded-full bg-slate-100 text-slate-600 font-medium peer-checked:bg-blue-500 peer-checked:text-white transition-colors text-sm">In Progress</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="completed" class="peer sr-only" {{ old('status', $task->status) == 'completed' ? 'checked' : '' }}>
                            <div class="px-4 py-2 rounded-full bg-slate-100 text-slate-600 font-medium peer-checked:bg-blue-500 peer-checked:text-white transition-colors text-sm">Completed</div>
                        </label>
                    </div>
                    @error('status') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-slate-700 font-bold mb-2">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d') : '') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                    @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-slate-700 font-bold mb-2">Assign To</label>
                    <select name="assigned_to" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-center mt-10">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-12 rounded-full shadow-lg shadow-blue-500/30 transition-transform transform hover:-translate-y-0.5">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
