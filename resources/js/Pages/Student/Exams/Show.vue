<template>

    <Head>
        <title>Ujian: Soal No. {{ page }} - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid pb-5">
        <div class="row mb-4">
            <div class="col-md-8 mb-4 mb-md-0">
                <div class="card border-0 shadow">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Soal No. <strong class="fw-bold">{{ page }}</strong> dari {{
                                all_questions.length }}</h5>
                            <div>
                                <VueCountdown :time="duration" @progress="handleChangeDuration"
                                    @end="showModalEndTimeExam = true" v-slot="{ hours, minutes, seconds }">
                                    <span class="badge bg-info p-2 fs-6">
                                        <i class="fa fa-clock me-1"></i> {{ hours }} jam, {{ minutes }} menit, {{
                                            seconds }} detik
                                    </span>
                                </VueCountdown>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" style="min-height: 400px;">
                        <div v-if="question_active !== null">
                            <div class="mb-2">
                                <span class="badge"
                                    :class="getQuestionTypeBadgeClass(question_active.question.question_type)">
                                    {{ getQuestionTypeLabel(question_active.question.question_type) }}
                                </span>

                                <span v-if="question_active.question.media_type !== 'none'"
                                    class="badge bg-secondary ms-2">
                                    {{ question_active.question.media_type === 'image' ? 'Dengan Gambar' : 'DenganAudio' }}
                                </span>
                            </div>

                            <div class="question-text mb-4 pb-2 border-bottom">
                                <div v-if="question_active.question.media_type !== 'none'" class="question-media mb-3">
                                    <img v-if="question_active.question.media_type === 'image' && question_active.question.question_image"
                                        :src="`/storage/questions/${question_active.question.question_image}`"
                                        class="img-fluid rounded mb-3 border shadow-sm" alt="Question Image"
                                        style="max-height: 300px;" />

                                    <div v-if="question_active.question.media_type === 'audio' && question_active.question.audio_file"
                                        class="audio-player border rounded p-3 bg-light mb-3">
                                        <p class="mb-2"><i class="fa fa-music me-2"></i> File Audio Soal:</p>
                                        <audio controls class="w-100">
                                            <source :src="`/storage/questions/${question_active.question.audio_file}`"
                                                type="audio/mpeg">
                                            Browser Anda tidak mendukung pemutaran audio.
                                        </audio>
                                    </div>
                                </div>

                                <p v-html="question_active.question.question" class="fs-5"></p>

                                <div v-if="question_active.question.question_type === 'multiple'"
                                    class="alert alert-info">
                                    <i class="fa fa-info-circle me-2"></i> Pilih semua jawaban yang benar
                                </div>

                                <div v-if="question_active.question.question_type === 'essay'"
                                    class="alert alert-warning">
                                    <i class="fa fa-pencil-alt me-2"></i> Ketik jawaban essay Anda pada kotak di bawah
                                </div>
                            </div>

                            <div v-if="question_active.question.question_type === 'single'" class="answer-options">
                                <div v-for="(answer, index) in answer_order" :key="index" class="answer-option mb-3">
                                    <div class="d-flex">
                                        <div class="option-label me-3">
                                            <button :class="[
                                                'btn btn-lg fw-bold',
                                                answer == question_active.answer ? 'btn-info' : 'btn-outline-info'
                                            ]"
                                                @click.prevent="answer != question_active.answer && submitAnswer(question_active.question.exam.id, question_active.question.id, answer)">
                                                {{ options[index] }}
                                            </button>
                                        </div>
                                        <div class="option-content flex-grow-1"
                                            :class="{ 'selected': answer == question_active.answer }"
                                            @click.prevent="answer != question_active.answer && submitAnswer(question_active.question.exam.id, question_active.question.id, answer)">
                                            <img v-if="question_active.question.option_type === 'image' && question_active.question['option_' + answer]"
                                                :src="`/storage/questions/options/${question_active.question['option_' + answer]}`"
                                                class="img-fluid rounded border" :alt="'Opsi ' + options[index]"
                                                style="max-height: 200px; cursor: pointer;" />
                                            <p v-else v-html="question_active.question['option_' + answer]"
                                                class="mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else-if="question_active.question.question_type === 'multiple'"
                                class="answer-options">
                                <div v-for="(answer, index) in answer_order" :key="index" class="answer-option mb-3">
                                    <div class="d-flex">
                                        <div class="option-label me-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" :id="'option-' + answer"
                                                    :checked="isAnswerSelected(answer)"
                                                    @change="toggleMultipleAnswer(answer)"
                                                    style="width: 25px; height: 25px; cursor: pointer;">
                                                <label class="form-check-label" :for="'option-' + answer">
                                                    <span class="ms-2 fs-5 fw-bold">{{ options[index] }}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="option-content flex-grow-1"
                                            :class="{ 'selected': isAnswerSelected(answer) }"
                                            @click.prevent="toggleMultipleAnswer(answer)">
                                            <img v-if="question_active.question.option_type === 'image' && question_active.question['option_' + answer]"
                                                :src="`/storage/questions/options/${question_active.question['option_' + answer]}`"
                                                class="img-fluid rounded border" :alt="'Opsi ' + options[index]"
                                                style="max-height: 200px; cursor: pointer;" />
                                            <p v-else v-html="question_active.question['option_' + answer]"
                                                class="mb-0"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button
                                        @click.prevent="submitMultipleAnswers(question_active.question.exam.id, question_active.question.id)"
                                        class="btn btn-primary px-4">
                                        <i class="fa fa-save me-2"></i> Simpan Jawaban
                                    </button>
                                </div>
                            </div>

                            <div v-else-if="question_active.question.question_type === 'essay'"
                                class="essay-answer mt-4">
                                <div class="form-group">
                                    <label for="essay-answer" class="form-label fw-bold mb-2">
                                        <i class="fa fa-pen me-2"></i>Jawaban Essay:
                                    </label>

                                    <Editor :api-key="'f4g16s2kaw96ta82x5udni28fxmdk833fkdpwdduyrzb20gr'"
                                        v-model="essayAnswer" :init="tinymceConfig" />

                                    <div class="d-flex justify-content-end mt-2">
                                        <div class="character-count text-muted">
                                            <small>{{ essayAnswer.length }} karakter</small>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button
                                            @click.prevent="submitEssayAnswer(question_active.question.exam.id, question_active.question.id)"
                                            class="btn btn-primary px-4">
                                            <i class="fa fa-save me-2"></i> Simpan Jawaban
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="alert alert-danger border-0 shadow text-center">
                            <i class="fa fa-exclamation-triangle me-2"></i> Soal Tidak Ditemukan!
                        </div>
                    </div>

                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button v-if="page > 1" @click.prevent="prevPage" class="btn btn-secondary px-4">
                                    <i class="fa fa-arrow-left me-2"></i> Sebelumnya
                                </button>
                            </div>
                            <div>
                                <button v-if="page < all_questions.length" @click.prevent="nextPage"
                                    class="btn btn-secondary px-4">
                                    Selanjutnya <i class="fa fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Navigasi Soal</h5>
                            <div class="badge bg-success p-2">
                                <i class="fa fa-check-circle me-1"></i> {{ question_answered }} dari {{
                                    all_questions.length }} Dikerjakan
                            </div>
                        </div>
                    </div>

                    <div class="card-body question-nav" style="height: 320px; overflow-y: auto;">
                        <div class="row g-2">
                            <div v-for="(question, index) in all_questions" :key="index" class="col-2">
                                <button @click.prevent="clickQuestion(index)" :class="[
                                    'btn btn-sm w-100 question-button',
                                    index + 1 == page ? 'btn-dark' :
                                        questionIsAnswered(question) ? getQuestionTypeButtonClass(question.question.question_type) : 'btn-outline-info'
                                ]" :title="getQuestionTypeLabel(question.question.question_type)">
                                    {{ index + 1 }}
                                    <span v-if="question.question.question_type === 'essay'" class="small">
                                        <i class="fa fa-pen-alt"></i>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white py-3">
                        <button @click="showModalEndExam = true" class="btn btn-danger btn-lg w-100 border-0 shadow">
                            <i class="fa fa-flag-checkered me-2"></i> Akhiri Ujian
                        </button>
                    </div>
                </div>

                <div class="card border-0 shadow">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fa fa-info-circle me-2"></i> Informasi Ujian</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Materi:</span>
                                <span class="fw-bold">{{ exam_group.exam.lesson.title }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Total Soal:</span>
                                <span class="fw-bold">{{ all_questions.length }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Sudah Dijawab:</span>
                                <span class="fw-bold">{{ question_answered }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Belum Dijawab:</span>
                                <span class="fw-bold">{{ all_questions.length - question_answered }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="showModalEndExam" class="modal fade show" tabindex="-1" aria-hidden="true"
        style="display:block; z-index:1050;" role="dialog">
        <div class="modal-dialog modal-dialog-centered" style="z-index:1055;">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fa fa-exclamation-triangle me-2"></i> Konfirmasi Akhiri Ujian</h5>
                </div>
                <div class="modal-body py-4">
                    <p class="fs-5 mb-0">Setelah mengakhiri ujian, Anda tidak dapat kembali ke ujian ini lagi.</p>

                    <div v-if="all_questions.length - question_answered > 0" class="alert alert-warning mt-3">
                        <i class="fa fa-exclamation-circle me-2"></i> Masih ada <strong>{{ all_questions.length -
                            question_answered }}</strong> soal yang belum Anda jawab.
                    </div>

                    <p class="fw-bold mt-3 mb-0">Yakin akan mengakhiri ujian?</p>
                </div>
                <div class="modal-footer">
                    <button @click.prevent="endExam" type="button" class="btn btn-danger px-4">
                        <i class="fa fa-check me-2"></i> Ya, Akhiri Ujian
                    </button>
                    <button @click.prevent="showModalEndExam = false" type="button" class="btn btn-secondary px-4">
                        <i class="fa fa-times me-2"></i> Kembali ke Ujian
                    </button>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" style="z-index:1040;"></div>
    </div>

    <div v-if="showModalEndTimeExam" class="modal fade show" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-hidden="true" style="display:block; z-index:1050;" role="dialog">
        <div class="modal-dialog modal-dialog-centered" style="z-index:1055;">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fa fa-clock me-2"></i> Waktu Habis!</h5>
                </div>
                <div class="modal-body py-4 text-center">
                    <i class="fa fa-stopwatch fa-4x mb-3 text-warning"></i>
                    <p class="fs-5">Waktu ujian sudah berakhir!</p>
                    <p>Klik <strong class="fw-bold">Selesai</strong> untuk mengakhiri ujian.</p>
                </div>
                <div class="modal-footer">
                    <button @click.prevent="endExam" type="button" class="btn btn-primary w-100">
                        <i class="fa fa-check-circle me-2"></i> Selesai
                    </button>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" style="z-index:1040;"></div>
    </div>
</template>

<script>
import LayoutStudent from '../../../Layouts/Student.vue';
import Editor from '@tinymce/tinymce-vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { ref, onMounted, watch } from 'vue';
import VueCountdown from '@chenfengyuan/vue-countdown';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';

export default {
    layout: LayoutStudent,
    components: {
        Head,
        Link,
        VueCountdown,
        Editor
    },
    props: {
        id: Number,
        page: Number,
        exam_group: Object,
        all_questions: Array,
        question_answered: Number,
        question_active: Object,
        answer_order: Array,
        duration: Object,
    },
    setup(props) {
        let options = ["A", "B", "C", "D", "E"];

        const tinymceConfig = {
            height: 300,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'preview', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }'
        };

        const counter = ref(0);
        const duration = ref(props.duration.duration);
        const selectedAnswers = ref([]);
        const essayAnswer = ref('');

        onMounted(() => {
            if (props.question_active) {
                if (props.question_active.question.question_type === 'multiple') {
                    try {
                        const parsedAnswers = JSON.parse(props.question_active.selected_answers || '[]');
                        selectedAnswers.value = Array.isArray(parsedAnswers) ? parsedAnswers : [];
                    } catch (e) {
                        selectedAnswers.value = [];
                        console.error("Error parsing selected answers:", e);
                    }
                } else if (props.question_active.question.question_type === 'essay') {
                    essayAnswer.value = props.question_active.essay_answer || '';
                }
            }
        });

        watch(() => props.question_active, (newVal) => {
            if (newVal && newVal.question.question_type === 'essay') {
                essayAnswer.value = newVal.essay_answer || '';
            } else if (newVal && newVal.question.question_type === 'multiple') {
                try {
                    const parsedAnswers = JSON.parse(newVal.selected_answers || '[]');
                    selectedAnswers.value = Array.isArray(parsedAnswers) ? parsedAnswers : [];
                } catch (e) {
                    selectedAnswers.value = [];
                }
            }
        });

        const getQuestionTypeLabel = ((type) => {
            switch (type) {
                case 'single':
                    return 'Pilihan Tunggal';
                case 'multiple':
                    return 'Pilihan Ganda (Multiple)';
                case 'essay':
                    return 'Essay';
                default:
                    return 'Pilihan Tunggal';
            }
        });

        const getQuestionTypeBadgeClass = ((type) => {
            switch (type) {
                case 'single':
                    return 'bg-info';
                case 'multiple':
                    return 'bg-purple';
                case 'essay':
                    return 'bg-warning';
                default:
                    return 'bg-info';
            }
        });

        const getQuestionTypeButtonClass = ((type) => {
            switch (type) {
                case 'single':
                    return 'btn-info';
                case 'multiple':
                    return 'btn-purple';
                case 'essay':
                    return 'btn-warning';
                default:
                    return 'btn-info';
            }
        });

        const isAnswerSelected = ((answer) => {
            return selectedAnswers.value.includes(answer);
        });

        const toggleMultipleAnswer = ((answer) => {
            const index = selectedAnswers.value.indexOf(answer);
            if (index === -1) {
                selectedAnswers.value.push(answer);
            } else {
                selectedAnswers.value.splice(index, 1);
            }
        });

        const submitMultipleAnswers = ((exam_id, question_id) => {
            Inertia.post('/student/exam-answer', {
                exam_id: exam_id,
                exam_session_id: props.exam_group.exam_session.id,
                question_id: question_id,
                selected_answers: JSON.stringify(selectedAnswers.value),
                duration: duration.value
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Jawaban disimpan!',
                        showConfirmButton: false,
                        timer: 1000,
                        toast: true
                    });
                }
            });
        });

        const submitEssayAnswer = ((exam_id, question_id) => {
            Swal.fire({
                position: 'top-end',
                icon: 'info',
                title: 'Menyimpan jawaban...',
                showConfirmButton: false,
                toast: true
            });

            Inertia.post('/student/exam-answer', {
                exam_id: exam_id,
                exam_session_id: props.exam_group.exam_session.id,
                question_id: question_id,
                essay_answer: essayAnswer.value,
                duration: duration.value
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Jawaban essay disimpan!',
                        showConfirmButton: false,
                        timer: 1000,
                        toast: true
                    });
                },
                onError: (errors) => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Gagal menyimpan jawaban!',
                        text: Object.values(errors).flat().join(' '),
                        showConfirmButton: false,
                        timer: 3000,
                        toast: true
                    });
                }
            });
        });

        const questionIsAnswered = ((question) => {
            if (question.question.question_type === 'single') {
                return question.answer !== 0;
            } else if (question.question.question_type === 'multiple') {
                try {
                    const selected = JSON.parse(question.selected_answers || '[]');
                    return Array.isArray(selected) && selected.length > 0;
                } catch (e) {
                    return false;
                }
            } else if (question.question.question_type === 'essay') {
                return question.essay_answer && question.essay_answer.trim() !== '';
            }
            return false;
        });

        const handleChangeDuration = (() => {
            duration.value = duration.value - 1000;
            counter.value = counter.value + 1;

            if (duration.value > 0) {
                if (counter.value % 10 == 1) {
                    axios.put(`/student/exam-duration/update/${props.duration.id}`, {
                        duration: duration.value
                    })
                }
            }
        });

        const prevPage = (() => {
            axios.put(`/student/exam-duration/update/${props.duration.id}`, {
                duration: duration.value
            });
            Inertia.get(`/student/exam/${props.id}/${props.page - 1}`);
        });

        const nextPage = (() => {
            axios.put(`/student/exam-duration/update/${props.duration.id}`, {
                duration: duration.value
            });
            Inertia.get(`/student/exam/${props.id}/${props.page + 1}`);
        });

        const clickQuestion = ((index) => {
            axios.put(`/student/exam-duration/update/${props.duration.id}`, {
                duration: duration.value
            });
            Inertia.get(`/student/exam/${props.id}/${index + 1}`);
        });

        const submitAnswer = ((exam_id, question_id, answer) => {
            Inertia.post('/student/exam-answer', {
                exam_id: exam_id,
                exam_session_id: props.exam_group.exam_session.id,
                question_id: question_id,
                answer: answer,
                duration: duration.value
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Jawaban disimpan!',
                        showConfirmButton: false,
                        timer: 1000,
                        toast: true
                    });
                }
            });
        });

        const showModalEndExam = ref(false);
        const showModalEndTimeExam = ref(false);

        const endExam = (() => {
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menyimpan hasil ujian',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            Inertia.post('/student/exam-end', {
                exam_group_id: props.exam_group.id,
                exam_id: props.exam_group.exam.id,
                exam_session_id: props.exam_group.exam_session.id,
            }, {
                onSuccess: () => {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Ujian Selesai! Mengalihkan ke halaman hasil...',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            });
        });

        return {
            options,
            duration,
            handleChangeDuration,
            prevPage,
            nextPage,
            clickQuestion,
            submitAnswer,
            showModalEndExam,
            showModalEndTimeExam,
            endExam,
            selectedAnswers,
            isAnswerSelected,
            toggleMultipleAnswer,
            submitMultipleAnswers,
            questionIsAnswered,
            essayAnswer,
            submitEssayAnswer,
            getQuestionTypeLabel,
            getQuestionTypeBadgeClass,
            getQuestionTypeButtonClass,
            tinymceConfig
        }
    }
}
</script>

<style>
.bg-purple {
    background-color: #6f42c1;
    color: white;
}

.btn-purple {
    background-color: #6f42c1;
    color: white;
}

.btn-outline-purple {
    border-color: #6f42c1;
    color: #6f42c1;
}

.btn-outline-purple:hover {
    background-color: #6f42c1;
    color: white;
}

.option-content.selected {
    background-color: #e7f3ff;
    border-radius: 0.25rem;
    padding: 0.5rem;
    border-left: 4px solid #0d6efd;
}

.option-content {
    cursor: pointer;
}

.option-content img {
    transition: transform 0.2s;
}

.option-content:hover img {
    transform: scale(1.02);
}

#essay-answer:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
}

.question-button {
    font-weight: bold;
    height: 32px;
    width: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 5px;
}

.question-nav .btn-warning,
.question-nav .btn-outline-warning {
    position: relative;
}

.question-nav .small {
    position: absolute;
    bottom: -3px;
    right: -3px;
    font-size: 8px;
    background-color: #ffffff;
    border-radius: 50%;
    padding: 1px;
    height: 12px;
    width: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>