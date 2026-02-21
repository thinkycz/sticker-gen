<script setup>
import { ref, computed, onMounted, nextTick, watch } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import bwipjs from 'bwip-js';
import { useTranslations } from '@/composables/useTranslations';

const { __ } = useTranslations();

const props = defineProps({
    sheet: Object,
});

const elements = ref((props.sheet && props.sheet.design_data) || []);
const selectedId = ref(null);
const draggingId = ref(null);
const dragOffset = ref({ x: 0, y: 0 });
const canvasRef = ref(null);
const fileInput = ref(null);


const selectedElement = computed(() => {
    return elements.value.find(el => el.id === selectedId.value);
});

// Canvas Scaling
const scale = ref(1);
const updateScale = () => {
    // Calculate scale to fit canvas in container with padding
};

const unitToPx = (val) => {
    if (!props.sheet || val === undefined || val === null) return 0;
    // 1 inch = 96px (CSS standard)
    // 1 mm = 3.78px
    const unit = props.sheet.paper_unit;
    if (unit === 'in') return val * 96;
    return val * 3.7795;
};

const pxToUnit = (val) => {
    if (!props.sheet || val === undefined || val === null) return 0;
    const unit = props.sheet.paper_unit;
    if (unit === 'in') return val / 96;
    return val / 3.7795;
};

const zoom = ref(2); // Default 200% zoom for better visibility

const canvasStyle = computed(() => {
    if (!props.sheet) return {};
    return {
        width: `${unitToPx(props.sheet.sticker_width)}px`,
        height: `${unitToPx(props.sheet.sticker_height)}px`,
        transform: `scale(${zoom.value})`,
        transformOrigin: 'center',
    };
});

// Actions
const resizingId = ref(null);
const resizeStart = ref({ x: 0, y: 0, w: 0, h: 0 });

const addElement = (type) => {
    const id = Date.now().toString();
    const newEl = {
        id,
        type,
        x: 5,
        y: 5,
        width: type === 'barcode' ? 30 : 40,
        height: type === 'barcode' ? 15 : 10,
        content: type === 'text' ? 'New Text' : '12345678',
        fontSize: 12,
        fontFamily: 'Arial',
        textAlign: 'left',
        barcodeType: 'code128', // or 'ean13', 'qrcode'
        includeText: true,
        lockAspectRatio: type === 'barcode' ? false : true, // Images locked by default
    };
    if (type === 'barcode' && newEl.barcodeType === 'qrcode') {
        newEl.width = 20;
        newEl.height = 20;
        newEl.lockAspectRatio = true;
    }
    elements.value.push(newEl);
    selectedId.value = id;
};

const triggerImageUpload = () => {
    fileInput.value.click();
};

const handleImageUpload = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (event) => {
        const id = Date.now().toString();
        const newEl = {
            id,
            type: 'image',
            x: 5,
            y: 5,
            width: 40,
            height: 40,
            content: event.target.result, // Base64
            lockAspectRatio: true,
        };
        elements.value.push(newEl);
        selectedId.value = id;
    };
    reader.readAsDataURL(file);
    // Reset input
    e.target.value = '';
};

const replaceImage = (e) => {
    const file = e.target.files[0];
    if (!file || !selectedElement.value) return;

    const reader = new FileReader();
    reader.onload = (event) => {
        selectedElement.value.content = event.target.result;
    };
    reader.readAsDataURL(file);
    e.target.value = '';
};



const startResize = (e, element) => {
    e.preventDefault();
    e.stopPropagation();
    
    const startX = e.clientX;
    const startY = e.clientY;
    
    // Store initial dimensions in units (fallback to defaults if undefined)
    const initialW = element.width || (element.type === 'barcode' ? 30 : 40);
    const initialH = element.height || (element.type === 'barcode' ? 15 : 10);
    
    const onMouseMove = (moveEvent) => {
        // Delta in screen pixels adjusted for zoom
        const dx = (moveEvent.clientX - startX) / zoom.value;
        const dy = (moveEvent.clientY - startY) / zoom.value;
        
        // Convert initial unit width to px, add delta, convert back to unit
        let newW = pxToUnit(unitToPx(initialW) + dx);
        let newH = pxToUnit(unitToPx(initialH) + dy);
        
        if (element.lockAspectRatio && initialW > 0 && initialH > 0) {
            const ratio = initialW / initialH;
            // Use the larger delta to drive the scaling
            if (Math.abs(dx) > Math.abs(dy)) {
                newH = newW / ratio;
            } else {
                newW = newH * ratio;
            }
        }

        // Update element if above minimum size
        if (newW > 1) element.width = Number(newW.toFixed(2));
        if (newH > 1) element.height = Number(newH.toFixed(2));
    };
    
    const onMouseUp = () => {
        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', onMouseUp);
    };
    
    document.addEventListener('mousemove', onMouseMove);
    document.addEventListener('mouseup', onMouseUp);
};


