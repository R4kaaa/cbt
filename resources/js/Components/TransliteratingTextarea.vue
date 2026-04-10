<template>
    <div class="transliterating-textarea">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
            <small v-if="showModeHelp" class="text-muted">
                Mode input:
                <strong>{{ currentModeLabel }}</strong>
            </small>
            <div class="btn-group btn-group-sm align-self-start align-self-md-auto flex-wrap" role="group"
                aria-label="Pilih mode input">
                <input :id="`${fieldId}-normal`" v-model="inputMode" class="btn-check" type="radio" value="normal">
                <label class="btn btn-outline-secondary" :for="`${fieldId}-normal`">Normal</label>

                <input :id="`${fieldId}-hiragana`" v-model="inputMode" class="btn-check" type="radio"
                    value="hiragana">
                <label class="btn btn-outline-secondary" :for="`${fieldId}-hiragana`">Hiragana</label>

                <input :id="`${fieldId}-katakana`" v-model="inputMode" class="btn-check" type="radio"
                    value="katakana">
                <label class="btn btn-outline-secondary" :for="`${fieldId}-katakana`">Katakana</label>

                <input :id="`${fieldId}-kanji`" v-model="inputMode" class="btn-check" type="radio" value="kanji">
                <label class="btn btn-outline-secondary" :for="`${fieldId}-kanji`">Kanji</label>
            </div>
        </div>

        <textarea :id="fieldId" ref="textareaRef" :value="modelValue" :rows="rows" :placeholder="placeholder"
            :class="textareaClass" :style="textareaStyle" @input="handleInput"></textarea>

        <div v-if="inputMode === 'kanji'" class="kanji-panel mt-2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                <small class="text-muted">
                    Token aktif:
                    <strong>{{ activeKanaToken || '-' }}</strong>
                </small>
                <button type="button" class="btn btn-sm btn-primary align-self-start align-self-md-auto"
                    :disabled="!activeKanaToken || kanjiSuggestions.length === 0" @click="convertFirstSuggestion">
                    Convert Kanji
                </button>
            </div>

            <small class="text-muted d-block mt-2">
                Kandidat lokal: {{ localSuggestions.length }} | kandidat online: {{ onlineSuggestions.length }}
                <span v-if="isLoadingOnline">| memuat online...</span>
            </small>

            <div v-if="kanjiSuggestions.length > 0" class="mt-2">
                <small class="text-muted d-block mb-2">
                    Pilih kandidat untuk <strong>{{ activeKanaToken }}</strong>:
                </small>
                <div class="d-flex flex-wrap gap-2">
                    <button v-for="suggestion in kanjiSuggestions" :key="suggestion" type="button"
                        class="btn btn-sm btn-outline-primary" @click="applyKanjiSuggestion(suggestion)">
                        {{ suggestion }}
                    </button>
                </div>
            </div>

            <small v-else class="text-muted d-block mt-2">
                Belum ada kandidat untuk token ini. Ketik hiragana lalu tekan `Convert Kanji` saat kata dikenali.
            </small>
        </div>

        <small v-if="showModeHelp" class="text-muted d-block mt-2">
            `Normal` untuk ketik biasa, `Hiragana/Katakana` untuk transliterasi qwerty, dan `Kanji` untuk saran
            kandidat berbasis kamus lokal.
        </small>
    </div>
</template>

