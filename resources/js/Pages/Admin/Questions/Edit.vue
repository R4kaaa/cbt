<template>
    <Head>
        <title>Edit Soal Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <Link :href="`/admin/exams/${exam.id}`" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-question-circle"></i> Edit Soal Ujian</h5>
                        <hr>
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipe Soal</label>
                                <div class="d-flex">
                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" v-model="form.question_type" value="single" id="single">
                                        <label class="form-check-label" for="single">
                                            Jawaban Tunggal
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" v-model="form.question_type" value="multiple" id="multiple">
                                        <label class="form-check-label" for="multiple">
                                            Jawaban Ganda
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
                                        <input class="form-check-input" type="radio" v-model="form.media_type" value="none" id="none">
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
                                <div v-else-if="currentImage" class="mt-3">
                                    <p class="mb-2">Gambar saat ini:</p>
                                    <img :src="'/storage/questions/' + currentImage" class="img-fluid" style="max-height: 200px;" />
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
                                <div v-else-if="currentAudio" class="mt-3">
                                    <p class="mb-2">Audio saat ini:</p>
                                    <audio controls :src="'/storage/questions/' + currentAudio"></audio>
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
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
                                                    }"
                                                />
                                                <div v-if="errors.question" class="mt-2 text-danger">
                                                    {{ errors.question }}
                                                </div>
                                            </td>
                                        </tr>
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
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
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
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
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
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
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
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
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
                                                        toolbar: 'styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image emoticons'
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
    //import layout
    import LayoutAdmin from '../../../Layouts/Admin.vue';

    //import Heade and Link from Inertia
    import {
        Head,
        Link
    } from '@inertiajs/inertia-vue3';

    //import reactive from vue
    import { reactive, ref } from 'vue';

    //import inerita adapter
    import { Inertia } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

    //import tinyMCE
    import Editor from '@tinymce/tinymce-vue';

    export default {

        //layout
        layout: LayoutAdmin,

        //register components
        components: {
            Head,
            Link,
            Editor,
        },

        //props
        props: {
            errors: Object,
            exam: Object,
            question: Object,
        },

        //inisialisasi composition API
        setup(props) {
            // Store current media for preview
            const currentImage = ref(props.question.question_image);
            const currentAudio = ref(props.question.audio_file);
            
            // For image and audio preview of newly selected files
            const imagePreview = ref(null);
            const audioPreview = ref(null);

            //define form with reactive
            const form = reactive({
                question: props.question.question,
                option_1: props.question.option_1,
                option_2: props.question.option_2,
                option_3: props.question.option_3,
                option_4: props.question.option_4,
                option_5: props.question.option_5,
                question_type: props.question.question_type || 'single',
                media_type: props.question.media_type || 'none',
                answer: props.question.answer,
                answers: Array.isArray(props.question.answers) 
                    ? props.question.answers 
                    : (props.question.answers ? props.question.answers.split(',') : []),
                question_image: null, // File object for new image upload
                audio_file: null, // File object for new audio upload
            });

            // Handle image upload
            const handleImageUpload = (e) => {
                const file = e.target.files[0];
                if (file) {
                    // Store file object in form data
                    form.question_image = file;
                    // Create preview URL
                    imagePreview.value = URL.createObjectURL(file);
                    // Reset audio file if an image is selected
                    form.audio_file = null;
                    audioPreview.value = null;
                }
            };

            // Handle audio upload
            const handleAudioUpload = (e) => {
                const file = e.target.files[0];
                if (file) {
                    // Store file object in form data
                    form.audio_file = file;
                    // Create preview URL
                    audioPreview.value = URL.createObjectURL(file);
                    // Reset image file if audio is selected
                    form.question_image = null;
                    imagePreview.value = null;
                }
            };

            // Method "submit" using FormData for file uploads
            const submit = () => {
                // Create FormData object
                const formData = new FormData();
                
                // Append basic form fields
                formData.append('question', form.question);
                formData.append('option_1', form.option_1);
                formData.append('option_2', form.option_2);
                formData.append('option_3', form.option_3);
                formData.append('option_4', form.option_4);
                formData.append('option_5', form.option_5);
                formData.append('question_type', form.question_type);
                formData.append('media_type', form.media_type);
                
                // Add answer based on question type
                if (form.question_type === 'single') {
                    formData.append('answer', form.answer);
                } else {
                    // Convert array to JSON string for multiple answers
                    form.answers.forEach(answer => {
                        formData.append('answers[]', answer);
                    });
                }
                
                // Add files if they exist
                if (form.media_type === 'image' && form.question_image) {
                    formData.append('question_image', form.question_image);
                }
                
                if (form.media_type === 'audio' && form.audio_file) {
                    formData.append('audio_file', form.audio_file);
                }
                
                // Send data to server using PUT method
                Inertia.post(`/admin/exams/${props.exam.id}/questions/${props.question.id}/update`, formData, {
                    forceFormData: true,
                    preserveScroll: true,
                    preserveState: false,
                    onSuccess: () => {
                        // Show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Soal Ujian Berhasil Diupdate!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });
            };

            //return
            return {
                form,
                currentImage,
                currentAudio,
                imagePreview,
                audioPreview,
                handleImageUpload,
                handleAudioUpload,
                submit,
            }
        }
    }
</script>

<style>
</style>