const removeElement = (id) => {
    elements.value = elements.value.filter(el => el.id !== id);
    if (selectedId.value === id) selectedId.value = null;
};

const selectElement = (id) => {
    selectedId.value = id;
};

// Dragging Logic
const startDrag = (e, element) => {
    e.preventDefault();
    e.stopPropagation();
    selectedId.value = element.id;
    draggingId.value = element.id;
    
    const startX = e.clientX;
    const startY = e.clientY;
    
    // Convert current unit position to px for calculation
    const currentPxX = unitToPx(element.x);
    const currentPxY = unitToPx(element.y);
    
    const onMouseMove = (moveEvent) => {
        // Delta in screen pixels
        const dx = (moveEvent.clientX - startX) / zoom.value; // Adjust for zoom
        const dy = (moveEvent.clientY - startY) / zoom.value;
        
        let newX = pxToUnit(currentPxX + dx);
        let newY = pxToUnit(currentPxY + dy);
        
        element.x = Number(newX.toFixed(2));
        element.y = Number(newY.toFixed(2));
    };
    
    const onMouseUp = () => {
        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', onMouseUp);
        draggingId.value = null;
    };
    
    document.addEventListener('mousemove', onMouseMove);
    document.addEventListener('mouseup', onMouseUp);
};

// Saving
const form = useForm({
    design_data: [],
    name: props.sheet.name || __('untitled_design'),
});

const save = () => {
    form.design_data = elements.value;
    form.put(route('designer.update', props.sheet.id), {
        preserveScroll: true,
        onSuccess: () => {
        }
    });
};

const saveAndPreview = () => {
    form.design_data = elements.value;
    form.put(route('designer.update', props.sheet.id), {
        preserveScroll: true,
        onSuccess: () => {
            router.visit(route('designer.preview', props.sheet.id));
        }
    });
};

watch(elements, () => {
    nextTick(() => {
        elements.value.filter(el => el.type === 'barcode').forEach(el => {
            try {
                const canvasId = `barcode-${el.id}`;
                const canvas = document.getElementById(canvasId);
                if (canvas && bwipjs) {
                    const lib = bwipjs.toCanvas ? bwipjs : (bwipjs.default || bwipjs);
                    
                    if (typeof lib.toCanvas === 'function') {
                        const options = {
                            bcid: el.barcodeType || 'code128',
                            text: el.content || '123456',
                            scale: 2,
                            includetext: el.includeText,
                            textxalign: 'center',
                        };
                        
                        // For standard barcodes, height is needed. For QR, it's usually automatic/square.
                        if (el.barcodeType !== 'qrcode') {
                            options.height = 10;
                        }

                        lib.toCanvas(canvasId, options);
                    }
                }
            } catch (e) {
                console.error('Barcode render error', e);
            }
        });
    });
}, { deep: true, immediate: true });


onMounted(() => {
});

</script>

