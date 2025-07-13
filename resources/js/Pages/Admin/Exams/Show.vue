<template>
    <Head>
        <title>Detail Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">

                <Link href="/admin/exams" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <h5> <i class="fa fa-edit"></i> Detail Ujian</h5>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <tbody>
                                    <tr>
                                        <td style="width:30%" class="fw-bold">Nama Ujian</td>
                                        <td>{{ exam.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Materi</td>
                                        <td>{{ exam.lesson.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Kelas</td>
                                        <td>{{ exam.classroom.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Jumlah Soal</td>
                                        <td>{{ exam.questions.data.length }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Durasi (Menit)</td>
                                        <td>{{ exam.duration }} Menit</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>

                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5> <i class="fa fa-question-circle"></i> Soal Ujian</h5>
                        <hr>
                        
                        <Link :href="`/admin/exams/${exam.id}/questions/create`" class="btn btn-md btn-primary border-0 shadow me-2" type="button"><i class="fa fa-plus-circle"></i> Tambah</Link>
                        <Link :href="`/admin/exams/${exam.id}/questions/import`" class="btn btn-md btn-success border-0 shadow text-white" type="button"><i class="fa fa-file-excel"></i> Import</Link>
                        
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th class="border-0 rounded-start" style="width:5%">No.</th>
                                        <th class="border-0">Soal</th>
                                        <th class="border-0" style="width:15%">Tipe Soal</th>
                                        <th class="border-0 rounded-end" style="width:15%">Aksi</th>
                                    </tr>
                                </thead>
                                <div class="mt-2"></div>
                                <tbody>
                                    <tr v-for="(question, index) in exam.questions.data" :key="index">
                                        <td class="fw-bold text-center">{{ ++index + (exam.questions.current_page - 1) * exam.questions.per_page }}</td>
                                        <td>
                                            <!-- Question with media handling (if present) -->
                                            <div class="fw-bold" v-html="question.question"></div>
                                            
                                            <!-- Display media if available -->
                                            <div v-if="question.media_type === 'image' && question.question_image" class="mt-2 mb-2">
                                                <img :src="`/storage/questions/${question.question_image}`" class="img-fluid" style="max-height: 200px;" alt="Question Image">
                                            </div>
                                            <div v-if="question.media_type === 'audio' && question.audio_file" class="mt-2 mb-2">
                                                <audio controls>
                                                    <source :src="`/storage/questions/${question.audio_file}`" type="audio/mpeg">
                                                    Browser Anda tidak mendukung tag audio.
                                                </audio>
                                            </div>
                                            
                                            <hr>
                                            
                                            <!-- For single and multiple choice questions -->
                                            <ol v-if="question.question_type === 'single' || question.question_type === 'multiple'" type="A">
                                                <li v-if="question.option_1" v-html="question.option_1" 
                                                   :class="{ 'text-success fw-bold': question.question_type === 'single' && question.answer === 1 || (question.question_type === 'multiple' && question.answers && question.answers.includes('1')) }"></li>
                                                <li v-if="question.option_2" v-html="question.option_2" 
                                                   :class="{ 'text-success fw-bold': question.question_type === 'single' && question.answer === 2 || (question.question_type === 'multiple' && question.answers && question.answers.includes('2')) }"></li>
                                                <li v-if="question.option_3" v-html="question.option_3" 
                                                   :class="{ 'text-success fw-bold': question.question_type === 'single' && question.answer === 3 || (question.question_type === 'multiple' && question.answers && question.answers.includes('3')) }"></li>
                                                <li v-if="question.option_4" v-html="question.option_4" 
                                                   :class="{ 'text-success fw-bold': question.question_type === 'single' && question.answer === 4 || (question.question_type === 'multiple' && question.answers && question.answers.includes('4')) }"></li>
                                                <li v-if="question.option_5" v-html="question.option_5" 
                                                   :class="{ 'text-success fw-bold': question.question_type === 'single' && question.answer === 5 || (question.question_type === 'multiple' && question.answers && question.answers.includes('5')) }"></li>
                                            </ol>
                                            
                                            <!-- For essay questions -->
                                            <div v-if="question.question_type === 'essay'" class="mt-3">
                                                <div class="alert alert-info">
                                                    <strong>Kunci Jawaban Essay:</strong>
                                                    <div class="mt-2" v-html="question.essay_answer"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span v-if="question.question_type === 'single'" class="badge bg-primary">Jawaban Tunggal</span>
                                            <span v-else-if="question.question_type === 'multiple'" class="badge bg-success">Jawaban Ganda</span>
                                            <span v-else-if="question.question_type === 'essay'" class="badge bg-warning">Essay</span>
                                        </td>
                                        <td class="text-center">
                                            <Link :href="`/admin/exams/${exam.id}/questions/${question.id}/edit`" class="btn btn-sm btn-info border-0 shadow me-2"
                                                type="button"><i class="fa fa-pencil-alt"></i></Link>
                                            <button @click.prevent="destroy(exam.id, question.id)" class="btn btn-sm btn-danger border-0"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination :links="exam.questions.links" align="end" />
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    //import layout
    import LayoutAdmin from '../../../Layouts/Admin.vue';

    //import component pagination
    import Pagination from '../../../Components/Pagination.vue';

    //import Heade and Link from Inertia
    import {
        Head,
        Link
    } from '@inertiajs/inertia-vue3';

    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

    export default {

        //layout
        layout: LayoutAdmin,

        //register components
        components: {
            Head,
            Link,
            Pagination
        },

        //props
        props: {
            errors: Object,
            exam: Object,
        },

        //inisialisasi composition API
        setup() {

            //define method destroy
            const destroy = (exam_id, question_id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/exams/${exam_id}/questions/${question_id}/destroy`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Soal Ujian Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            //return
            return {
                destroy,
            }

        }

    }

</script>

<style>

</style>