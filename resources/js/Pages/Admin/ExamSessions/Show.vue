<template>
    <Head>
        <title>Detail Sesi Ujian - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">

                <Link href="/admin/exam_sessions" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>

                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <h5> <i class="fa fa-stopwatch"></i> Detail Sesi Ujian</h5>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <tbody>
                                    <tr>
                                        <td style="width:30%" class="fw-bold">Nama Ujian</td>
                                        <td>{{ exam_session.exam.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Materi</td>
                                        <td>{{ exam_session.exam.lesson.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Kelas</td>
                                        <td>{{ exam_session.exam.classroom.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Sesi</td>
                                        <td>{{ exam_session.title }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Mulai</td>
                                        <td>{{ exam_session.start_time }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Selesai</td>
                                        <td>{{ exam_session.end_time }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>


                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5> <i class="fa fa-user-plus"></i> Enrolled Siswa</h5>
                        <hr>
                        
                        <Link :href="`/admin/exam_sessions/${exam_session.id}/enrolle/create`" class="btn btn-md btn-primary border-0 shadow me-2" type="button"><i class="fa fa-user-plus"></i> Enrolle Siswa</Link>
                        
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th class="border-0 rounded-start" style="width:5%">No.</th>
                                        <th class="border-0">Nama Siswa</th>
                                        <th class="border-0">Kelas</th>
                                        <th class="border-0">Jenis Kelamin</th>
                                        <th class="border-0 rounded-end" style="width:15%">Aksi</th>
                                    </tr>
                                </thead>
                                <div class="mt-2"></div>
                                <tbody>
                                    <tr v-for="(data, index) in exam_session.exam_groups.data" :key="index">
                                        <td class="fw-bold text-center">{{ ++index + (exam_session.exam_groups.current_page - 1) * exam_session.exam_groups.per_page }}</td>
                                        <td>{{ data.student.name }}</td>
                                        <td class="text-center">{{ data.student.classroom.title }}</td>
                                        <td class="text-center">{{ data.student.gender }}</td>
                                        <td class="text-center">
                                            <button @click.prevent="destroy(exam_session.id, data.id)" class="btn btn-sm btn-danger border-0"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination :links="exam_session.exam_groups.links" align="end" />
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
            exam_session: Object,
        },

        //inisialisasi composition API
        setup() {

            //define method destroy
            const destroy = (exam_session_id, exam_group_id) => {
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

                        Inertia.delete(`/admin/exam_sessions/${exam_session_id}/enrolle/${exam_group_id}/destroy`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Siswa Berhasil Dihapus!.',
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