<template>
    <AppLayout>
        <template #header-actions>
            <div class="flex items-center space-x-4">
                <div class="mr-4">
                     <input 
                        v-model="form.name" 
                        type="text" 
                        class="border-transparent bg-transparent focus:border-indigo-300 focus:ring-0 text-slate-700 font-semibold text-sm px-2 py-1 rounded hover:bg-slate-100 transition-colors w-48 text-center placeholder-slate-400"
                        :placeholder="__('untitled_design')"
                    />
                </div>
                <Link :href="route('setup')" class="text-slate-600 hover:text-slate-900 font-medium text-sm transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    {{ __('back') }}
                </Link>
                <button @click="save" class="text-slate-600 hover:text-indigo-600 font-medium text-sm transition-colors flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-slate-50 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    {{ __('save_draft') }}
                </button>
                <button 
                    @click="saveAndPreview" 
                    :disabled="form.processing"
                    class="bg-indigo-600 text-white px-5 py-2 rounded-full shadow-lg hover:bg-indigo-700 hover:shadow-indigo-500/30 transition-all font-semibold text-sm flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed cursor-pointer hover:scale-105"
                >
                    {{ form.processing ? __('saving') : __('print') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                </button>
            </div>
        </template>

        <div class="h-[calc(100vh-8rem)] flex gap-6">
            <!-- Left: Elements -->
            <div class="w-64 bg-white shadow-sm border border-slate-200 rounded-xl flex flex-col overflow-hidden">
                <div class="p-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-900">{{ __('toolbox') }}</h3>
                </div>
                
                <div class="p-4 space-y-3">
                    <button @click="addElement('text')" class="w-full flex items-center p-3 bg-white hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 rounded-lg border border-slate-200 hover:border-indigo-200 transition-all group shadow-sm cursor-pointer hover:shadow-md">
                        <div class="w-8 h-8 rounded bg-slate-100 group-hover:bg-indigo-100 text-slate-500 group-hover:text-indigo-600 flex items-center justify-center mr-3 transition-colors">
                            <span class="font-serif text-lg">Aa</span>
                        </div>
                        <span class="font-medium text-sm">{{ __('text_field') }}</span>
                    </button>
                    <button @click="addElement('barcode')" class="w-full flex items-center p-3 bg-white hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 rounded-lg border border-slate-200 hover:border-indigo-200 transition-all group shadow-sm cursor-pointer hover:shadow-md">
                        <div class="w-8 h-8 rounded bg-slate-100 group-hover:bg-indigo-100 text-slate-500 group-hover:text-indigo-600 flex items-center justify-center mr-3 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 5v14"/><path d="M8 5v14"/><path d="M12 5v14"/><path d="M17 5v14"/><path d="M21 5v14"/></svg>
                        </div>
                        <span class="font-medium text-sm">{{ __('barcode') }}</span>
                    </button>
                    <button @click="triggerImageUpload" class="w-full flex items-center p-3 bg-white hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 rounded-lg border border-slate-200 hover:border-indigo-200 transition-all group shadow-sm cursor-pointer hover:shadow-md">
                        <div class="w-8 h-8 rounded bg-slate-100 group-hover:bg-indigo-100 text-slate-500 group-hover:text-indigo-600 flex items-center justify-center mr-3 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        </div>
                        <span class="font-medium text-sm">{{ __('image') }}</span>
                    </button>
                    <input type="file" ref="fileInput" class="hidden" accept="image/*" @change="handleImageUpload" />
                </div>
                
                <div class="mt-auto border-t border-slate-100 flex flex-col flex-1 min-h-0">
                    <div class="p-3 bg-slate-50 border-b border-slate-100">
                        <h3 class="font-medium text-xs uppercase tracking-wider text-slate-500">{{ __('layers') }}</h3>
                    </div>
                    <div class="flex-1 overflow-y-auto p-2 space-y-1">
                        <div v-if="elements.length === 0" class="text-center py-8 text-slate-400 text-xs italic">
                            {{ __('no_layers_yet') }}
                        </div>
                        <div 
                            v-for="(el, index) in elements.slice().reverse()" 
                            :key="el.id"
                            @click="selectElement(el.id)"
                            class="p-2.5 text-sm rounded-lg cursor-pointer flex justify-between items-center transition-colors group"
                            :class="selectedId === el.id ? 'bg-indigo-50 text-indigo-700 font-medium' : 'hover:bg-slate-50 text-slate-700'"
                        >
                            <div class="flex items-center gap-2 overflow-hidden">
                                <span class="w-4 text-xs text-slate-400 font-mono">{{ elements.length - index }}</span>
                                <svg v-if="el.type === 'text'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-70"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/></svg>
                                <svg v-else-if="el.type === 'barcode'" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-70"><path d="M3 5v14"/><path d="M8 5v14"/><path d="M12 5v14"/><path d="M17 5v14"/><path d="M21 5v14"/></svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-70"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                <span class="truncate">{{ el.id === draggingId ? __('dragging') : (el.type === 'image' ? __('image') : (el.content || __(el.type))) }}</span>
                            </div>
                            <button @click.stop="removeElement(el.id)" class="text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Center: Canvas -->
            <div class="flex-1 bg-slate-100 rounded-xl flex items-center justify-center overflow-auto p-8 relative border-inner border-slate-200 shadow-inner">
                <div 
                    ref="canvasRef"
                    class="bg-white shadow-2xl relative overflow-hidden transition-all ring-1 ring-slate-900/5"
                    :style="canvasStyle"
                    @click.self="selectedId = null"
                >
                    <!-- Grid Lines / Rulers (Optional visual aid) -->
                    <div class="absolute inset-0 pointer-events-none opacity-[0.03]" 
                         style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;">
                    </div>

                    <div
                            v-for="element in elements"
                            :key="element.id"
                            class="absolute cursor-move group border-2 transition-colors box-border"
                            :class="selectedId === element.id ? 'border-indigo-500 z-10 shadow-sm' : 'border-transparent hover:border-indigo-300'"
                            :style="{
                                left: `${unitToPx(element.x)}px`,
                                top: `${unitToPx(element.y)}px`,
                                width: element.width ? `${unitToPx(element.width)}px` : 'auto',
                                height: element.height ? `${unitToPx(element.height)}px` : 'auto',
                            }"
                            @mousedown="startDrag($event, element)"
                            @click="selectElement(element.id)"
                        >
                        <!-- Text Render -->
                        <div v-if="element.type === 'text'" 
                             class="whitespace-nowrap px-1"
                             :style="{
                                 fontSize: `${element.fontSize}px`,
                                 fontFamily: element.fontFamily,
                                 textAlign: element.textAlign,
                                 fontWeight: element.fontWeight,
                                 display: 'flex',
                                 alignItems: 'center',
                                 justifyContent: element.textAlign === 'center' ? 'center' : (element.textAlign === 'right' ? 'flex-end' : 'flex-start'),
                                 overflow: 'hidden',
                                 width: '100%',
                                 height: '100%'
                             }"
                        >
                            {{ element.content }}
                        </div>

                        <!-- Barcode Render -->
                        <div v-if="element.type === 'barcode'" class="pointer-events-none w-full h-full">
                            <canvas :id="`barcode-${element.id}`" class="w-full h-full object-contain"></canvas>
                        </div>

                        <!-- Image Render -->
                        <div v-if="element.type === 'image'" class="pointer-events-none w-full h-full">
                            <img :src="element.content" class="w-full h-full object-contain" />
                        </div>

                        
                        <!-- Resize Handles -->
                        <div v-if="selectedId === element.id" 
                             class="absolute -bottom-1.5 -right-1.5 w-3 h-3 bg-white border-2 border-indigo-500 rounded-full cursor-se-resize shadow-sm z-20"
                             @mousedown.stop="startResize($event, element)"
                        ></div>
                    </div>
                </div>
                
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-white/90 backdrop-blur px-4 py-2 rounded-full shadow-sm border border-slate-200 text-xs font-medium text-slate-500 flex gap-4">
                     <span>{{ props.sheet.sticker_width }}{{ props.sheet.paper_unit }} x {{ props.sheet.sticker_height }}{{ props.sheet.paper_unit }}</span>
                     <span class="w-px h-4 bg-slate-300"></span>
                     <span>{{ __('zoom') }}: {{ zoom * 100 }}%</span>
                </div>
            </div>

            <!-- Right: Properties -->
            <div class="w-72 bg-white shadow-sm border border-slate-200 rounded-xl flex flex-col overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-semibold text-slate-900">{{ __('properties') }}</h3>
                </div>
                
                <div class="flex-1 overflow-y-auto p-4">
                    <div v-if="selectedElement" class="space-y-6">
                        <!-- Common -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('position') }}</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">X ({{ sheet.paper_unit }})</label>
                                    <input v-model.number="selectedElement.x" type="number" step="0.1" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5 text-center" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Y ({{ sheet.paper_unit }})</label>
                                    <input v-model.number="selectedElement.y" type="number" step="0.1" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5 text-center" />
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <!-- Text Specific -->
                        <div v-if="selectedElement.type === 'text'" class="space-y-4">
                             <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('typography') }}</h4>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">{{ __('content') }}</label>
                                <input v-model="selectedElement.content" type="text" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5 text-center" />
                                <div class="flex gap-1 mt-1.5 flex-wrap">
                                    <button @click="selectedElement.content += '{price}'" class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 hover:bg-slate-200 border border-slate-200 cursor-pointer">{{ __('add_price') }}</button>
                                    <button @click="selectedElement.content += '{name}'" class="text-[10px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 hover:bg-slate-200 border border-slate-200 cursor-pointer">{{ __('add_name') }}</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">{{ __('size_px') }}</label>
                                    <input v-model.number="selectedElement.fontSize" type="number" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5 text-center" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">{{ __('weight') }}</label>
                                    <select v-model="selectedElement.fontWeight" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5">
                                        <option value="normal">{{ __('normal') }}</option>
                                        <option value="bold">{{ __('bold') }}</option>
                                        <option value="100">{{ __('thin') }}</option>
                                        <option value="900">{{ __('black') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Barcode Specific -->
                        <div v-if="selectedElement.type === 'barcode'" class="space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('barcode_settings') }}</h4>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">{{ __('barcode_value') }}</label>
                                <input v-model="selectedElement.content" type="text" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5 text-center" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">{{ __('barcode_type') }}</label>
                                <select 
                                    v-model="selectedElement.barcodeType" 
                                    @change="selectedElement.barcodeType === 'qrcode' ? (selectedElement.lockAspectRatio = true, selectedElement.height = selectedElement.width) : null"
                                    class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5"
                                >
                                    <option value="code128">Code 128</option>
                                    <option value="ean13">EAN-13</option>
                                    <option value="qrcode">QR Code</option>
                                </select>
                            </div>
                            <div class="flex items-center pt-2">
                                <input id="includeText" v-model="selectedElement.includeText" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                <label for="includeText" class="ml-2 block text-sm text-slate-600">{{ __('show_human_readable') }}</label>
                            </div>
                            <div class="flex items-center pt-2">
                                <input id="lockAspectBarcode" v-model="selectedElement.lockAspectRatio" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                <label for="lockAspectBarcode" class="ml-2 block text-sm text-slate-600">{{ __('lock_aspect_ratio') }}</label>
                            </div>
                        </div>

                        <!-- Image Specific -->
                        <div v-if="selectedElement.type === 'image'" class="space-y-4">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('image_settings') }}</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">{{ __('width') }} ({{ sheet.paper_unit }})</label>
                                    <input v-model.number="selectedElement.width" type="number" step="0.1" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5 text-center" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">{{ __('height') }} ({{ sheet.paper_unit }})</label>
                                    <input v-model.number="selectedElement.height" type="number" step="0.1" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5 text-center" />
                                </div>
                            </div>
                            <div class="flex items-center pt-2">
                                <input id="lockAspect" v-model="selectedElement.lockAspectRatio" type="checkbox" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                <label for="lockAspect" class="ml-2 block text-sm text-slate-600">{{ __('lock_aspect_ratio') }}</label>
                            </div>
                            <div class="pt-2">
                                <label class="w-full flex items-center justify-center p-2 bg-indigo-50 text-indigo-700 rounded-lg border border-indigo-200 hover:bg-indigo-100 transition-all cursor-pointer text-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"/><polyline points="16 5 21 5 21 10"/><path d="m21 5-7 7"/></svg>
                                    {{ __('replace_image') }}
                                    <input type="file" class="hidden" accept="image/*" @change="replaceImage" />
                                </label>
                            </div>
                        </div>

                    </div>
                    <div v-else class="h-full flex flex-col items-center justify-center text-slate-400 text-sm text-center p-8 opacity-60">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-3 text-slate-300"><path d="M3 3h18v18H3z"/><path d="m21 3-9 9"/><path d="m21 9-9-9"/><path d="M12 3v9"/></svg>
                        <p>{{ __('select_element_tip') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
