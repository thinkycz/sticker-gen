<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StickerRenderer from '@/Components/StickerRenderer.vue';

const props = defineProps({
    sheets: Array,
});


const duplicateSheet = (id) => {
    if (confirm('Create a copy of this design?')) {
        router.post(route('designer.duplicate', id));
    }
};

const deleteSheet = (id) => {
    if (confirm('Are you sure you want to delete this design?')) {
        router.delete(route('designer.destroy', id));
    }
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>

<template>
    <AppLayout>
        <div class="space-y-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Your Designs</h2>
                    <p class="text-slate-500 mt-1">Manage and print your sticker sheets.</p>
                </div>
                <Link :href="route('setup')" class="bg-indigo-600 text-white px-6 py-2.5 rounded-full shadow-lg hover:bg-indigo-700 hover:shadow-indigo-500/30 transition-all font-semibold flex items-center gap-2 cursor-pointer hover:scale-[1.02]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Create New Design
                </Link>
            </div>

            <div v-if="sheets.length === 0" class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">No designs yet</h3>
                <p class="text-slate-500 mb-6 max-w-sm mx-auto">Get started by creating your first sticker sheet layout. You can customize dimensions and add elements.</p>
                <Link :href="route('setup')" class="text-indigo-600 font-medium hover:text-indigo-800 transition-colors">
                    Start a new design &rarr;
                </Link>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="sheet in sheets" :key="sheet.id" class="bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow overflow-hidden group">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-semibold text-slate-900 text-lg mb-1">
                                    {{ sheet.name || 'Untitled Design' }}
                                </h3>
                                <p class="text-sm text-slate-500">
                                    {{ sheet.paper_width }}x{{ sheet.paper_height }}{{ sheet.paper_unit }} &bull; {{ sheet.rows }}x{{ sheet.columns }} &bull; {{ formatDate(sheet.created_at) }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Mini Preview -->
                        <div class="bg-slate-50 rounded-lg p-4 mb-6 flex justify-center items-center h-48 border border-slate-100 overflow-hidden relative">
                             <div class="transform scale-75 origin-center shadow-md">
                                 <StickerRenderer 
                                     :elements="sheet.design_data || []"
                                     :width="parseFloat(sheet.sticker_width)"
                                     :height="parseFloat(sheet.sticker_height)"
                                     :unit="sheet.paper_unit"
                                 />
                             </div>
                             
                             <div class="absolute bottom-2 right-2 text-[10px] font-mono text-slate-500 bg-white/90 backdrop-blur px-2 py-0.5 rounded border border-slate-200 shadow-sm">
                                 {{ sheet.sticker_width }}x{{ sheet.sticker_height }}{{ sheet.paper_unit }}
                             </div>
                        </div>

                        <div class="flex gap-3">
                            <Link :href="route('designer', sheet.id)" class="flex-1 bg-white border border-slate-300 text-slate-700 py-2 rounded-lg text-center text-sm font-medium hover:bg-slate-50 transition-colors hover:shadow-sm">
                                Edit
                            </Link>
                            <Link :href="route('preview', sheet.id)" class="flex-1 bg-indigo-50 border border-indigo-100 text-indigo-700 py-2 rounded-lg text-center text-sm font-medium hover:bg-indigo-100 transition-colors hover:shadow-sm">
                                Print
                            </Link>
                            <button @click="duplicateSheet(sheet.id)" class="px-3 py-2 bg-white border border-slate-300 text-slate-500 rounded-lg hover:bg-slate-50 hover:text-indigo-600 transition-colors hover:shadow-sm cursor-pointer" title="Duplicate">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            </button>
                            <button @click="deleteSheet(sheet.id)" class="px-3 py-2 bg-white border border-slate-300 text-slate-500 rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors hover:shadow-sm cursor-pointer" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
