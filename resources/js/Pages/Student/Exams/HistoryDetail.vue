<template>

    <Head>
        <title>Detail History Ujian - Aplikasi Ujian Online</title>
    </Head>

    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fa fa-file-alt me-2"></i> Detail History Ujian
                        </h4>
                        <p class="text-muted mb-0">{{ exam_group.exam.title }} - {{ exam_group.exam.lesson.title }}</p>
                    </div>
                    <Link href="/student/exam-history" class="btn btn-md btn-primary border-0 shadow">
                    <i class="fa fa-long-arrow-alt-left me-2"></i> Kembali
                    </Link>
                </div>
            </div>
        </div>

        <!-- Exam Summary -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="fa fa-info-circle me-2"></i> Ringkasan Ujian
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%" class="fw-bold">Nama Siswa</td>
                                            <td>{{ exam_group.student.name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Kelas</td>
                                            <td>{{ exam_group.student.classroom.title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Ujian</td>
                                            <td>{{ exam_group.exam.title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Materi</td>
                                            <td>{{ exam_group.exam.lesson.title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Sesi</td>
                                            <td>{{ exam_group.exam_session.title }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%" class="fw-bold">Waktu Mulai</td>
                                            <td>{{ formatDateTime(grade.start_time) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Waktu Selesai</td>
                                            <td>{{ formatDateTime(grade.end_time) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Durasi Pengerjaan</td>
                                            <td>{{ getDuration() }} menit</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nilai Akhir</td>
                                            <td>
                                                <span :class="getGradeClass(grade.grade, exam_group.exam.lesson.kkm)"
                                                    class="fw-bold fs-5">
                                                    {{ grade.grade }}
                                                </span>
                                                <span v-if="grade.grade >= exam_group.exam.lesson.kkm"
                                                    class="badge bg-success ms-2">
                                                    <i class="fa fa-check"></i> Lulus
                                                </span>
                                                <span v-else class="badge bg-danger ms-2">
                                                    <i class="fa fa-times"></i> Tidak Lulus
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">KKM</td>
                                            <td>{{ exam_group.exam.lesson.kkm }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Score Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ answers.length }}</h3>
                        <p class="mb-0">Total Soal</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ grade.total_correct }}</h3>
                        <p class="mb-0">Benar</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ grade.total_partial || 0 }}</h3>
                        <p class="mb-0">Sebagian Benar</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-1">{{ answers.length - grade.total_correct - (grade.total_partial || 0) }}</h3>
                        <p class="mb-0">Salah</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Questions Detail -->
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="fa fa-question-circle me-2"></i> Detail Jawaban
                        </h5>
                    </div>
                    <div class="card-body">
                        <div v-for="(answer, index) in answers" :key="answer.id" class="mb-4">
                            <div class="card border-left-primary">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <span class="badge bg-secondary me-2">{{ index + 1 }}</span>
                                        <span class="badge me-2"
                                            :class="getQuestionTypeBadge(answer.question.question_type)">
                                            {{ getQuestionTypeLabel(answer.question.question_type) }}
                                        </span>
                                    </h6>
                                    <div>
                                        <span class="badge me-2" :class="getAnswerStatusBadge(answer.is_correct)">
                                            <i :class="getAnswerStatusIcon(answer.is_correct)"></i>
                                            {{ getAnswerStatusLabel(answer.is_correct) }}
                                        </span>
                                        <span class="badge bg-info">Skor: {{ answer.score }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Question -->
                                    <div class="mb-3">
                                        <h6 class="fw-bold mb-2">Pertanyaan:</h6>
                                        <div v-html="answer.question.question" class="question-content"></div>

                                        <!-- Question Media -->
                                        <div v-if="answer.question.question_image" class="mt-2">
                                            <img :src="`/storage/questions/${answer.question.question_image}`"
                                                class="img-fluid rounded" style="max-width: 300px;">
                                        </div>
                                        <div v-if="answer.question.audio_file" class="mt-2">
                                            <audio controls class="w-100">
                                                <source :src="`/storage/questions/${answer.question.audio_file}`"
                                                    type="audio/mpeg">
                                                Browser Anda tidak mendukung audio.
                                            </audio>
                                        </div>
                                    </div>

                                    <!-- Answer based on question type -->
                                    <div v-if="answer.question.question_type === 'single'">
                                        <h6 class="fw-bold mb-2">Pilihan Jawaban:</h6>
                                        <div class="row">
                                            <div v-for="(option, optionIndex) in getOptions(answer.question)"
                                                :key="optionIndex" class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input type="radio" :id="`option_${answer.id}_${optionIndex}`"
                                                        :name="`question_${answer.id}`" :value="optionIndex + 1"
                                                        :checked="answer.answer == optionIndex + 1" disabled
                                                        class="form-check-input">
                                                    <label :for="`option_${answer.id}_${optionIndex}`"
                                                        class="form-check-label"
                                                        :class="getOptionClass(answer, optionIndex + 1)">
                                                        {{ getOptionText(answer.question, optionIndex + 1) }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Jawaban yang dipilih:</strong>
                                                <span
                                                    :class="answer.is_correct === 'Y' ? 'text-success' : 'text-danger'">
                                                    {{ getOptionText(answer.question, answer.answer) }}
                                                </span>
                                            </small>
                                            <br>
                                            <small class="text-success">
                                                <strong>Jawaban yang benar:</strong> {{ getOptionText(answer.question,
                                                answer.question.answer) }}
                                            </small>
                                        </div>
                                    </div>

                                    <div v-else-if="answer.question.question_type === 'multiple'">
                                        <h6 class="fw-bold mb-2">Pilihan Jawaban (Pilih semua yang benar):</h6>
                                        <div class="row">
                                            <div v-for="(option, optionIndex) in getOptions(answer.question)"
                                                :key="optionIndex" class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" :id="`option_${answer.id}_${optionIndex}`"
                                                        :value="optionIndex + 1"
                                                        :checked="answer.selected_answers_decoded.includes(optionIndex + 1)"
                                                        disabled class="form-check-input">
                                                    <label :for="`option_${answer.id}_${optionIndex}`"
                                                        class="form-check-label"
                                                        :class="getMultipleOptionClass(answer, optionIndex + 1)">
                                                        {{ getOptionText(answer.question, optionIndex + 1) }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Jawaban yang dipilih:</strong>
                                                <span v-if="answer.selected_answers_decoded.length > 0">
                                                    {{answer.selected_answers_decoded.map(opt =>
                                                    getOptionText(answer.question, opt)).join(', ') }}
                                                </span>
                                                <span v-else class="text-muted">Tidak ada pilihan</span>
                                            </small>
                                            <br>
                                            <small class="text-success">
                                                <strong>Jawaban yang benar:</strong>
                                                {{answer.correct_answers.map(opt => getOptionText(answer.question,
                                                opt)).join(', ') }}
                                            </small>
                                        </div>
                                    </div>

                                    <div v-else-if="answer.question.question_type === 'essay'">
                                        <h6 class="fw-bold mb-2">Jawaban Siswa:</h6>
                                        <div class="card bg-light border-0 mb-3">
                                            <div class="card-body">
                                                <p class="mb-0">{{ answer.essay_answer || 'Tidak ada jawaban' }}</p>
                                            </div>
                                        </div>

                                        <h6 class="fw-bold mb-2">Jawaban yang Diharapkan:</h6>
                                        <div class="card bg-success bg-opacity-10 border-success border-opacity-25">
                                            <div class="card-body">
                                                <p class="mb-0">{{ answer.question.essay_answer }}</p>
                                            </div>
                                        </div>

                                        <div v-if="answer.similarity_score" class="mt-2">
                                            <small class="text-muted">
                                                <strong>Tingkat Kemiripan:</strong> {{
                                                Math.round(answer.similarity_score * 100) }}%
                                            </small>
                                        </div>
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
        exam_group: Object,
        grade: Object,
        answers: Array
    },

    methods: {
        formatDateTime(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        getDuration() {
            if (!this.grade.start_time || !this.grade.end_time) return '-';
            const start = new Date(this.grade.start_time);
            const end = new Date(this.grade.end_time);
            const diffInMinutes = Math.floor((end - start) / (1000 * 60));
            return diffInMinutes;
        },

        getGradeClass(grade, kkm) {
            if (grade >= kkm) {
                return 'text-success';
            } else if (grade >= kkm * 0.75) {
                return 'text-warning';
            } else {
                return 'text-danger';
            }
        },

        getQuestionTypeBadge(type) {
            const badges = {
                'single': 'bg-primary',
                'multiple': 'bg-info',
                'essay': 'bg-secondary'
            };
            return badges[type] || 'bg-secondary';
        },

        getQuestionTypeLabel(type) {
            const labels = {
                'single': 'Pilihan Tunggal',
                'multiple': 'Pilihan Ganda',
                'essay': 'Essay'
            };
            return labels[type] || 'Unknown';
        },

        getAnswerStatusBadge(status) {
            const badges = {
                'Y': 'bg-success',
                'P': 'bg-warning',
                'N': 'bg-danger'
            };
            return badges[status] || 'bg-secondary';
        },

        getAnswerStatusIcon(status) {
            const icons = {
                'Y': 'fa fa-check',
                'P': 'fa fa-minus',
                'N': 'fa fa-times'
            };
            return icons[status] || 'fa fa-question';
        },

        getAnswerStatusLabel(status) {
            const labels = {
                'Y': 'Benar',
                'P': 'Sebagian Benar',
                'N': 'Salah'
            };
            return labels[status] || 'Unknown';
        },

        getOptions(question) {
            const options = [];
            if (question.option_1) options.push(question.option_1);
            if (question.option_2) options.push(question.option_2);
            if (question.option_3) options.push(question.option_3);
            if (question.option_4) options.push(question.option_4);
            if (question.option_5) options.push(question.option_5);
            return options;
        },

        getOptionText(question, optionNumber) {
            const optionKey = `option_${optionNumber}`;
            return question[optionKey] || '';
        },

        getOptionClass(answer, optionNumber) {
            const isSelected = answer.answer == optionNumber;
            const isCorrect = answer.question.answer == optionNumber;

            if (isSelected && isCorrect) {
                return 'text-success fw-bold';
            } else if (isSelected && !isCorrect) {
                return 'text-danger fw-bold';
            } else if (!isSelected && isCorrect) {
                return 'text-success';
            }
            return '';
        },

        getMultipleOptionClass(answer, optionNumber) {
            const isSelected = answer.selected_answers_decoded.includes(optionNumber);
            const isCorrect = answer.correct_answers.includes(optionNumber);

            if (isSelected && isCorrect) {
                return 'text-success fw-bold';
            } else if (isSelected && !isCorrect) {
                return 'text-danger fw-bold';
            } else if (!isSelected && isCorrect) {
                return 'text-success';
            }
            return '';
        }
    }
}
</script>

<style scoped>
.question-content {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.card.border-left-primary {
    border-left: 4px solid #007bff !important;
}

.form-check-label {
    cursor: default;
}

.form-check-input:disabled {
    opacity: 0.7;
}

.badge {
    font-size: 0.75em;
}

.card-header {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6;
}
</style>