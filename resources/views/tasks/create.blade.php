<x-app-layout>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-white tracking-tight">Create Task</h1>
    </div>

    <div class="bg-white rounded-2xl p-8 shadow-xl">
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-slate-700 font-bold mb-2">Title</label>
                <div class="relative">
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg p-3 pl-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow" placeholder="e.g. Launch New Campaign">
                    <div class="absolute inset-y-0 right-3 flex items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=E0F2FE&color=2563EB" class="w-6 h-6 rounded-full" alt="avatar">
                    </div>
                </div>
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-8">
                <label class="block text-slate-700 font-bold mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg p-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-8">
                <label class="block text-slate-700 font-bold mb-3">Priority</label>
                <div class="flex flex-wrap gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="priority" value="low" class="peer sr-only" {{ old('priority', 'low') == 'low' ? 'checked' : '' }}>
                        <div class="px-6 py-2 rounded-full bg-slate-100 text-slate-600 font-medium peer-checked:bg-blue-500 peer-checked:text-white transition-colors">Low</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="priority" value="medium" class="peer sr-only" {{ old('priority') == 'medium' ? 'checked' : '' }}>
                        <div class="px-6 py-2 rounded-full bg-slate-100 text-slate-600 font-medium peer-checked:bg-blue-500 peer-checked:text-white transition-colors">+ Medium</div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="priority" value="high" class="peer sr-only" {{ old('priority') == 'high' ? 'checked' : '' }}>
                        <div class="px-6 py-2 rounded-full bg-slate-100 text-slate-600 font-medium peer-checked:bg-blue-500 peer-checked:text-white transition-colors">+ High</div>
                    </label>
                </div>
                @error('priority') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-slate-700 font-bold mb-2">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                    @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-slate-700 font-bold mb-2">Assign To</label>
                    <select name="assigned_to" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-center mt-10">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-12 rounded-full shadow-lg shadow-blue-500/30 transition-transform transform hover:-translate-y-0.5">
                    Save Task
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
