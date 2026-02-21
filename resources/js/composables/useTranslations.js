import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useTranslations() {
    const page = usePage();
    
    const translations = computed(() => 
        page.props.translations?.messages || {}
    );

    const __ = (key, replacements = {}) => {
        const translation = translations.value[key] || key;
        
        return Object.keys(replacements).reduce(
            (str, replacementKey) => 
                str.replace(`:${replacementKey}`, replacements[replacementKey]),
            translation
        );
    };

    return { __ };
}
