<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import StickerRenderer from '@/Components/StickerRenderer.vue';

const props = defineProps({
    sheets: Array,
});

const deleteSheet = (id) => {
    if (confirm('Are you sure you want to delete this design?')) {
        router.delete(route('designer.destroy', id));
    }
};

const duplicateSheet = (id) => {
    router.post(route('designer.duplicate', id));
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <template #header-actions>
            <Link
                :href="route('setup')"
                class="bg-indigo-600 text-white px-5 py-2 rounded-full shadow-lg hover:bg-indigo-700 hover:shadow-indigo-500/30 transition-all font-semibold text-sm flex items-center gap-2 cursor-pointer hover:scale-105"
            >
                Create New Sheet
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
            </Link>
        </template>

        <div class="py-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">My Sticker Sheets</h2>
            
            <div v-if="sheets.length === 0" class="text-center py-16 bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <line x1="12" y1="8" x2="12" y2="16"/>
                            <line x1="8" y1="12" x2="16" y2="12"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">No sticker sheets yet</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Start creating your first custom sticker sheet with our intuitive design tools. Perfect for labels, barcodes, and more.
                    </p>
                    <Link 
                        :href="route('setup')" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-sm hover:shadow-lg"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/>
                            <path d="M12 5v14"/>
                        </svg>
                        Create Your First Design
                    </Link>
                </div>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="sheet in sheets" :key="sheet.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-medium text-gray-900 truncate" :title="sheet.name || 'Untitled Design'">
                                {{ sheet.name || 'Untitled Design' }}
                            </h3>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                {{ sheet.sticker_width }}x{{ sheet.sticker_height }} {{ sheet.paper_unit }}
                            </span>
                        </div>
                        
                        <div class="aspect-video bg-slate-50 rounded-md mb-4 flex items-center justify-center border border-slate-200 overflow-hidden relative shadow-inner">
                            <div class="absolute inset-0 p-4 flex items-center justify-center">
                                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 transition-transform duration-500 ease-out hover:scale-105" :style="{
                                    transform: `scale(${Math.min(
                                        200 / (parseFloat(sheet.sticker_width) * (sheet.paper_unit === 'in' ? 96 : 3.78)),
                                        120 / (parseFloat(sheet.sticker_height) * (sheet.paper_unit === 'in' ? 96 : 3.78))
                                    )})`,
                                    transformOrigin: 'center center'
                                }">
                                    <StickerRenderer 
                                        :elements="sheet.design_data || []"
                                        :width="parseFloat(sheet.sticker_width)"
                                        :height="parseFloat(sheet.sticker_height)"
                                        :unit="sheet.paper_unit"
                                        class="pointer-events-none"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                            <span class="text-xs text-gray-400">
                                {{ new Date(sheet.updated_at).toLocaleDateString() }}
                            </span>
                            
                            <div class="flex gap-2">
                                <Link :href="route('designer', sheet.id)" title="Edit" class="p-1.5 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 hover:text-indigo-900 rounded-md transition-colors cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                </Link>
                                <Link :href="route('designer.preview', sheet.id)" title="Print" class="p-1.5 text-green-600 bg-green-50 hover:bg-green-100 hover:text-green-900 rounded-md transition-colors cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                </Link>
                                <button @click="duplicateSheet(sheet.id)" title="Duplicate" class="p-1.5 text-gray-600 bg-gray-50 hover:bg-gray-100 hover:text-gray-900 rounded-md transition-colors cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                </button>
                                <button @click="deleteSheet(sheet.id)" title="Delete" class="p-1.5 text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-900 rounded-md transition-colors cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
