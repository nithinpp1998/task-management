@if (session('success') || session('status') || session('error') || $errors->any())
    <div id="statusToast" class="fixed top-6 right-6 z-[999] max-w-sm pointer-events-auto transition-all duration-500 ease-out transform translate-x-12 opacity-0 scale-95" style="display: none;">
        @if (session('success') || session('status'))
            <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-2xl flex items-start gap-3 w-80 md:w-96">
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center shrink-0 text-green-600 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-800">Success</h4>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5 leading-relaxed">
                        {{ session('success') ?? session('status') }}
                    </p>
                </div>
                <button onclick="dismissStatusToast()" class="text-slate-400 hover:text-slate-600 transition-colors shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-2xl flex items-start gap-3 w-80 md:w-96">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center shrink-0 text-red-600 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-slate-800">Alert</h4>
                    <ul class="text-xs font-semibold text-slate-500 mt-0.5 leading-relaxed list-none space-y-1">
                        @if (session('error'))
                            <li>{{ session('error') }}</li>
                        @else
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <button onclick="dismissStatusToast()" class="text-slate-400 hover:text-slate-600 transition-colors shrink-0 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('statusToast');
            if (toast) {
                // Show with animation
                toast.style.display = 'block';
                // Trigger reflow for transition
                toast.offsetHeight;
                toast.classList.remove('translate-x-12', 'opacity-0', 'scale-95');
                toast.classList.add('translate-x-0', 'opacity-100', 'scale-100');

                // Auto-dismiss after 3 seconds
                setTimeout(() => {
                    dismissStatusToast();
                }, 3000);
            }
        });

        function dismissStatusToast() {
            const toast = document.getElementById('statusToast');
            if (toast) {
                // Dismiss with smooth animation
                toast.classList.remove('translate-x-0', 'opacity-100', 'scale-100');
                toast.classList.add('translate-x-12', 'opacity-0', 'scale-95');
                
                // Hide after animation finishes
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 500);
            }
        }
    </script>
@endif
