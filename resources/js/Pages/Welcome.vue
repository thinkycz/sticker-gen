<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-slate-50 min-h-screen text-slate-800 font-sans selection:bg-indigo-500 selection:text-white">
        <!-- Navigation -->
        <nav class="container mx-auto px-6 py-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                    <circle cx="8.5" cy="8.5" r="1.5" />
                    <polyline points="21 15 16 10 5 21" />
                </svg>
                <span class="text-xl font-bold text-slate-900 tracking-tight">StickerApp</span>
            </div>
            
            <div v-if="canLogin" class="flex items-center gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors"
                >
                    Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors"
                    >
                        Log in
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg"
                    >
                        Get Started
                    </Link>
                </template>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="container mx-auto px-6 py-20 md:py-32 text-center">
            <div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
                <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Design & Print <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Custom Stickers</span>
                </h1>
                <p class="text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                    Create professional barcode labels, product stickers, and custom layouts in seconds. No complex software required.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="route('register')"
                        class="px-8 py-4 text-lg font-bold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-1"
                    >
                        Start Designing Free
                    </Link>
                    <Link
                        v-else
                        :href="route('dashboard')"
                        class="px-8 py-4 text-lg font-bold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-1"
                    >
                        Go to Dashboard
                    </Link>
                    <a href="#features" class="px-8 py-4 text-lg font-bold text-slate-700 bg-white border border-slate-200 rounded-full hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm hover:shadow-md">
                        Learn More
                    </a>
                </div>
            </div>
            
            <!-- Hero Image / Preview -->
            <div class="mt-20 relative max-w-5xl mx-auto animate-fade-in-up delay-200">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-50 to-transparent z-10 h-32 bottom-0"></div>
                <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden p-2">
                    <div class="bg-slate-100 rounded-xl overflow-hidden aspect-[16/9] flex items-center justify-center relative">
                         <!-- Abstract UI Representation -->
                         <div class="absolute inset-0 grid grid-cols-4 gap-4 p-8 opacity-50">
                            <div v-for="i in 12" :key="i" class="bg-white rounded-lg shadow-sm border border-slate-200"></div>
                         </div>
                         <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-xl shadow-lg border border-indigo-100 max-w-md w-full text-center">
                                <div class="w-16 h-16 bg-indigo-100 rounded-full mx-auto flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">Ready to Print</h3>
                                <p class="text-slate-500">Perfectly aligned stickers, every time.</p>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-20">
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Everything you need</h2>
                    <p class="text-slate-500 max-w-xl mx-auto">Powerful tools to help you create the perfect layout for any paper size.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-12">
                    <div class="space-y-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Custom Layouts</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Define rows, columns, margins, and gaps. Support for A4, Letter, or any custom paper size.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5v14"/><path d="M8 5v14"/><path d="M12 5v14"/><path d="M17 5v14"/><path d="M21 5v14"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Barcode Generation</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Built-in support for Code128, EAN-13, QR Codes, and more. Generate labels instantly.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center text-pink-600 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Print Ready</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Export directly to PDF or print from browser with pixel-perfect alignment.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-50 border-t border-slate-200 py-12">
            <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-slate-500 text-sm">
                    &copy; 2026 StickerApp. All rights reserved.
                </div>
                <div class="flex gap-6">
                    <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors">Privacy Policy</a>
                    <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors">Terms of Service</a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
    opacity: 0;
    transform: translateY(20px);
}

.delay-200 {
    animation-delay: 0.2s;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
