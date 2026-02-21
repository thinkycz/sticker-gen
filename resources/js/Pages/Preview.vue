<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StickerRenderer from '@/Components/StickerRenderer.vue';

const props = defineProps({
    sheet: Object,
});

const showCutLines = ref(true);
const showMargins = ref(true);
const zoom = ref(100);

const print = () => {
    window.print();
};

const getStickerContent = (row, col) => {
    // In future, could replace variables like {row}, {col}
    // For now, just clone the design data
    // We need unique IDs for barcodes if they depend on content? 
    // Actually StickerRenderer generates unique IDs for canvas.
    // The content is static for now unless we implement variable replacement logic.
    return props.sheet.design_data || [];
};

// Replace variables in content (simple implementation)
const processContent = (elements, row, col) => {
    return elements.map(el => {
        let content = el.content;
        if (el.type === 'text' && content) {
            content = content.replace('{row}', row).replace('{col}', col);
            // Add more variables like {price}, {name} logic here
        }
        return { ...el, content };
    });
};

</script>

<template>
    <AppLayout>
        <template #header-actions>
            <div class="flex items-center space-x-4 print:hidden">
                <Link :href="route('designer', sheet.id)" class="text-slate-600 hover:text-slate-900 font-medium text-sm transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Back to Designer
                </Link>
                <button @click="print" class="bg-indigo-600 text-white px-5 py-2 rounded-full shadow-lg hover:bg-indigo-700 hover:shadow-indigo-500/30 transition-all font-semibold text-sm flex items-center gap-2 cursor-pointer hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                    Print Sheet
                </button>
            </div>
        </template>

        <div class="flex flex-col lg:flex-row gap-8 print:hidden">
            <!-- Controls -->
            <div class="w-full lg:w-64 bg-white shadow-sm border border-slate-200 rounded-xl p-6 h-fit sticky top-24">
                <h3 class="font-medium text-gray-900 mb-4">Preview Controls</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Zoom: {{ zoom }}%</label>
                        <input type="range" min="50" max="200" v-model="zoom" class="w-full mt-2 cursor-pointer">
                    </div>
                    
                    <div class="flex items-center">
                        <input v-model="showCutLines" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        <label class="ml-2 text-sm text-gray-700">Show Cut Lines</label>
                    </div>

                    <div class="flex items-center">
                        <input v-model="showMargins" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        <label class="ml-2 text-sm text-gray-700">Show Margins</label>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Print Checklist</h4>
                    <ul class="text-sm text-gray-600 space-y-2 list-disc pl-4">
                        <li>Paper size: {{ sheet.paper_width }}x{{ sheet.paper_height }}{{ sheet.paper_unit }}</li>
                        <li>Scale: 100% (Do not "Fit to Page")</li>
                        <li>Margins: Default/None</li>
                    </ul>
                </div>
            </div>

            <!-- Preview Area -->
            <div class="flex-1 bg-gray-200 p-8 rounded-lg overflow-auto flex justify-center">
                <div 
                    class="bg-white shadow-lg relative transition-transform origin-top-center box-border"
                    :style="{
                        width: `${sheet.paper_width}${sheet.paper_unit}`,
                        height: `${sheet.paper_height}${sheet.paper_unit}`,
                        transform: `scale(${zoom / 100})`,
                        marginBottom: `${(zoom / 100) * 100}px` // Add some bottom space for scroll
                    }"
                >
                    <!-- Grid Container -->
                    <div class="absolute inset-0"
                        :style="{
                            padding: `${sheet.margin_top}${sheet.paper_unit} ${sheet.margin_right}${sheet.paper_unit} ${sheet.margin_bottom}${sheet.paper_unit} ${sheet.margin_left}${sheet.paper_unit}`
                        }"
                    >
                        <div class="grid w-full h-full"
                             :style="{
                                 gridTemplateColumns: `repeat(${sheet.columns}, 1fr)`,
                                 gridTemplateRows: `repeat(${sheet.rows}, 1fr)`,
                                 columnGap: `${sheet.gap_horizontal}${sheet.paper_unit}`,
                                 rowGap: `${sheet.gap_vertical}${sheet.paper_unit}`,
                             }"
                        >
                            <div v-for="i in (sheet.rows * sheet.columns)" :key="i" 
                                 class="relative overflow-hidden"
                                 :class="{ 'border border-dashed border-gray-300': showCutLines }"
                            >
                                <StickerRenderer 
                                    :elements="processContent(sheet.design_data || [], Math.ceil(i/sheet.columns), (i-1)%sheet.columns + 1)"
                                    :width="Number(sheet.sticker_width)"
                                    :height="Number(sheet.sticker_height)"
                                    :unit="sheet.paper_unit"
                                    :use-physical-units="true"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Margin Visuals (Overlay) -->
                    <div v-if="showMargins" class="absolute inset-0 pointer-events-none border border-blue-200 opacity-50"
                         :style="{
                             borderTopWidth: `${sheet.margin_top}${sheet.paper_unit}`,
                             borderBottomWidth: `${sheet.margin_bottom}${sheet.paper_unit}`,
                             borderLeftWidth: `${sheet.margin_left}${sheet.paper_unit}`,
                             borderRightWidth: `${sheet.margin_right}${sheet.paper_unit}`,
                         }"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Print View (Hidden on Screen) -->
        <div class="hidden print:block absolute top-0 left-0 w-full h-full bg-white z-[100]">
            <div 
                class="relative box-border"
                :style="{
                    width: `${sheet.paper_width}${sheet.paper_unit}`,
                    height: `${sheet.paper_height}${sheet.paper_unit}`,
                }"
            >
                <div class="absolute inset-0"
                    :style="{
                        padding: `${sheet.margin_top}${sheet.paper_unit} ${sheet.margin_right}${sheet.paper_unit} ${sheet.margin_bottom}${sheet.paper_unit} ${sheet.margin_left}${sheet.paper_unit}`
                    }"
                >
                    <div class="grid w-full h-full"
                            :style="{
                                gridTemplateColumns: `repeat(${sheet.columns}, 1fr)`,
                                gridTemplateRows: `repeat(${sheet.rows}, 1fr)`,
                                columnGap: `${sheet.gap_horizontal}${sheet.paper_unit}`,
                                rowGap: `${sheet.gap_vertical}${sheet.paper_unit}`,
                            }"
                    >
                        <div v-for="i in (sheet.rows * sheet.columns)" :key="i" 
                                class="relative overflow-hidden"
                                :class="{ 'border border-dashed border-gray-300': showCutLines }"
                        >
                            <StickerRenderer 
                                :elements="processContent(sheet.design_data || [], Math.ceil(i/sheet.columns), (i-1)%sheet.columns + 1)"
                                :width="Number(sheet.sticker_width)"
                                :height="Number(sheet.sticker_height)"
                                :unit="sheet.paper_unit"
                                :use-physical-units="true"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
@media print {
    @page {
        size: auto;
        margin: 0;
    }
    body {
        background: white;
    }
    nav, header, footer, .print\:hidden {
        display: none !important;
    }
    .print\:block {
        display: block !important;
    }
}
</style>
