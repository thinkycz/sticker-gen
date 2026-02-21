<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Privacy Policy" />
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
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-8">Privacy Policy</h1>
                
                <div class="prose prose-slate max-w-none space-y-6">
                    <p class="text-slate-600 leading-relaxed">
                        Last updated: {{ new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </p>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Information We Collect</h2>
                        <p class="text-slate-600 leading-relaxed">
                            We collect information you provide directly to us, such as when you create an account, use our services, or contact us for support. This includes:
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>Account information (name, email address)</li>
                            <li>Sticker sheet designs and configurations you create</li>
                            <li>Usage data and interactions with our platform</li>
                            <li>Technical information about your device and browser</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">How We Use Your Information</h2>
                        <p class="text-slate-600 leading-relaxed">
                            We use the information we collect to:
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>Provide, maintain, and improve our services</li>
                            <li>Process transactions and send related information</li>
                            <li>Send technical notices and support messages</li>
                            <li>Respond to your comments and questions</li>
                            <li>Monitor and analyze trends and usage</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Information Sharing</h2>
                        <p class="text-slate-600 leading-relaxed">
                            We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy. We may share your information with:
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>Service providers who assist in operating our platform</li>
                            <li>Legal authorities when required by law</li>
                            <li>Business partners with your explicit consent</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Data Security</h2>
                        <p class="text-slate-600 leading-relaxed">
                            We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet is 100% secure.
                        </p>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Your Rights</h2>
                        <p class="text-slate-600 leading-relaxed">
                            You have the right to:
                        </p>
                        <ul class="list-disc list-inside text-slate-600 space-y-2 mt-3">
                            <li>Access and update your personal information</li>
                            <li>Delete your account and associated data</li>
                            <li>Opt out of communications from us</li>
                            <li>Request a copy of your data</li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-2xl font-semibold text-slate-900 mb-4">Contact Us</h2>
                        <p class="text-slate-600 leading-relaxed">
                            If you have any questions about this Privacy Policy, please contact us at:
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
