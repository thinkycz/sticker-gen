<script setup>
import { onMounted, nextTick, watch } from 'vue';
import bwipjs from 'bwip-js';

const props = defineProps({
    elements: { type: Array, default: () => [] },
    width: Number,
    height: Number,
    unit: String,
    scale: { type: Number, default: 1 } // Only used for barcode scaling if needed
});

// Unique ID for this renderer instance to scope barcode IDs
const instanceId = Math.random().toString(36).substr(2, 9);

const renderBarcodes = () => {
    nextTick(() => {
        props.elements.filter(el => el.type === 'barcode').forEach(el => {
            try {
                const canvasId = `barcode-${instanceId}-${el.id}`;
                const canvas = document.getElementById(canvasId);
                if (canvas && bwipjs) {
                     // Handle potential module export issues
                     const lib = bwipjs.toCanvas ? bwipjs : (bwipjs.default || bwipjs);
                     if (typeof lib.toCanvas === 'function') {
                        // EAN-13 validation/padding
                        let text = el.content || '123456789012';
                        if (el.barcodeType === 'ean13' && text.length < 12) {
                            text = text.padStart(12, '0');
                        }
                        
                        lib.toCanvas(canvasId, {
                            bcid: el.barcodeType || 'code128',
                            text: text,
                            scale: 2, // Resolution scale
                            height: 10,
                            includetext: el.includeText,
                            textxalign: 'center',
                        });
                     }
                }
            } catch (e) {
                console.error('Barcode render error:', e);
            }
        });
    });
};

onMounted(renderBarcodes);
watch(() => props.elements, renderBarcodes, { deep: true });
</script>

<template>
    <div class="relative overflow-hidden bg-white box-border" 
         :style="{ width: `${width}${unit}`, height: `${height}${unit}` }">
        <div v-for="el in elements" :key="el.id"
            class="absolute box-border"
            :style="{
                left: `${el.x}${unit}`,
                top: `${el.y}${unit}`,
                width: el.width ? `${el.width}${unit}` : 'auto',
                height: el.height ? `${el.height}${unit}` : 'auto',
            }"
        >
             <div v-if="el.type === 'text'" 
                 class="whitespace-nowrap px-1 leading-none"
                 :style="{
                     fontSize: `${el.fontSize}px`,
                     fontFamily: el.fontFamily,
                     textAlign: el.textAlign,
                     fontWeight: el.fontWeight,
                     display: 'flex',
                     alignItems: 'center',
                     justifyContent: el.textAlign === 'center' ? 'center' : (el.textAlign === 'right' ? 'flex-end' : 'flex-start'),
                     width: '100%',
                     height: '100%',
                     overflow: 'hidden'
                 }"
            >
                {{ el.content }}
            </div>
            <div v-if="el.type === 'barcode'" class="w-full h-full">
                <canvas :id="`barcode-${instanceId}-${el.id}`" class="w-full h-full object-contain"></canvas>
            </div>
        </div>
    </div>
</template>
