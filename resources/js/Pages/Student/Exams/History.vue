<template>

    <Head>
        <title>History Pengerjaan Ujian - Aplikasi Ujian Online</title>
    </Head>

    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fa fa-history me-2"></i> History Pengerjaan Ujian
                        </h4>
                        <p class="text-muted mb-0">{{ student.name }}</p>
                    </div>
                    <Link href="/student/dashboard" class="btn btn-md btn-primary border-0 shadow">
                    <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
                    </Link>
                </div>
            </div>
        </div>

        <!-- Overall Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fa fa-file-alt fa-2x text-primary"></i>
                        </div>
                        <h3 class="mb-1">{{ overall_stats.total_exams }}</h3>
                        <p class="text-muted mb-0">Total Ujian</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fa fa-check-circle fa-2x text-success"></i>
                        </div>
                        <h3 class="mb-1">{{ overall_stats.completed_exams }}</h3>
                        <p class="text-muted mb-0">Ujian Selesai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fa fa-trophy fa-2x text-warning"></i>
                        </div>
                        <h3 class="mb-1">{{ overall_stats.passed_exams }}</h3>
                        <p class="text-muted mb-0">Lulus KKM</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fa fa-chart-line fa-2x text-info"></i>
                        </div>
                        <h3 class="mb-1">{{ Math.round(overall_stats.average_score) }}</h3>
                        <p class="text-muted mb-0">Rata-rata Nilai</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exam History List -->
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="fa fa-list me-2"></i> Riwayat Ujian
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div v-if="exam_history.length === 0" class="text-center py-5">
                            <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada riwayat ujian</p>
                        </div>
                        <div v-else class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Ujian</th>
                                        <th width="15%">Materi</th>
                                        <th width="12%">Sesi</th>
                                        <th width="10%">Tanggal</th>
                                        <th width="8%">Status</th>
                                        <th width="8%">Nilai</th>
                                        <th width="10%">Durasi</th>
                                        <th width="12%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(history, index) in exam_history" :key="history.exam_group.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>
                                            <strong>{{ history.exam_group.exam.title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ history.stats.total_questions }} soal</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ history.exam_group.exam.lesson.title
                                                }}</span>
                                            <br>
                                            <small class="text-muted">KKM: {{ history.exam_group.exam.lesson.kkm
                                                }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ history.exam_group.exam_session.title }}</strong>
                                            <br>
                                            <small class="text-muted">{{
                                                formatDate(history.exam_group.exam_session.start_time) }}</small>
                                        </td>
                                        <td>
                                            <small>{{ history.exam_date }}</small>
                                        </td>
                                        <td>
                                            <span v-if="history.status === 'completed'" class="badge bg-success">
                                                <i class="fa fa-check"></i> Selesai
                                            </span>
                                            <span v-else-if="history.status === 'in_progress'" class="badge bg-warning">
                                                <i class="fa fa-clock"></i> Berlangsung
                                            </span>
                                            <span v-else class="badge bg-secondary">
                                                <i class="fa fa-minus"></i> Belum Mulai
                                            </span>
                                        </td>
                                        <td>
                                            <div v-if="history.grade && history.grade.grade !== null">
                                                <strong
                                                    :class="getGradeClass(history.grade.grade, history.exam_group.exam.lesson.kkm)">
                                                    {{ history.grade.grade }}
                                                </strong>
                                                <br>
                                                <small v-if="history.passed" class="text-success">
                                                    <i class="fa fa-check"></i> Lulus
                                                </small>
                                                <small v-else class="text-danger">
                                                    <i class="fa fa-times"></i> Tidak Lulus
                                                </small>
                                            </div>
                                            <span v-else class="text-muted">-</span>
                                        </td>
                                        <td>
                                            <div v-if="history.duration_taken">
                                                <strong>{{ history.duration_taken }}</strong> menit
                                                <br>
                                                <small class="text-muted">
                                                    dari {{ history.exam_group.exam.duration }} menit
                                                </small>
                                            </div>
                                            <span v-else class="text-muted">-</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <Link v-if="history.status === 'completed'"
                                                    :href="`/student/exam-history-detail/${history.exam_group.id}`"
                                                    class="btn btn-sm btn-info border-0 shadow-sm">
                                                <i class="fa fa-eye"></i> Detail
                                                </Link>
                                                <Link v-if="history.status === 'completed'"
                                                    :href="`/student/exam-result/${history.exam_group.id}`"
                                                    class="btn btn-sm btn-success border-0 shadow-sm">
                                                <i class="fa fa-chart-bar"></i> Hasil
                                                </Link>
                                                <Link v-if="history.status === 'not_started'"
                                                    :href="`/student/exam-confirmation/${history.exam_group.id}`"
                                                    class="btn btn-sm btn-primary border-0 shadow-sm">
                                                <i class="fa fa-play"></i> Mulai
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="fa fa-chart-bar me-2"></i> Statistik Detail
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fa fa-bullseye me-2"></i> Nilai Tertinggi
                                        </h6>
                                        <h3 class="text-success mb-0">{{ overall_stats.highest_score }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fa fa-chart-line me-2"></i> Nilai Terendah
                                        </h6>
                                        <h3 class="text-danger mb-0">{{ overall_stats.lowest_score }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fa fa-percentage me-2"></i> Tingkat Kelulusan
                                        </h6>
                                        <h3 class="text-info mb-0">
                                            {{ overall_stats.completed_exams > 0 ?
                                                Math.round((overall_stats.passed_exams / overall_stats.completed_exams) *
                                            100) : 0 }}%
                                        </h3>
                                    </div>
                                </div>
                            </div>
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
        student: Object,
        exam_history: Array,
        overall_stats: Object
    },

    methods: {
        formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        getGradeClass(grade, kkm) {
            if (grade >= kkm) {
                return 'text-success';
            } else if (grade >= kkm * 0.75) {
                return 'text-warning';
            } else {
                return 'text-danger';
            }
        }
    }
}
</script>

<style scoped>
.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-group .btn {
    margin-right: 2px;
}

.badge {
    font-size: 0.75em;
}

.table-responsive {
    border-radius: 0.375rem;
}
</style>