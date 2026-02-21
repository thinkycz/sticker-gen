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
    <Head :title="__('terms_of_service_title')" />
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
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-8">{{ __('terms_of_service_title') }}</h1>
                
                <div class="prose prose-slate max-w-none space-y-6">
                    <p class="text-slate-600 leading-relaxed">
                        {{ __('last_updated') }}: {{ formattedDate }}
                    </p>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('agreement_to_terms') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('agreement_to_terms_desc') }}
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('description_of_service') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('description_of_service_desc') }}
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>{{ __('layout_tools') }}</li>
                            <li>{{ __('barcode_features') }}</li>
                            <li>{{ __('print_output') }}</li>
                            <li>{{ __('cloud_storage') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('user_accounts') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('user_accounts_desc') }}
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>{{ __('accurate_info') }}</li>
                            <li>{{ __('maintain_info') }}</li>
                            <li>{{ __('secure_password') }}</li>
                            <li>{{ __('accept_responsibility') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('acceptable_use') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('acceptable_use_desc') }}
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>{{ __('violate_laws') }}</li>
                            <li>{{ __('infringe_rights') }}</li>
                            <li>{{ __('upload_malicious') }}</li>
                            <li>{{ __('unauthorized_access') }}</li>
                            <li>{{ __('spam_fraud') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('intellectual_property') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('ip_ownership_desc') }}
                        </p>
                        <p class="text-slate-600 leading-relaxed mt-3">
                            {{ __('platform_ip_desc') }}
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('payment_subscription') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('payment_subscription_desc') }}
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>{{ __('accurate_payment') }}</li>
                            <li>{{ __('pay_fees') }}</li>
                            <li>{{ __('update_payment') }}</li>
                            <li>{{ __('non_refundable') }}</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('service_availability') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('service_availability_desc') }}
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('limitation_of_liability') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('limitation_of_liability_desc') }}
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('termination') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('termination_desc') }}
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('changes_to_terms') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('changes_to_terms_desc') }}
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">{{ __('contact_information') }}</h2>
                        <p class="text-slate-600 leading-relaxed">
                            {{ __('contact_information_desc') }}
                        </p>
                        <p class="text-slate-600 mt-3">
                            Email: legal@stickerapp.com<br>
                            Address: 123 Design Street, Creative City, CC 12345
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>
