<template>
    <Head>
        <title>Laporan Nilai Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <h5><i class="fa fa-filter"></i> Filter Nilai Ujian</h5>
                        <hr>
                        <form @submit.prevent="filter">
                            
                            <div class="row">
                                <div class="col-md-9">
                                    <label class="control-label" for="name">Ujian</label>
                                    <select class="form-select" v-model="form.exam_id">
                                        <option value="">-- Pilih Ujian --</option>
                                        <option v-for="(exam, index) in exams" :key="index" :value="exam.id">{{ exam.title }} — Kelas : {{ exam.classroom.title }} — Pelajaran : {{ exam.lesson.title }}</option>
                                    </select>
                                    <div v-if="errors.exam_id" class="alert alert-danger mt-2">
                                        {{ errors.exam_id }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-white">*</label>
                                    <button type="submit" class="btn btn-md btn-primary border-0 shadow w-100" :disabled="loading"> 
                                        <i class="fa fa-filter"></i> 
                                        <span v-if="loading">Loading...</span>
                                        <span v-else>Filter</span>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div v-if="grades.length > 0" class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9 col-12">
                                <h5 class="mt-2"><i class="fa fa-chart-line"></i> Laporan Nilai Ujian</h5>
                            </div>
                            <div class="col-md-3 col-12">
                                <a :href="`/admin/reports/export?exam_id=${form.exam_id}`" target="_blank" class="btn btn-success btn-md border-0 shadow w-100 text-white"><i class="fa fa-file-excel"></i> DOWNLOAD EXCEL</a>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th class="border-0 rounded-start" style="width:5%">No.</th>
                                        <th class="border-0">Ujian</th>
                                        <th class="border-0">Sesi</th>
                                        <th class="border-0">Nama Siswa</th>
                                        <th class="border-0">Kelas</th>
                                        <th class="border-0">Pelajaran</th>
                                        <th class="border-0">Nilai</th>
                                    </tr>
                                </thead>
                                <div class="mt-2"></div>
                                <tbody>
                                    <tr v-for="(grade, index) in grades" :key="grade.id">
                                        <td class="fw-bold text-center">
                                            {{ index + 1 }}
                                        </td>
                                        <td>{{ grade.exam.title }}</td>
                                        <td>{{ grade.exam_session.title }}</td>
                                        <td>{{ grade.student.name }}</td>
                                        <td class="text-center">{{ grade.exam.classroom.title }}</td>
                                        <td>{{ grade.exam.lesson.title }}</td>
                                        <td class="fw-bold text-center">{{ grade.grade }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Alert ketika tidak ada data -->
                <div v-if="showNoDataAlert" class="card border-0 shadow">
                    <div class="card-body text-center">
                        <div class="alert alert-warning" role="alert">
                            <i class="fa fa-exclamation-triangle fa-2x mb-3"></i>
                            <h5>Tidak Ada Data Nilai</h5>
                            <p class="mb-0">Ujian belum dilakukan atau belum ada siswa yang mengerjakan ujian ini.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    //import layout Admin
    import LayoutAdmin from '../../../Layouts/Admin.vue';

    //import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    //import reactive from vue
    import { reactive, ref, watch } from 'vue';

    //import inerita adapter
    import { Inertia } from '@inertiajs/inertia';

    //import sweetalert2
    import Swal from 'sweetalert2';

    export default {

        //layout
        layout: LayoutAdmin,

        //register components
        components: {
            Head,
        },

        //props
        props: {
            errors: Object,
            exams: Array,
            grades: Array,
            showAlert: {
                type: Boolean,
                default: false
            },
            alertMessage: {
                type: String,
                default: ''
            }
        },

        //inisialisasi composition API
        setup(props) {

            //define state
            const form = reactive({
                'exam_id': '' || (new URL(document.location)).searchParams.get('exam_id'),
            });

            const loading = ref(false);
            const showNoDataAlert = ref(false);

            //watch untuk menampilkan alert dari server
            watch(() => props.showAlert, (newVal) => {
                if (newVal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ujian Belum Dilakukan',
                        text: props.alertMessage || 'Ujian belum dilakukan atau belum ada siswa yang mengerjakan ujian ini.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    });
                }
            }, { immediate: true });

            //watch untuk menampilkan alert local
            watch(() => props.grades, (newGrades) => {
                if (form.exam_id && newGrades.length === 0) {
                    showNoDataAlert.value = true;
                } else {
                    showNoDataAlert.value = false;
                }
            });

             //define methods filter
            const filter = () => {

                if (!form.exam_id) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pilih Ujian',
                        text: 'Silakan pilih ujian terlebih dahulu.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                loading.value = true;

                //HTTP request
                Inertia.get('/admin/reports/filter', {
                    //send data to server
                    exam_id: form.exam_id,
                }, {
                    onFinish: () => {
                        loading.value = false;
                    },
                    onError: (errors) => {
                        loading.value = false;
                        if (errors.exam_id) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errors.exam_id,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#3085d6'
                            });
                        }
                    }
                });

            }

            //return
            return {
                form,
                filter,
                loading,
                showNoDataAlert
            }

        }

    }

</script>

<style>

</style>