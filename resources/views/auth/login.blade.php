<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Task Manager') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#1E293B] h-screen overflow-hidden flex items-center justify-center p-4 relative">
        <!-- Interactive Antigravity Canvas Background -->
        <canvas id="particleCanvas" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>

        <div class="w-full max-w-md my-auto flex flex-col justify-center max-h-full relative z-10">
            <!-- Logo / App Name -->
            <div class="text-center mb-6 shrink-0">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-500 shadow-lg shadow-blue-500/30 mb-3 transition-transform hover:scale-105 duration-300">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-white tracking-tight">Task Manager</h1>
                <p class="text-slate-400 mt-1 text-xs font-medium">AI-Assisted Task Management System</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-6 md:p-8 overflow-y-auto max-h-[calc(100vh-160px)] no-scrollbar">
                <h2 class="text-xl font-bold text-slate-800 mb-0.5">Welcome back</h2>
                <p class="text-slate-500 mb-5 text-sm">Sign in to your account to continue</p>



                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-slate-50 border @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-slate-200 focus:ring-blue-500 focus:border-blue-500 @enderror text-slate-800 rounded-xl p-3.5 focus:ring-2 focus:outline-none transition-all placeholder-slate-400 text-sm"
                            placeholder="admin@example.com">
                        @error('email')
                            <p class="text-red-600 text-xs mt-1.5 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                        <input id="password" type="password" name="password" required
                            class="w-full bg-slate-50 border @error('password') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-slate-200 focus:ring-blue-500 focus:border-blue-500 @enderror text-slate-800 rounded-xl p-3.5 focus:ring-2 focus:outline-none transition-all placeholder-slate-400 text-sm"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-600 text-xs mt-1.5 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-500 focus:ring-blue-500 focus:ring-offset-0 transition-colors">
                            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors font-medium">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-blue-500 hover:text-blue-600 transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 transition-all active:scale-[0.98] transform text-sm cursor-pointer mt-2">
                        Sign In
                    </button>
                </form>

                @if (Route::has('register'))
                    <p class="text-center text-sm text-slate-500 mt-6 font-medium">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-bold text-blue-500 hover:text-blue-600 transition-colors">Create one</a>
                    </p>
                @endif
            </div>
        </div>

        <!-- Antigravity Background Animation Script -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const canvas = document.getElementById('particleCanvas');
                const ctx = canvas.getContext('2d');

                let width = canvas.width = window.innerWidth;
                let height = canvas.height = window.innerHeight;

                // Handle dynamic resize
                window.addEventListener('resize', () => {
                    width = canvas.width = window.innerWidth;
                    height = canvas.height = window.innerHeight;
                    initParticles();
                });

                const particles = [];
                const numParticles = 120;
                const influenceRadius = 150;

                let mouse = { x: 0, y: 0, active: false };

                window.addEventListener('mousemove', (e) => {
                    mouse.x = e.clientX;
                    mouse.y = e.clientY;
                    mouse.active = true;
                });

                window.addEventListener('mouseleave', () => {
                    mouse.active = false;
                });

                class Particle {
                    constructor() {
                        this.reset();
                        // Initial dynamic distribution
                        this.x = Math.random() * width;
                        this.y = Math.random() * height;
                        this.originX = this.x;
                        this.originY = this.y;
                    }

                    reset() {
                        this.originX = Math.random() * width;
                        this.originY = Math.random() * height;
                        this.x = this.originX;
                        this.y = this.originY;
                        this.size = Math.random() * 2 + 1.2;
                        this.baseOpacity = Math.random() * 0.35 + 0.15;
                        this.opacity = this.baseOpacity;
                        
                        // Beautiful subtle indigo, blue, purple palette
                        const colors = [
                            'rgba(59, 130, 246, ',   // Blue
                            'rgba(99, 102, 241, ',   // Indigo
                            'rgba(139, 92, 246, ',   // Purple
                            'rgba(168, 85, 247, '    // Violet
                        ];
                        this.colorPrefix = colors[Math.floor(Math.random() * colors.length)];
                        
                        this.angle = Math.random() * Math.PI * 2;
                        this.orbitRadius = Math.random() * 70 + 40; // Circular orbit distance from cursor
                        this.angularSpeed = (Math.random() * 0.010 + 0.004) * (Math.random() > 0.5 ? 1 : -1);
                    }

                    update() {
                        if (mouse.active) {
                            const dx = mouse.x - this.x;
                            const dy = mouse.y - this.y;
                            const dist = Math.sqrt(dx * dx + dy * dy);

                            if (dist < influenceRadius) {
                                // Anti-gravity circular/oval slow orbit motion around cursor
                                this.angle += this.angularSpeed;
                                
                                const targetX = mouse.x + Math.cos(this.angle) * this.orbitRadius;
                                const targetY = mouse.y + Math.sin(this.angle) * this.orbitRadius;
                                
                                // Smooth spring motion towards orbit path
                                this.x += (targetX - this.x) * 0.04;
                                this.y += (targetY - this.y) * 0.04;
                                
                                // Glowing highlight on hover
                                this.opacity = Math.min(0.85, this.opacity + 0.04);
                                return;
                            }
                        }

                        // Soft gravity-free glide back to static grid coordinates
                        this.x += (this.originX - this.x) * 0.03;
                        this.y += (this.originY - this.y) * 0.03;
                        
                        if (this.opacity > this.baseOpacity) {
                            this.opacity -= 0.01;
                        } else {
                            this.opacity = this.baseOpacity;
                        }
                    }

                    draw() {
                        ctx.beginPath();
                        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                        ctx.fillStyle = this.colorPrefix + this.opacity + ')';
                        ctx.shadowBlur = 6;
                        ctx.shadowColor = this.colorPrefix.replace(', ', ')');
                        ctx.fill();
                    }
                }

                function initParticles() {
                    particles.length = 0;
                    for (let i = 0; i < numParticles; i++) {
                        particles.push(new Particle());
                    }
                }

                function animate() {
                    ctx.clearRect(0, 0, width, height);
                    
                    // Reset shadow for performance clears
                    ctx.shadowBlur = 0;
                    
                    for (let i = 0; i < particles.length; i++) {
                        const p = particles[i];
                        p.update();
                        p.draw();
                    }
                    requestAnimationFrame(animate);
                }

                initParticles();
                animate();
            });
        </script>
        
        <x-status-toast />
    </body>
</html>
