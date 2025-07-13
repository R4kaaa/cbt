<template>

    <Head>
        <title>Dashboard Siswa - Aplikasi Ujian Online</title>
    </Head>
    <div class="row">
        <div class="col-md-12">
            <div
                class="alert alert-success border-0 shadow d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 gap-3">
                <div>
                    <h5 class="mb-1">Selamat Datang, <strong>{{ auth.student.name }}</strong></h5>
                </div>

                <div class="d-flex flex-wrap gap-2" v-if="auth.student">
                    <Link :href="`/student/profile-index/${auth.student.id}`"
                        class="btn btn-outline-light btn-sm shadow">
                    <i class="fas fa-user me-1"></i> Profile
                    </Link>
                    <Link :href="`/student/exam-history/${auth.student.id}`"
                        class="btn btn-outline-light btn-sm shadow">
                    <i class="fas fa-history me-1"></i> History Tes
                    </Link>
                </div>
            </div>
        </div>

    </div>

    <!-- Tampilkan daftar ujian jika tidak ada ujian yang dipilih -->
    <div class="row" v-if="!selectedExam && groupedExams.length > 0">
        <div class="col-md-6" v-for="(exam, index) in groupedExams" :key="index">
            <div class="card border-0 shadow mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ exam.title }}</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="fw-bold">Materi</td>
                                <td>{{ exam.lesson.title }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Durasi</td>
                                <td>{{ exam.duration }} menit</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Total Sesi</td>
                                <td>{{ exam.sessions.length }} sesi</td>
                            </tr>
                        </table>
                    </div>
                    <button @click="selectExam(exam)" class="btn btn-md btn-primary border-0 shadow w-100 mt-2">
                        Lihat Sesi Ujian
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tampilkan sesi ujian jika ada ujian yang dipilih -->
    <div v-if="selectedExam">
        <div class="row mb-3">
            <div class="col-md-12">
                <button @click="backToExamList" class="btn btn-md btn-secondary border-0 shadow">
                    <i class="fa fa-arrow-left"></i> Kembali ke Daftar Ujian
                </button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h4 class="card-title">{{ selectedExam.title }}</h4>
                        <p class="text-muted">{{ selectedExam.lesson.title }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6" v-for="(session, index) in selectedExam.sessions" :key="index">
                <div class="card border-0 shadow mb-3">
                    <div class="card-body">
                        <h5>{{ session.exam_group.exam_session.title }}</h5>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="fw-bold">Kelas</td>
                                    <td>{{ session.exam_group.student.classroom.title }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Mulai</td>
                                    <td>{{ session.exam_group.exam_session.start_time }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Selesai</td>
                                    <td>{{ session.exam_group.exam_session.end_time }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- cek waktu selesai -->
                        <div v-if="session.grade.end_time == null">
                            <!-- cek apakah ujian sudah dimulai, tapi waktu masih ada -->
                            <div
                                v-if="examTimeRangeChecker(session.exam_group.exam_session.start_time, session.exam_group.exam_session.end_time)">
                                <div v-if="session.grade.start_time == null">
                                    <Link :href="`/student/exam-confirmation/${session.exam_group.id}`"
                                        class="btn btn-md btn-success border-0 shadow w-100 mt-2 text-white">Kerjakan
                                    </Link>
                                </div>
                                <div v-else>
                                    <Link :href="`/student/exam/${session.exam_group.id}/1`"
                                        class="btn btn-md btn-info border-0 shadow w-100 mt-2">Lanjut Kerjakan</Link>
                                </div>
                            </div>
                            <div v-else>
                                <!-- ujian belum mulai-->
                                <div v-if="examTimeStartChecker(session.exam_group.exam_session.start_time)">
                                    <button class="btn btn-md btn-gray-700 border-0 shadow w-100 mt-2" disabled>Belum
                                        Mulai</button>
                                </div>
                                <!-- ujian terlewat -->
                                <div v-if="examTimeEndChecker(session.exam_group.exam_session.end_time)">
                                    <button class="btn btn-md btn-danger border-0 shadow w-100 mt-2" disabled>Waktu
                                        Terlewat</button>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <button class="btn btn-md btn-success border-0 shadow w-100 mt-2" disabled>Sudah
                                Dikerjakan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tampilkan pesan jika tidak ada ujian -->
    <div class="row" v-if="!selectedExam && groupedExams.length === 0">
        <div class="col-md-12">
            <div class="alert alert-danger border-0 shadow">
                <i class="fa fa-info-circle"></i> Tidak ada ujian yang tersedia
            </div>
        </div>
    </div>
</template>

<script>
//import layout student
import LayoutStudent from '../../../Layouts/Student.vue';

//import Link from Inertia
import {
    Link
} from '@inertiajs/inertia-vue3';

export default {

    //layout
    layout: LayoutStudent,

    //register components
    components: {
        Link,
    },

    //register props
    props: {
        exam_groups: Array,
        auth: Object
    },

    data() {
        return {
            selectedExam: null,
            groupedExams: []
        }
    },

    mounted() {
        this.groupExamsByTitle();
    },

    methods: {
        groupExamsByTitle() {
            const examMap = {};

            this.exam_groups.forEach(item => {
                const examId = item.exam_group.exam.id;
                const examTitle = item.exam_group.exam.title;

                if (!examMap[examId]) {
                    examMap[examId] = {
                        id: examId,
                        title: examTitle,
                        lesson: item.exam_group.exam.lesson,
                        duration: item.exam_group.exam.duration,
                        sessions: []
                    };
                }

                examMap[examId].sessions.push(item);
            });

            this.groupedExams = Object.values(examMap);
        },

        selectExam(exam) {
            this.selectedExam = exam;
        },

        backToExamList() {
            this.selectedExam = null;
        },

        examTimeRangeChecker(startTime, endTime) {
            // Implementasi logika pengecekan waktu ujian
            const now = new Date();
            const start = new Date(startTime);
            const end = new Date(endTime);
            return now >= start && now <= end;
        },

        examTimeStartChecker(startTime) {
            // Implementasi logika pengecekan apakah ujian belum dimulai
            const now = new Date();
            const start = new Date(startTime);
            return now < start;
        },

        examTimeEndChecker(endTime) {
            // Implementasi logika pengecekan apakah ujian sudah berakhir
            const now = new Date();
            const end = new Date(endTime);
            return now > end;
        }
    }
}
</script>

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0;
}

.btn {
    transition: all 0.2s ease-in-out;
}
</style>