<template>
    <Head>
        <title>Hasil Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <!-- Result Card -->
                <div class="card border-0 shadow">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-check-circle text-success me-2 fa-2x"></i>
                            <h4 class="mb-0">Ujian Selesai</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Student Information -->
                        <div class="table-responsive mb-4">
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
                                        <td class="fw-bold bg-light">Materi</td>
                                        <td>{{ exam_group.exam.lesson.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Mulai Mengerjakan</td>
                                        <td>{{ formatDateTime(grade.start_time) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold bg-light">Selesai Mengerjakan</td>
                                        <td>{{ formatDateTime(grade.end_time) }}</td>
                                    </tr>
                                    <tr v-if="exam_group.exam.show_answer == 'Y'">
                                        <td class="fw-bold bg-light">Jumlah Benar</td>
                                        <td>{{ grade.total_correct }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Final Score -->
                        <div v-if="exam_group.exam.show_answer == 'Y'" class="text-center">
                            <div class="score-circle">
                                <div class="score-inner">
                                    <h1>{{ grade.grade }}</h1>
                                    <p>NILAI</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Back to Dashboard Button -->
                        <div class="mt-4 text-center">
                            <Link href="/student/dashboard" class="btn btn-primary btn-lg border-0 shadow">
                                <i class="fa fa-home me-2"></i> Kembali ke Dashboard
                            </Link>
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
    
    methods: {
        formatDateTime(dateTimeStr) {
            if (!dateTimeStr) return '-';
            
            const date = new Date(dateTimeStr);
            return new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }).format(date);
        }
    }
}
</script>

<style>
.score-circle {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: #f8f9fa;
    border: 10px solid #28a745;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.score-inner {
    text-align: center;
}

.score-inner h1 {
    font-size: 2.5rem;
    font-weight: bold;
    margin: 0;
    color: #28a745;
}

.score-inner p {
    margin: 0;
    font-weight: bold;
    color: #6c757d;
}
</style>