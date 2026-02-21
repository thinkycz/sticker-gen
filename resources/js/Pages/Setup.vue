<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StickerPreview from '@/Components/StickerPreview.vue';
import { computed, ref, watchEffect } from 'vue';

const props = defineProps({
    errors: Object,
    configs: Array,
});

const form = useForm({
    name: 'Untitled Design',
    paper_unit: 'mm',
    paper_width: 210,
    paper_height: 297,
    sticker_width: 60,
    sticker_height: 30,
    rows: 8,
    columns: 3,
    gap_horizontal: 5,
    gap_vertical: 5,
    margin_top: 10,
    margin_bottom: 10,
    margin_left: 10,
    margin_right: 10,
});

const showSaveModal = ref(false);
const showPresets = ref(false);
const saveForm = useForm({
    name: '',
    paper_unit: '',
    paper_width: 0,
    paper_height: 0,
    sticker_width: 0,
    sticker_height: 0,
    rows: 0,
    columns: 0,
    gap_horizontal: 0,
    gap_vertical: 0,
    margin_top: 0,
    margin_bottom: 0,
    margin_left: 0,
    margin_right: 0,
});

const presets = {
    'A4': { width: 210, height: 297, unit: 'mm' },
    'Letter': { width: 8.5, height: 11, unit: 'in' },
};

const applyPreset = (name) => {
    const preset = presets[name];
    form.paper_width = preset.width;
    form.paper_height = preset.height;
    form.paper_unit = preset.unit;
};

const loadConfig = (config) => {
    form.paper_unit = config.paper_unit;
    form.paper_width = parseFloat(config.paper_width);
    form.paper_height = parseFloat(config.paper_height);
    form.sticker_width = parseFloat(config.sticker_width);
    form.sticker_height = parseFloat(config.sticker_height);
    form.rows = config.rows;
    form.columns = config.columns;
    form.gap_horizontal = parseFloat(config.gap_horizontal);
    form.gap_vertical = parseFloat(config.gap_vertical);
    form.margin_top = parseFloat(config.margin_top);
    form.margin_bottom = parseFloat(config.margin_bottom);
    form.margin_left = parseFloat(config.margin_left);
    form.margin_right = parseFloat(config.margin_right);
};

// Auto-calculate sticker dimensions based on layout
watchEffect(() => {
    // Calculate Width
    if (form.columns > 0) {
        const availableWidth = parseFloat(form.paper_width || 0) - parseFloat(form.margin_left || 0) - parseFloat(form.margin_right || 0) - ((parseFloat(form.columns || 1) - 1) * parseFloat(form.gap_horizontal || 0));
        // Use floor to avoid rounding up which could cause validation errors
        form.sticker_width = Math.max(0, Math.floor((availableWidth / parseFloat(form.columns || 1)) * 100) / 100);
    }

    // Calculate Height
    if (form.rows > 0) {
        const availableHeight = parseFloat(form.paper_height || 0) - parseFloat(form.margin_top || 0) - parseFloat(form.margin_bottom || 0) - ((parseFloat(form.rows || 1) - 1) * parseFloat(form.gap_vertical || 0));
        // Use floor to avoid rounding up which could cause validation errors
        form.sticker_height = Math.max(0, Math.floor((availableHeight / parseFloat(form.rows || 1)) * 100) / 100);
    }
});

const deleteConfig = (id) => {
    if (confirm('Are you sure you want to delete this configuration?')) {
        router.delete(route('configs.destroy', id));
    }
};

const openSaveModal = () => {
    saveForm.name = '';
    saveForm.paper_unit = form.paper_unit;
    saveForm.paper_width = form.paper_width;
    saveForm.paper_height = form.paper_height;
    saveForm.sticker_width = form.sticker_width;
    saveForm.sticker_height = form.sticker_height;
    saveForm.rows = form.rows;
    saveForm.columns = form.columns;
    saveForm.gap_horizontal = form.gap_horizontal;
    saveForm.gap_vertical = form.gap_vertical;
    saveForm.margin_top = form.margin_top;
    saveForm.margin_bottom = form.margin_bottom;
    saveForm.margin_left = form.margin_left;
    saveForm.margin_right = form.margin_right;
    showSaveModal.value = true;
};

const saveConfig = () => {
    saveForm.post(route('configs.store'), {
        onSuccess: () => {
            showSaveModal.value = false;
        },
    });
};

