<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

const page = usePage();
const isOpen = ref(false);

const currentLocale = computed(() => page.props.locale || 'en');

const form = useForm({
    locale: currentLocale.value,
});

const switchLanguage = (locale) => {
    if (locale === currentLocale.value) {
        isOpen.value = false;
        return;
    }
    
    form.locale = locale;
    form.post(route('language.switch'), {
        preserveScroll: true,
        onSuccess: () => {
            isOpen.value = false;
        }
    });
};

const languages = [
    { code: 'en', name: 'English', flag: '🇺🇸' },
    { code: 'cs', name: 'Česky', flag: '🇨🇿' },
];

const currentLanguage = computed(() => 
    languages.find(l => l.code === currentLocale.value)
);
</script>

<template>
    <div class="relative">
        <button
            @click="isOpen = !isOpen"
            class="flex items-center justify-center w-10 h-10 rounded-full bg-white border border-slate-200 hover:bg-slate-50 transition-all hover:shadow-md cursor-pointer"
            :title="currentLanguage?.name"
        >
            <span class="text-xl">{{ currentLanguage?.flag }}</span>
        </button>

        <!-- Dropdown -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-show="isOpen"
                class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-xl shadow-lg z-50 overflow-hidden"
            >
                <div class="py-2">
                    <button
                        v-for="language in languages"
                        :key="language.code"
                        @click="switchLanguage(language.code)"
                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer"
                        :class="{ 'bg-indigo-50 text-indigo-700': language.code === currentLocale }"
                    >
                        <span class="text-xl">{{ language.flag }}</span>
                        <span class="font-medium">{{ language.name }}</span>
                        <svg
                            v-if="language.code === currentLocale"
                            xmlns="http://www.w3.org/2000/svg"
                            width="16"
                            height="16"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="text-indigo-600 ml-auto"
                        >
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                    </button>
                </div>
            </div>
        </Transition>

        <!-- Overlay to close dropdown when clicking outside -->
        <div
            v-if="isOpen"
            @click="isOpen = false"
            class="fixed inset-0 z-40"
        ></div>
    </div>
</template>
