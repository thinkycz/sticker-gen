<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

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
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer"
            >
                Create New Sheet
            </Link>
        </template>

        <div class="py-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">My Sticker Sheets</h2>
            
            <div v-if="sheets.length === 0" class="text-center py-12 bg-white rounded-lg shadow-sm border border-gray-100">
                <p class="text-gray-500 mb-4">No sticker sheets found.</p>
                <Link :href="route('setup')" class="text-indigo-600 hover:text-indigo-800 font-medium cursor-pointer">
                    Create your first design
                </Link>
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
                        
                        <div class="aspect-video bg-gray-50 rounded-md mb-4 flex items-center justify-center border border-gray-200">
                            <span class="text-gray-400 text-sm">Preview</span>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                            <span class="text-xs text-gray-400">
                                {{ new Date(sheet.updated_at).toLocaleDateString() }}
                            </span>
                            
                            <div class="flex gap-2">
                                <Link :href="route('designer', sheet.id)" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium cursor-pointer">
                                    Edit
                                </Link>
                                <button @click="duplicateSheet(sheet.id)" class="text-gray-600 hover:text-gray-900 text-sm font-medium cursor-pointer">
                                    Duplicate
                                </button>
                                <button @click="deleteSheet(sheet.id)" class="text-red-600 hover:text-red-900 text-sm font-medium cursor-pointer">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
