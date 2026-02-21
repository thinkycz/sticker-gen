<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { useTranslations } from '@/composables/useTranslations';
import { computed } from 'vue';

const { __ } = useTranslations();
const page = usePage();
const locale = computed(() => page.props.locale || 'en');

const formattedDate = computed(() => {
    return new Date().toLocaleDateString(locale.value === 'cs' ? 'cs-CZ' : 'en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
});
</script>

<template>
    <Head :title="__('privacy_policy_title')" />
    <div class="bg-slate-50 min-h-screen">
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
            
            <Link
                href="/"
                class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors"
            >
                ← {{ __('back_to_home') }}
            </Link>
        </nav>

        <!-- Content -->
        <div class="container mx-auto px-6 py-12 max-w-4xl">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12">
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-8">{{ __('privacy_policy_title') }}</h1>
                
                <div class="prose prose-slate max-w-none space-y-6">
                    <p class="text-slate-600 leading-relaxed">
                        {{ __('last_updated') }}: {{ formattedDate }}
                    </p>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('information_we_collect') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('information_we_collect_desc') }}
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>{{ __('account_info') }}</li>
                            <li>{{ __('designs_info') }}</li>
                            <li>{{ __('usage_data') }}</li>
                            <li>{{ __('technical_info') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('how_we_use_info') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('how_we_use_info_desc') }}
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>{{ __('provide_improve_service') }}</li>
                            <li>{{ __('process_transactions') }}</li>
                            <li>{{ __('send_technical_notices') }}</li>
                            <li>{{ __('respond_to_comments') }}</li>
                            <li>{{ __('monitor_analyze_trends') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('information_sharing') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('information_sharing_desc') }}
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>{{ __('service_providers') }}</li>
                            <li>{{ __('legal_authorities') }}</li>
                            <li>{{ __('business_partners') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('data_security') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('data_security_desc') }}
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('your_rights') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('your_rights_desc') }}
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>{{ __('access_update_info') }}</li>
                            <li>{{ __('delete_account_data') }}</li>
                            <li>{{ __('opt_out_comms') }}</li>
                            <li>{{ __('request_copy_data') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('contact_us') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('contact_us_desc') }}
                        </p>
                        <p class="text-slate-600 mt-3">
                            Email: privacy@stickerapp.com<br>
                            Address: 123 Design Street, Creative City, CC 12345
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>
