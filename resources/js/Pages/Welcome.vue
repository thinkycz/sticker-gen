<script setup>
import { Head, Link } from '@inertiajs/vue3';
import LanguagePicker from '@/Components/LanguagePicker.vue';
import { useTranslations } from '@/composables/useTranslations';

const { __ } = useTranslations();

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
    <Head :title="__('welcome')" />
    <div class="bg-slate-50 min-h-screen text-slate-800 font-sans selection:bg-indigo-500 selection:text-white">
        <!-- Navigation -->
        <nav class="container mx-auto px-6 py-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                    <circle cx="8.5" cy="8.5" r="1.5" />
                    <polyline points="21 15 16 10 5 21" />
                </svg>
                <span class="text-xl font-bold text-slate-900 tracking-tight">{{ __('app_name') }}</span>
            </div>
            
            <div class="flex items-center gap-4">
                <LanguagePicker />
                
                <div v-if="canLogin" class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors"
                    >
                        {{ __('dashboard') }}
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors"
                        >
                            {{ __('login') }}
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg"
                        >
                            {{ __('register') }}
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="container mx-auto px-6 py-20 md:py-32 text-center">
            <div class="max-w-4xl mx-auto space-y-8 animate-fade-in-up">
                <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    {{ __('tagline') }} <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">{{ __('subtagline') }}</span>
                </h1>
                <p class="text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                    {{ __('description') }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="route('register')"
                        class="px-8 py-4 text-lg font-bold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-1"
                    >
                        {{ __('start_designing_free') }}
                    </Link>
                    <Link
                        v-else
                        :href="route('dashboard')"
                        class="px-8 py-4 text-lg font-bold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-1"
                    >
                        {{ __('go_to_dashboard') }}
                    </Link>
                </div>
            </div>
            
            <!-- Hero Image / Preview -->
            <div class="mt-20 relative max-w-5xl mx-auto animate-fade-in-up delay-200">
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
                                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ __('ready_to_print') }}</h3>
                                <p class="text-slate-500">{{ __('perfectly_aligned') }}</p>
                            </div>
                         </div>
                         <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-100 to-transparent z-10 h-32"></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Features Section -->
        <section id="features" class="py-24 bg-gradient-to-br from-slate-50 to-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-20">
                    <h2 class="text-4xl md:text-5xl font-bold text-slate-900 mb-6 bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-transparent">
                        {{ __('everything_you_need') }}
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                        {{ __('powerful_tools_desc') }}
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                    <div class="group relative bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:border-indigo-100 transition-all duration-300 hover:-translate-y-1">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ __('custom_layouts') }}</h3>
                            <p class="text-slate-600 leading-relaxed text-lg">
                                {{ __('custom_layouts_desc') }}
                            </p>
                            <div class="mt-6 flex items-center text-indigo-600 font-semibold group-hover:text-indigo-700 transition-colors">
                                <span class="text-sm">{{ __('learn_more') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 group-hover:translate-x-1 transition-transform duration-300"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="group relative bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:border-purple-100 transition-all duration-300 hover:-translate-y-1">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5v14"/><path d="M8 5v14"/><path d="M12 5v14"/><path d="M17 5v14"/><path d="M21 5v14"/></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ __('barcode_generation') }}</h3>
                            <p class="text-slate-600 leading-relaxed text-lg">
                                {{ __('barcode_generation_desc') }}
                            </p>
                            <div class="mt-6 flex items-center text-purple-600 font-semibold group-hover:text-purple-700 transition-colors">
                                <span class="text-sm">{{ __('learn_more') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 group-hover:translate-x-1 transition-transform duration-300"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="group relative bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:border-pink-100 transition-all duration-300 hover:-translate-y-1">
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-50 to-orange-50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-4">{{ __('print_ready') }}</h3>
                            <p class="text-slate-600 leading-relaxed text-lg">
                                {{ __('print_ready_desc') }}
                            </p>
                            <div class="mt-6 flex items-center text-pink-600 font-semibold group-hover:text-pink-700 transition-colors">
                                <span class="text-sm">{{ __('learn_more') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 group-hover:translate-x-1 transition-transform duration-300"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-50 border-t border-slate-200 py-12">
            <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-slate-500 text-sm">
                    {{ __('copyright', { year: new Date().getFullYear() }) }}
                </div>
                <div class="flex gap-6">
                    <Link :href="route('privacy-policy')" class="text-slate-400 hover:text-slate-600 transition-colors">{{ __('privacy_policy') }}</Link>
                    <Link :href="route('terms-of-service')" class="text-slate-400 hover:text-slate-600 transition-colors">{{ __('terms_of_service') }}</Link>
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
