<template>
    <Head>
        <title>Konfirmasi Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid">
        <!-- Back Button -->
        <div class="row mb-3">
            <div class="col-md-12">
                <Link href="/student/dashboard" class="btn btn-md btn-primary border-0 shadow" type="button">
                    <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
                </Link>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row">
            <!-- Exam Description -->
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="card border-0 shadow h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"> <i class="fa fa-file me-2"></i> Deskripsi Ujian</h5>
                    </div>
                    <div class="card-body">
                        <div v-html="exam_group.exam.description" class="exam-description"></div>
                    </div>
                </div>
            </div>
            
            <!-- Student Details -->
            <div class="col-md-6">
                <div class="card border-0 shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"> <i class="fa fa-list-ul me-2"></i> Detail Peserta</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <td width="30%" class="fw-bold bg-light">NISN</td>
                                        <td>{{ exam_group.student.nisn }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Nama Lengkap</td>
                                        <td>{{ exam_group.student.name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Kelas</td>
                                        <td>{{ exam_group.student.classroom.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Ujian</td>
                                        <td>{{ exam_group.exam.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Mata Pelajaran</td>
                                        <td>{{ exam_group.exam.lesson.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Durasi</td>
                                        <td>{{ exam_group.exam.duration }} Menit</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Start Button -->
                        <div v-if="grade.end_time == null" class="mt-3">
                            <Link :href="`/student/exam-start/${exam_group.id}`" 
                                  class="btn btn-lg btn-success border-0 shadow w-100 text-white">
                                <i class="fa fa-play-circle me-2"></i> Mulai Ujian
                            </Link>
                        </div>
                        <div v-else class="mt-3">
                            <button class="btn btn-lg btn-secondary border-0 shadow w-100" disabled>
                                <i class="fa fa-check-circle me-2"></i> Sudah Mengerjakan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//import layout student
import LayoutStudent from '../../../Layouts/Student.vue';

//import Head and Link from Inertia
import {
    Head,
    Link
} from '@inertiajs/inertia-vue3';

export default {
    //layout
    layout: LayoutStudent,

    //register components
    components: {
        Head,
        Link
    },

    //props
    props: {
        exam_group: Object,
        grade: Object
    },
}
</script>

<style>
.exam-description {
    min-height: 200px;
}
</style>