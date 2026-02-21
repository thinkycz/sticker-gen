<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-slate-50 pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/" class="flex items-center gap-2">
                <ApplicationLogo class="h-8 w-8 text-indigo-600" />
                <span class="text-xl font-bold text-slate-900 tracking-tight">StickerApp</span>
            </Link>
        </div>

        <div
            class="mt-6 w-full bg-white px-6 py-8 shadow-sm border border-slate-200 sm:max-w-md sm:rounded-2xl sm:px-10"
        >
            <slot />
        </div>
    </div>
</template>