const contentWidth = computed(() => {
    const w = parseFloat(form.margin_left || 0) + 
           (form.columns * parseFloat(form.sticker_width || 0)) + 
           ((form.columns - 1) * parseFloat(form.gap_horizontal || 0)) + 
           parseFloat(form.margin_right || 0);
    return Number(w.toFixed(2));
});

const contentHeight = computed(() => {
    const h = parseFloat(form.margin_top || 0) + 
           (form.rows * parseFloat(form.sticker_height || 0)) + 
           ((form.rows - 1) * parseFloat(form.gap_vertical || 0)) + 
           parseFloat(form.margin_bottom || 0);
    return Number(h.toFixed(2));
});

const isValid = computed(() => {
    return contentWidth.value <= parseFloat(form.paper_width) && 
           contentHeight.value <= parseFloat(form.paper_height);
});

const submit = () => {
    form.post(route('setup.store'));
};
</script>

<template>
    <AppLayout>
        <template #header-actions>
            <div class="flex items-center gap-4">
                <button @click="form.reset()" class="text-slate-500 hover:text-slate-800 font-medium transition-colors text-sm">Reset</button>
                <button 
                    @click="submit" 
                    :disabled="!isValid || form.processing"
                    class="bg-indigo-600 text-white px-5 py-2 rounded-full shadow-lg hover:bg-indigo-700 hover:shadow-indigo-500/30 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none transition-all font-semibold text-sm flex items-center gap-2 cursor-pointer hover:scale-105"
                >
                    Continue
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Setup Form -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white shadow-sm border border-slate-200 rounded-xl p-8">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Sheet Configuration</h2>
                            <p class="text-slate-500 mt-1">Define your paper size and sticker layout.</p>
                        </div>
                        <div class="flex gap-3">
                            <div class="relative">
                                <button @click="showPresets = !showPresets" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 hover:border-slate-400 transition-all flex items-center gap-2 cursor-pointer hover:shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                    Load Preset
                                </button>
                                
                                <!-- Backdrop to close dropdown -->
                                <div v-if="showPresets" @click="showPresets = false" class="fixed inset-0 z-40"></div>
                                
                                <div v-show="showPresets" class="absolute right-0 mt-2 w-72 bg-white shadow-xl rounded-xl border border-slate-100 z-50 overflow-hidden transition-all transform origin-top-right">
                                    <div v-if="configs.length === 0" class="p-4 text-sm text-slate-500 text-center italic">No saved presets yet</div>
                                    <div v-else class="max-h-64 overflow-y-auto">
                                        <div v-for="config in configs" :key="config.id" class="flex justify-between items-center px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0 transition-colors group/item">
                                            <button @click="loadConfig(config); showPresets = false" class="text-sm text-left flex-1 text-slate-700 font-medium hover:text-indigo-600 transition-colors cursor-pointer">
                                                {{ config.name }}
                                            </button>
                                            <button v-if="config.user_id === $page.props.auth.user.id" @click="deleteConfig(config.id)" class="text-slate-300 hover:text-red-500 transition-colors opacity-0 group-hover/item:opacity-100 p-1 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button @click="openSaveModal" class="px-4 py-2 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg text-sm font-medium hover:bg-indigo-100 hover:border-indigo-200 transition-all flex items-center gap-2 cursor-pointer hover:shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Save Preset
                            </button>
                        </div>
                    </div>
                    
                    <div class="space-y-8">
                        <!-- Paper Settings -->
                        <div class="border-b border-slate-100 pb-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 text-xs flex items-center justify-center">1</span>
                                    Paper Dimensions
                                </h3>
                                <div class="flex bg-slate-100 p-1 rounded-lg">
                                    <button @click="applyPreset('A4')" type="button" class="px-3 py-1 text-xs font-medium rounded-md transition-all cursor-pointer" :class="form.paper_width === 210 && form.paper_unit === 'mm' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'">A4</button>
                                    <button @click="applyPreset('Letter')" type="button" class="px-3 py-1 text-xs font-medium rounded-md transition-all cursor-pointer" :class="form.paper_width === 8.5 && form.paper_unit === 'in' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700'">Letter</button>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Width</label>
                                    <div class="relative">
                                        <input v-model="form.paper_width" type="number" step="0.1" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow pl-4 py-2.5 text-center" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Height</label>
                                    <div class="relative">
                                        <input v-model="form.paper_height" type="number" step="0.1" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow pl-4 py-2.5 text-center" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Unit</label>
                                    <select v-model="form.paper_unit" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow py-2.5 text-center cursor-pointer">
                                        <option value="mm">Millimeters (mm)</option>
                                        <option value="in">Inches (in)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Grid & Spacing -->
                        <div class="grid grid-cols-2 gap-8 border-b border-slate-100 pb-8">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 mb-6 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 text-xs flex items-center justify-center">2</span>
                                    Grid Layout
                                </h3>
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Rows</label>
                                        <input v-model="form.rows" type="number" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow py-2.5 text-center" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Columns</label>
                                        <input v-model="form.columns" type="number" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow py-2.5 text-center" />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 mb-6 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 text-xs flex items-center justify-center">3</span>
                                    Gaps
                                </h3>
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Horizontal Gap</label>
                                        <input v-model="form.gap_horizontal" type="number" step="0.1" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow py-2.5 text-center" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Vertical Gap</label>
                                        <input v-model="form.gap_vertical" type="number" step="0.1" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow py-2.5 text-center" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Margins -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 text-xs flex items-center justify-center">4</span>
                                Page Margins
                            </h3>
                            <div class="grid grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Top</label>
                                    <input v-model="form.margin_top" type="number" step="0.1" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow py-2.5 text-center" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Bottom</label>
                                    <input v-model="form.margin_bottom" type="number" step="0.1" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow py-2.5 text-center" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Left</label>
                                    <input v-model="form.margin_left" type="number" step="0.1" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow py-2.5 text-center" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Right</label>
                                    <input v-model="form.margin_right" type="number" step="0.1" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-shadow py-2.5 text-center" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="!isValid" class="mt-8 p-4 bg-red-50 border border-red-100 rounded-xl text-red-600 flex items-start gap-3 animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        <div>
                            <p class="font-semibold">Layout Constraint Error</p>
                            <p v-if="contentWidth > form.paper_width" class="text-sm mt-1 opacity-90">Content width ({{ contentWidth }}) exceeds paper width ({{ form.paper_width }} {{ form.paper_unit }}).</p>
                            <p v-if="contentHeight > form.paper_height" class="text-sm mt-1 opacity-90">Content height ({{ contentHeight }}) exceeds paper height ({{ form.paper_height }} {{ form.paper_unit }}).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Preview -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white shadow-sm border border-slate-200 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Live Preview</h3>
                        <div class="bg-slate-100 p-8 rounded-lg flex justify-center items-center overflow-hidden min-h-[400px] border-inner border-slate-200">
                            <!-- Scaled Paper Preview -->
                            <div class="w-[200px] h-auto flex items-center justify-center transition-all duration-500 transform hover:scale-[1.02]">
                                <StickerPreview 
                                    :paper-width="parseFloat(form.paper_width)"
                                    :paper-height="parseFloat(form.paper_height)"
                                    :rows="form.rows"
                                    :columns="form.columns"
                                    :gap-horizontal="parseFloat(form.gap_horizontal)"
                                    :gap-vertical="parseFloat(form.gap_vertical)"
                                    :margin-top="parseFloat(form.margin_top)"
                                    :margin-bottom="parseFloat(form.margin_bottom)"
                                    :margin-left="parseFloat(form.margin_left)"
                                    :margin-right="parseFloat(form.margin_right)"
                                />
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <div class="flex justify-between text-sm mb-4">
                                <span class="text-slate-500">Content Area</span>
                                <span class="font-medium font-mono text-slate-700">{{ contentWidth }} x {{ contentHeight }} {{ form.paper_unit }}</span>
                            </div>

                            <div class="border-t border-slate-100 pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Sticker Width</span>
                                    <span class="font-medium font-mono text-slate-700">{{ form.sticker_width }} {{ form.paper_unit }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Sticker Height</span>
                                    <span class="font-medium font-mono text-slate-700">{{ form.sticker_height }} {{ form.paper_unit }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Modal -->
        <div v-if="showSaveModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity">
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-96 transform transition-all scale-100">
                <h3 class="text-xl font-bold text-slate-900 mb-2">Save Preset</h3>
                <p class="text-slate-500 text-sm mb-6">Give this configuration a name to easily load it later.</p>
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Preset Name</label>
                    <input v-model="saveForm.name" type="text" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2.5" placeholder="e.g. Avery 5160 Labels">
                    <p v-if="saveForm.errors.name" class="text-red-500 text-xs mt-2">{{ saveForm.errors.name }}</p>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button @click="showSaveModal = false" class="px-4 py-2 text-slate-600 hover:text-slate-900 font-medium">Cancel</button>
                    <button @click="saveConfig" class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all font-medium" :disabled="saveForm.processing">
                        {{ saveForm.processing ? 'Saving...' : 'Save Preset' }}
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
