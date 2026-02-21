<script setup>
import { computed } from 'vue';

const props = defineProps({
    paperWidth: { type: Number, required: true },
    paperHeight: { type: Number, required: true },
    rows: { type: Number, required: true },
    columns: { type: Number, required: true },
    gapHorizontal: { type: Number, default: 0 },
    gapVertical: { type: Number, default: 0 },
    marginTop: { type: Number, default: 0 },
    marginBottom: { type: Number, default: 0 },
    marginLeft: { type: Number, default: 0 },
    marginRight: { type: Number, default: 0 },
});

const paperStyles = computed(() => {
    return {
        width: '100%',
        height: '100%',
        aspectRatio: `${props.paperWidth} / ${props.paperHeight}`,
        gridTemplateColumns: `repeat(${props.columns}, 1fr)`,
        gridTemplateRows: `repeat(${props.rows}, 1fr)`,
        columnGap: `${(props.gapHorizontal / props.paperWidth) * 100}%`,
        rowGap: `${(props.gapVertical / props.paperHeight) * 100}%`,
        padding: `${(props.marginTop / props.paperHeight) * 100}% ${(props.marginRight / props.paperWidth) * 100}% ${(props.marginBottom / props.paperHeight) * 100}% ${(props.marginLeft / props.paperWidth) * 100}%`
    };
});
</script>

<template>
    <div class="w-full h-full flex items-center justify-center p-2">
        <div 
            class="bg-white shadow-sm border border-slate-200 relative grid"
            :style="paperStyles"
        >
            <div 
                v-for="i in (rows * columns)" 
                :key="i"
                class="bg-indigo-50/50 border border-indigo-100 rounded-[1px] w-full h-full"
            ></div>
        </div>
    </div>
</template>