<script>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import axios from 'axios';
import * as wanakana from 'wanakana';
import { KANJI_SUGGESTION_MAP } from '../Constants/japaneseKanjiSuggestions';

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
        const hasBinding = ref(false);
        const activeKanaToken = ref('');
        const onlineSuggestions = ref([]);
        const isLoadingOnline = ref(false);
        let onlineFetchTimeout = null;
        const generatedId = `translit-${Math.random().toString(36).slice(2, 10)}`;

        const fieldId = computed(() => props.inputId || generatedId);
        const currentModeLabel = computed(() => {
            switch (inputMode.value) {
                case 'hiragana':
                    return 'Jepang Hiragana';
                case 'katakana':
                    return 'Jepang Katakana';
                case 'kanji':
                    return 'Jepang Kanji (Eksperimen)';
                default:
                    return 'Normal / Indonesia';
            }
        });
        const localSuggestions = computed(() => {
            if (!activeKanaToken.value) {
                return [];
            }

            return KANJI_SUGGESTION_MAP[activeKanaToken.value] || [];
        });
        const kanjiSuggestions = computed(() => {
            return Array.from(new Set([...localSuggestions.value, ...onlineSuggestions.value]));
        });

        const updateKanjiSuggestions = (value) => {
            if (inputMode.value !== 'kanji') {
                activeKanaToken.value = '';
                onlineSuggestions.value = [];
                return;
            }

            const tokens = (value || '').match(/[ぁ-ゖー]+/g) || [];
            activeKanaToken.value = tokens[tokens.length - 1] || '';
        };

        const fetchOnlineSuggestions = async () => {
            if (inputMode.value !== 'kanji' || !activeKanaToken.value || activeKanaToken.value.length < 2) {
                onlineSuggestions.value = [];
                return;
            }

            isLoadingOnline.value = true;

            try {
                const response = await axios.get('/japanese-dictionary/suggest', {
                    params: {
                        query: activeKanaToken.value
                    }
                });
                onlineSuggestions.value = response.data?.suggestions || [];
            } catch (error) {
                onlineSuggestions.value = [];
                console.error('Gagal mengambil kandidat kanji online:', error);
            } finally {
                isLoadingOnline.value = false;
            }
        };

        const bindMode = () => {
            if (!textareaRef.value) {
                return;
            }

            if (hasBinding.value) {
                wanakana.unbind(textareaRef.value);
                hasBinding.value = false;
            }

            if (inputMode.value === 'hiragana' || inputMode.value === 'kanji') {
                wanakana.bind(textareaRef.value, { IMEMode: 'toHiragana' });
                hasBinding.value = true;
            } else if (inputMode.value === 'katakana') {
                wanakana.bind(textareaRef.value, { IMEMode: 'toKatakana' });
                hasBinding.value = true;
            }

            updateKanjiSuggestions(textareaRef.value.value || '');
        };

        const handleInput = (event) => {
            updateKanjiSuggestions(event.target.value);
            emit('update:modelValue', event.target.value);
        };

        const applyKanjiSuggestion = (suggestion) => {
            if (!textareaRef.value || !activeKanaToken.value) {
                return;
            }

            const currentValue = textareaRef.value.value || '';
            const lastIndex = currentValue.lastIndexOf(activeKanaToken.value);
            if (lastIndex === -1) {
                return;
            }

            const nextValue = `${currentValue.slice(0, lastIndex)}${suggestion}${currentValue.slice(lastIndex + activeKanaToken.value.length)}`;
            textareaRef.value.value = nextValue;
            emit('update:modelValue', nextValue);
            activeKanaToken.value = '';
            textareaRef.value.focus();
        };

        const convertFirstSuggestion = () => {
            if (kanjiSuggestions.value.length === 0) {
                return;
            }

            applyKanjiSuggestion(kanjiSuggestions.value[0]);
        };

        onMounted(() => {
            bindMode();
        });

        onBeforeUnmount(() => {
            if (onlineFetchTimeout) {
                clearTimeout(onlineFetchTimeout);
            }

            if (textareaRef.value && hasBinding.value) {
                wanakana.unbind(textareaRef.value);
                hasBinding.value = false;
            }
        });

        watch(inputMode, (newValue) => {
            bindMode();
            emit('mode-change', newValue);
        });

        watch(activeKanaToken, () => {
            onlineSuggestions.value = [];

            if (onlineFetchTimeout) {
                clearTimeout(onlineFetchTimeout);
            }

            if (inputMode.value !== 'kanji' || !activeKanaToken.value || activeKanaToken.value.length < 2) {
                return;
            }

            onlineFetchTimeout = setTimeout(() => {
                fetchOnlineSuggestions();
            }, 500);
        });

        watch(() => props.defaultMode, (newValue) => {
            inputMode.value = newValue || 'normal';
        });

        watch(() => props.modelValue, (newValue) => {
            if (textareaRef.value && textareaRef.value.value !== newValue) {
                textareaRef.value.value = newValue || '';
            }
            updateKanjiSuggestions(newValue || '');
        });

        return {
            textareaRef,
            inputMode,
            fieldId,
            currentModeLabel,
            activeKanaToken,
            kanjiSuggestions,
            localSuggestions,
            onlineSuggestions,
            isLoadingOnline,
            handleInput,
            applyKanjiSuggestion,
            convertFirstSuggestion
        };
    }
};
</script>

<style scoped>
.transliterating-textarea textarea {
    resize: vertical;
}

.kanji-panel {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 0.75rem;
    background-color: #f8f9fa;
}
</style>
