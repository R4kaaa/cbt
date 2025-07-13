<template>
    <Head>
        <title>Tambah Soal Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <Link :href="`/admin/exams/${exam.id}`" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-question-circle"></i> Tambah Soal Ujian</h5>
                        <hr>
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipe Soal</label>
                                <div class="d-flex">
                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" v-model="form.question_type" value="single" id="single" checked>
                                        <label class="form-check-label" for="single">
                                            Jawaban Tunggal
                                        </label>
                                    </div>
                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" v-model="form.question_type" value="multiple" id="multiple">
                                        <label class="form-check-label" for="multiple">
                                            Jawaban Ganda
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" v-model="form.question_type" value="essay" id="essay">
                                        <label class="form-check-label" for="essay">
                                            Essay
                                        </label>
                                    </div>
                                </div>
                                <div v-if="errors.question_type" class="mt-2 text-danger">
                                    {{ errors.question_type }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipe Media</label>
                                <div class="d-flex">
                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" v-model="form.media_type" value="none" id="none" checked>
                                        <label class="form-check-label" for="none">
                                            Tanpa Media
                                        </label>
                                    </div>
                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" v-model="form.media_type" value="image" id="image">
                                        <label class="form-check-label" for="image">
                                            Gambar
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" v-model="form.media_type" value="audio" id="audio">
                                        <label class="form-check-label" for="audio">
                                            Audio (Listening)
                                        </label>
                                    </div>
                                </div>
                                <div v-if="errors.media_type" class="mt-2 text-danger">
                                    {{ errors.media_type }}
                                </div>
                            </div>

                            <div v-if="form.media_type === 'image'" class="mb-3">
                                <label class="form-label fw-bold">Upload Gambar</label>
                                <input type="file" class="form-control" @input="handleImageUpload" accept="image/*" />
                                <small class="text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal: 2MB</small>
                                <div v-if="errors.question_image" class="mt-2 text-danger">
                                    {{ errors.question_image }}
                                </div>
                                <div v-if="imagePreview" class="mt-3">
                                    <img :src="imagePreview" class="img-fluid" style="max-height: 200px;" />
                                </div>
                            </div>

                            <div v-if="form.media_type === 'audio'" class="mb-3">
                                <label class="form-label fw-bold">Upload Audio (Listening)</label>
                                <input type="file" class="form-control" @input="handleAudioUpload" accept="audio/*" />
                                <small class="text-muted">Format: MP3, WAV, OGG. Maksimal: 10MB</small>
                                <div v-if="errors.audio_file" class="mt-2 text-danger">
                                    {{ errors.audio_file }}
                                </div>
                                <div v-if="audioPreview" class="mt-3">
                                    <audio controls :src="audioPreview"></audio>
                                </div>
                            </div>

                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                    <tbody>
                                        <tr>
                                            <td style="width:20%" class="fw-bold">Soal</td>
                                            <td>
                                                <Editor 
                                                    api-key="dwq3i99zdbda10alithjifi49cxh7qnk222xfozi26pdxv3o" 
                                                    v-model="form.question" 
                                                    :init="{
                                                        menubar: false,
                                                        plugins: 'lists link image emoticons',
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                        setup: setupTinyMCEJapanese
                                                    }"
                                                />
                                                <div v-if="errors.question" class="mt-2 text-danger">
                                                    {{ errors.question }}
                                                </div>
                                            </td>
                                        </tr>
                                        <template v-if="form.question_type === 'single' || form.question_type === 'multiple'">
                                            <tr>
                                                <td style="width:20%" class="fw-bold">Pilihan A</td>
                                                <td>
                                                    <Editor 
                                                        api-key="dwq3i99zdbda10alithjifi49cxh7qnk222xfozi26pdxv3o" 
                                                        v-model="form.option_1" 
                                                        :init="{
                                                            height: 130,
                                                            menubar: false,
                                                            plugins: 'lists link image emoticons',
                                                            toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                            setup: setupTinyMCEJapanese
                                                        }"
                                                    />
                                                    <div v-if="errors.option_1" class="mt-2 text-danger">
                                                        {{ errors.option_1 }}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:20%" class="fw-bold">Pilihan B</td>
                                                <td>
                                                    <Editor 
                                                        api-key="dwq3i99zdbda10alithjifi49cxh7qnk222xfozi26pdxv3o" 
                                                        v-model="form.option_2" 
                                                        :init="{
                                                            height: 130,
                                                            menubar: false,
                                                            plugins: 'lists link image emoticons',
                                                            toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                            setup: setupTinyMCEJapanese
                                                        }"
                                                    />
                                                    <div v-if="errors.option_2" class="mt-2 text-danger">
                                                        {{ errors.option_2 }}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:20%" class="fw-bold">Pilihan C</td>
                                                <td>
                                                    <Editor 
                                                        api-key="dwq3i99zdbda10alithjifi49cxh7qnk222xfozi26pdxv3o" 
                                                        v-model="form.option_3" 
                                                        :init="{
                                                            height: 130,
                                                            menubar: false,
                                                            plugins: 'lists link image emoticons',
                                                            toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                            setup: setupTinyMCEJapanese
                                                        }"
                                                    />
                                                    <div v-if="errors.option_3" class="mt-2 text-danger">
                                                        {{ errors.option_3 }}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:20%" class="fw-bold">Pilihan D</td>
                                                <td>
                                                    <Editor 
                                                        api-key="dwq3i99zdbda10alithjifi49cxh7qnk222xfozi26pdxv3o" 
                                                        v-model="form.option_4" 
                                                        :init="{
                                                            height: 130,
                                                            menubar: false,
                                                            plugins: 'lists link image emoticons',
                                                            toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                            setup: setupTinyMCEJapanese
                                                        }"
                                                    />
                                                    <div v-if="errors.option_4" class="mt-2 text-danger">
                                                        {{ errors.option_4 }}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width:20%" class="fw-bold">Pilihan E</td>
                                                <td>
                                                    <Editor 
                                                        api-key="dwq3i99zdbda10alithjifi49cxh7qnk222xfozi26pdxv3o" 
                                                        v-model="form.option_5" 
                                                        :init="{
                                                            height: 130,
                                                            menubar: false,
                                                            plugins: 'lists link image emoticons',
                                                            toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                            setup: setupTinyMCEJapanese
                                                        }"
                                                    />
                                                    <div v-if="errors.option_5" class="mt-2 text-danger">
                                                        {{ errors.option_5 }}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-if="form.question_type === 'single'">
                                                <td style="width:20%" class="fw-bold">Jawaban Benar</td>
                                                <td>
                                                    <select class="form-control" v-model="form.answer">
                                                        <option value="1">A</option>
                                                        <option value="2">B</option>
                                                        <option value="3">C</option>
                                                        <option value="4">D</option>
                                                        <option value="5">E</option>
                                                    </select>
                                                    <div v-if="errors.answer" class="mt-2 text-danger">
                                                        {{ errors.answer }}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-if="form.question_type === 'multiple'">
                                                <td style="width:20%" class="fw-bold">Jawaban Benar</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" v-model="form.answers" value="1" id="answer1">
                                                        <label class="form-check-label" for="answer1">
                                                            A
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" v-model="form.answers" value="2" id="answer2">
                                                        <label class="form-check-label" for="answer2">
                                                            B
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" v-model="form.answers" value="3" id="answer3">
                                                        <label class="form-check-label" for="answer3">
                                                            C
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" v-model="form.answers" value="4" id="answer4">
                                                        <label class="form-check-label" for="answer4">
                                                            D
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" v-model="form.answers" value="5" id="answer5">
                                                        <label class="form-check-label" for="answer5">
                                                            E
                                                        </label>
                                                    </div>
                                                    <div v-if="errors.answers" class="mt-2 text-danger">
                                                        {{ errors.answers }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr v-if="form.question_type === 'essay'">
                                            <td style="width:20%" class="fw-bold">Kunci Jawaban Essay</td>
                                            <td>
                                                <Editor 
                                                    api-key="dwq3i99zdbda10alithjifi49cxh7qnk222xfozi26pdxv3o" 
                                                    v-model="form.essay_answer" 
                                                    :init="{
                                                        height: 200,
                                                        menubar: false,
                                                        plugins: 'lists link image emoticons',
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons',
                                                        setup: setupTinyMCEJapanese
                                                    }"
                                                />
                                                <div v-if="errors.essay_answer" class="mt-2 text-danger">
                                                    {{ errors.essay_answer }}
                                                </div>
                                                <small class="text-muted">Masukkan kunci jawaban atau panduan penilaian untuk soal essay ini.</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Simpan</button>
                            <button type="reset" class="btn btn-md btn-warning border-0 shadow">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import {
    Head,
    Link
} from '@inertiajs/inertia-vue3';
import { reactive, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';
import Editor from '@tinymce/tinymce-vue';

export default {
    layout: LayoutAdmin,
    components: {
        Head,
        Link,
        Editor,
    },
    props: {
        errors: Object,
        exam: Object,
    },
    setup(props) {
        const form = reactive({
            question: '',
            option_1: '',
            option_2: '',
            option_3: '',
            option_4: '',
            option_5: '',
            question_type: 'single',
            media_type: 'none',
            answer: '1',
            answers: [],
            essay_answer: '',
            question_image: null,
            audio_file: null,
        });

        const imagePreview = ref(null);
        const audioPreview = ref(null);

        const romajiToHiragana = {
            'a': 'あ', 'i': 'い', 'u': 'う', 'e': 'え', 'o': 'お',
            'ka': 'か', 'ki': 'き', 'ku': 'く', 'ke': 'け', 'ko': 'こ',
            'ga': 'が', 'gi': 'ぎ', 'gu': 'ぐ', 'ge': 'げ', 'go': 'ご',
            'sa': 'さ', 'shi': 'し', 'su': 'す', 'se': 'せ', 'so': 'そ',
            'za': 'ざ', 'ji': 'じ', 'zu': 'ず', 'ze': 'ぜ', 'zo': 'ぞ',
            'ta': 'た', 'chi': 'ち', 'tsu': 'つ', 'te': 'て', 'to': 'と',
            'da': 'だ', 'di': 'ぢ', 'du': 'づ', 'de': 'で', 'do': 'ど',
            'na': 'な', 'ni': 'に', 'nu': 'ぬ', 'ne': 'ね', 'no': 'の',
            'ha': 'は', 'hi': 'ひ', 'hu': 'ふ', 'he': 'へ', 'ho': 'ほ',
            'ba': 'ば', 'bi': 'び', 'bu': 'ぶ', 'be': 'べ', 'bo': 'ぼ',
            'pa': 'ぱ', 'pi': 'ぴ', 'pu': 'ぷ', 'pe': 'ぺ', 'po': 'ぽ',
            'ma': 'ま', 'mi': 'み', 'mu': 'む', 'me': 'め', 'mo': 'も',
            'ya': 'や', 'yu': 'ゆ', 'yo': 'よ',
            'ra': 'ら', 'ri': 'り', 'ru': 'る', 're': 'れ', 'ro': 'ろ',
            'wa': 'わ', 'wi': 'ゐ', 'we': 'ゑ', 'wo': 'を', 'n': 'ん',
            'kya': 'きゃ', 'kyu': 'きゅ', 'kyo': 'きょ',
            'gya': 'ぎゃ', 'gyu': 'ぎゅ', 'gyo': 'ぎょ',
            'sha': 'しゃ', 'shu': 'しゅ', 'sho': 'しょ',
            'ja': 'じゃ', 'ju': 'じゅ', 'jo': 'じょ',
            'cha': 'ちゃ', 'chu': 'ちゅ', 'cho': 'ちょ',
            'nya': 'にゃ', 'nyu': 'にゅ', 'nyo': 'にょ',
            'hya': 'ひゃ', 'hyu': 'ひゅ', 'hyo': 'ひょ',
            'bya': 'びゃ', 'byu': 'びゅ', 'byo': 'びょ',
            'pya': 'ぴゃ', 'pyu': 'ぴゅ', 'pyo': 'ぴょ',
            'mya': 'みゃ', 'myu': 'みゅ', 'myo': 'みょ',
            'rya': 'りゃ', 'ryu': 'りゅ', 'ryo': 'りょ',
            'fu': 'ふ', 'si': 'し', 'ti': 'ち', 'tu': 'つ', 'zi': 'じ',
            'nn': 'ん', 'xya': 'ゃ', 'xyu': 'ゅ', 'xyo': 'ょ',
            'xa': 'ぁ', 'xi': 'ぃ', 'xu': 'ぅ', 'xe': 'ぇ', 'xo': 'ぉ',
            'xtu': 'っ', 'xtsu': 'っ', 'ltu': 'っ', 'ltsu': 'っ',
            'xwa': 'ゎ', 'xke': 'ゖ', 'xka': 'ゕ'
        };

        const convertToHiragana = (text) => {
            let result = '';
            let i = 0;
            
            while (i < text.length) {
                let matched = false;
                
                for (let len = 4; len >= 1; len--) {
                    if (i + len <= text.length) {
                        const substr = text.substring(i, i + len);
                        if (romajiToHiragana[substr]) {
                            result += romajiToHiragana[substr];
                            i += len;
                            matched = true;
                            break;
                        }
                    }
                }
                
                if (!matched) {
                    result += text[i];
                    i++;
                }
            }
            
            return result;
        };

        const setupTinyMCEJapanese = (editor) => {
            editor.on('keyup', (e) => {
                const content = editor.getContent({ format: 'text' });
                const converted = convertToHiragana(content);
                if (content !== converted) {
                    const bookmark = editor.selection.getBookmark();
                    editor.setContent(converted);
                    editor.selection.moveToBookmark(bookmark);
                }
            });
        };

        const handleImageUpload = (e) => {
            const file = e.target.files[0];
            if (file) {
                form.question_image = file;
                imagePreview.value = URL.createObjectURL(file);
                form.audio_file = null;
                audioPreview.value = null;
            }
        };

        const handleAudioUpload = (e) => {
            const file = e.target.files[0];
            if (file) {
                form.audio_file = file;
                audioPreview.value = URL.createObjectURL(file);
                form.question_image = null;
                imagePreview.value = null;
            }
        };

        const submit = () => {
            const formData = new FormData();
            
            formData.append('question', form.question);
            formData.append('question_type', form.question_type);
            formData.append('media_type', form.media_type);
            
            if (form.question_type === 'single' || form.question_type === 'multiple') {
                formData.append('option_1', form.option_1);
                formData.append('option_2', form.option_2);
                formData.append('option_3', form.option_3);
                formData.append('option_4', form.option_4);
                formData.append('option_5', form.option_5);
                
                if (form.question_type === 'single') {
                    formData.append('answer', form.answer);
                } else {
                    form.answers.forEach(answer => {
                        formData.append('answers[]', answer);
                    });
                }
            } else if (form.question_type === 'essay') {
                formData.append('essay_answer', form.essay_answer);
            }
            
            if (form.media_type === 'image' && form.question_image) {
                formData.append('question_image', form.question_image);
            }
            
            if (form.media_type === 'audio' && form.audio_file) {
                formData.append('audio_file', form.audio_file);
            }
            
            Inertia.post(`/admin/exams/${props.exam.id}/questions/store`, formData, {
                forceFormData: true,
                onSuccess: () => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Soal Ujian Berhasil Disimpan!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
            });
        };

        return {
            form,
            imagePreview,
            audioPreview,
            handleImageUpload,
            handleAudioUpload,
            setupTinyMCEJapanese,
            submit,
        };
    }
}
</script>

<style>
</style>