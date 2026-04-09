<template>
    <div class="transliterating-textarea">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
            <small v-if="showModeHelp" class="text-muted">
                Mode input:
                <strong>{{ currentModeLabel }}</strong>
            </small>
            <div class="btn-group btn-group-sm align-self-start align-self-md-auto" role="group"
                aria-label="Pilih mode input">
                <input :id="`${fieldId}-normal`" v-model="inputMode" class="btn-check" type="radio" value="normal">
                <label class="btn btn-outline-secondary" :for="`${fieldId}-normal`">Normal</label>

                <input :id="`${fieldId}-hiragana`" v-model="inputMode" class="btn-check" type="radio"
                    value="hiragana">
                <label class="btn btn-outline-secondary" :for="`${fieldId}-hiragana`">Hiragana</label>

                <input :id="`${fieldId}-katakana`" v-model="inputMode" class="btn-check" type="radio"
                    value="katakana">
                <label class="btn btn-outline-secondary" :for="`${fieldId}-katakana`">Katakana</label>
            </div>
        </div>

        <textarea :id="fieldId" ref="textareaRef" :value="modelValue" :rows="rows" :placeholder="placeholder"
            :class="textareaClass" :style="textareaStyle" @input="handleInput"></textarea>

        <small v-if="showModeHelp" class="text-muted d-block mt-2">
            Ketik normal untuk Bahasa Indonesia, atau gunakan mode Jepang agar qwerty otomatis menjadi kana.
        </small>
    </div>
</template>

<script>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import * as wanakana from 'wanakana';

export default {
    name: 'TransliteratingTextarea',
    props: {
        modelValue: {
            type: String,
            default: ''
        },
        rows: {
            type: Number,
            default: 6
        },
        placeholder: {
            type: String,
            default: ''
        },
        textareaClass: {
            type: [String, Array, Object],
            default: 'form-control'
        },
        textareaStyle: {
            type: [String, Object],
            default: null
        },
        defaultMode: {
            type: String,
            default: 'normal'
        },
        showModeHelp: {
            type: Boolean,
            default: true
        },
        inputId: {
            type: String,
            default: ''
        }
    },
    emits: ['update:modelValue', 'mode-change'],
    setup(props, { emit }) {
        const textareaRef = ref(null);
        const inputMode = ref(props.defaultMode);
        const generatedId = `translit-${Math.random().toString(36).slice(2, 10)}`;

        const fieldId = computed(() => props.inputId || generatedId);
        const currentModeLabel = computed(() => {
            switch (inputMode.value) {
                case 'hiragana':
                    return 'Jepang Hiragana';
                case 'katakana':
                    return 'Jepang Katakana';
                default:
                    return 'Normal / Indonesia';
            }
        });

        const bindMode = () => {
            if (!textareaRef.value) {
                return;
            }

            wanakana.unbind(textareaRef.value);

            if (inputMode.value === 'hiragana') {
                wanakana.bind(textareaRef.value, { IMEMode: 'toHiragana' });
            } else if (inputMode.value === 'katakana') {
                wanakana.bind(textareaRef.value, { IMEMode: 'toKatakana' });
            }
        };

        const handleInput = (event) => {
            emit('update:modelValue', event.target.value);
        };

        onMounted(() => {
            bindMode();
        });

        onBeforeUnmount(() => {
            if (textareaRef.value) {
                wanakana.unbind(textareaRef.value);
            }
        });

        watch(inputMode, (newValue) => {
            bindMode();
            emit('mode-change', newValue);
        });

        watch(() => props.defaultMode, (newValue) => {
            inputMode.value = newValue || 'normal';
        });

        watch(() => props.modelValue, (newValue) => {
            if (textareaRef.value && textareaRef.value.value !== newValue) {
                textareaRef.value.value = newValue || '';
            }
        });

        return {
            textareaRef,
            inputMode,
            fieldId,
            currentModeLabel,
            handleInput
        };
    }
};
</script>

<style scoped>
.transliterating-textarea textarea {
    resize: vertical;
}
</style>
