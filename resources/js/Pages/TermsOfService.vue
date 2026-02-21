<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Terms of Service" />
    <div class="bg-slate-50 min-h-screen">
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
            
            <Link
                href="/"
                class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors"
            >
                ← Back to Home
            </Link>
        </nav>

        <!-- Content -->
        <div class="container mx-auto px-6 py-12 max-w-4xl">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12">
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-8">Terms of Service</h1>
                
                <div class="prose prose-slate max-w-none space-y-6">
                    <p class="text-slate-600 leading-relaxed">
                        Last updated: {{ new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </p>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Agreement to Terms</h2>
                        <p class="text-slate-600 leading-relaxed">
                            By accessing and using StickerApp, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Description of Service</h2>
                        <p class="text-slate-600 leading-relaxed">
                            StickerApp is a web-based platform that allows users to create, design, and print custom sticker sheets. Our service includes:
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>Custom sticker sheet layout design tools</li>
                            <li>Barcode generation and labeling features</li>
                            <li>Print-ready output generation</li>
                            <li>Cloud storage for your designs</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">User Accounts</h2>
                        <p class="text-slate-600 leading-relaxed">
                            To use certain features of our service, you must register for an account. You agree to:
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>Provide accurate, current, and complete information</li>
                            <li>Maintain and update your account information</li>
                            <li>Keep your password secure and confidential</li>
                            <li>Accept responsibility for all activities under your account</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Acceptable Use</h2>
                        <p class="text-slate-600 leading-relaxed">
                            You agree not to use the service to:
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>Create designs that violate any laws or regulations</li>
                            <li>Infringe on intellectual property rights</li>
                            <li>Upload malicious code or harmful content</li>
                            <li>Attempt to gain unauthorized access to our systems</li>
                            <li>Use the service for spam or fraudulent activities</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Intellectual Property</h2>
                        <p class="text-slate-600 leading-relaxed">
                            You retain ownership of the designs you create using our service. However, you grant us a limited, non-exclusive license to store, process, and display your designs solely for the purpose of providing our service to you.
                        </p>
                        <p class="text-slate-600 leading-relaxed mt-3">
                            The StickerApp platform, including its software, design, and trademarks, remains the property of StickerApp and is protected by intellectual property laws.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Payment and Subscription</h2>
                        <p class="text-slate-600 leading-relaxed">
                            Certain features of StickerApp may require payment. You agree to:
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>Provide accurate payment information</li>
                            <li>Pay all applicable fees and charges</li>
                            <li>Maintain updated payment methods</li>
                            <li>Understand that fees are non-refundable unless otherwise stated</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Service Availability</h2>
                        <p class="text-slate-600 leading-relaxed">
                            We strive to maintain high service availability but do not guarantee uninterrupted access. We may temporarily suspend or discontinue the service for maintenance, updates, or other operational reasons.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Limitation of Liability</h2>
                        <p class="text-slate-600 leading-relaxed">
                            StickerApp shall not be liable for any indirect, incidental, special, or consequential damages resulting from your use of the service. Our total liability shall not exceed the amount you paid for the service in the preceding twelve months.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Termination</h2>
                        <p class="text-slate-600 leading-relaxed">
                            We may terminate or suspend your account immediately, without prior notice or liability, for any reason, including if you breach the Terms of Service. Upon termination, your right to use the service will cease immediately.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Changes to Terms</h2>
                        <p class="text-slate-600 leading-relaxed">
                            We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting. Your continued use of the service constitutes acceptance of any modified terms.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Contact Information</h2>
                        <p class="text-slate-600 leading-relaxed">
                            If you have questions about these Terms of Service, please contact us at:
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
