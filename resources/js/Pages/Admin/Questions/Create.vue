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
                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tipe Soal</label>
                                <div class="d-flex">
                                    <div class="form-check me-4">
                                        <input class="form-check-input" type="radio" v-model="form.question_type" value="single" id="single" checked>
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
    import { reactive } from 'vue';

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
        },

        //inisialisasi composition API
        setup(props) {

            //define form with reactive
            const form = reactive({
                question: '',
                option_1: '',
                option_2: '',
                option_3: '',
                option_4: '',
                option_5: '',
                question_type: 'single', // Default to single
                answer: '',
                answers: [],
            });

            //method "submit"
            const submit = () => {
                //send data to server
                Inertia.post(`/admin/exams/${props.exam.id}/questions/store`, {
                    //data
                    question: form.question,
                    option_1: form.option_1,
                    option_2: form.option_2,
                    option_3: form.option_3,
                    option_4: form.option_4,
                    option_5: form.option_5,
                    question_type: form.question_type,
                    answer: form.question_type === 'single' ? form.answer : null,
                    answers: form.question_type === 'multiple' ? form.answers : null,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Soal Ujian Berhasil Disimpan!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });
            }

            //return
            return {
                form,
                submit,
            };
        }
    }
</script>

<style>

</style>