<template>

    <Head>
        <title>Siswa - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-2 col-12 mb-2">
                        <Link href="/admin/students/create" class="btn btn-md btn-primary border-0 shadow w-100"
                            type="button">
                        <i class="fa fa-plus-circle"></i>
                        Tambah
                        </Link>

                    </div>
                    <div class="col-md-3 col-12 mb-2">
                        <Link href="/admin/students/import" class="btn btn-md btn-success border-0 shadow w-100"
                            type="button">
                        <i class="fa fa-file text-white"></i>
                        import
                        </Link>
                    </div>
                    <div class="col-md-4 col-12 mb-2">
                        <form @submit.prevent="handleSearch">
                            <div class="input-group">
                                <input type="text" class="form-control border-0 shadow" v-model="search"
                                    placeholder="masukkan kata kunci dan enter...">
                                <span class="input-group-text border-0 shadow">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3 col-12 mb-2">
                        <select class="form-select border-0 shadow" v-model="selectedClassroom" @change="handleFilter">
                            <option value="">Semua Kelas</option>
                            <option v-for="classroom in classrooms" :key="classroom.id" :value="classroom.id">
                                {{ classroom.title }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-dark">
                                    <tr class="border-0">
                                        <th class="border-0 rounded-start" style="width:5%">No.</th>
                                        <th class="border-0">NISN</th>
                                        <th class="border-0">Nama Lengkap</th>
                                        <th class="border-0">Email</th>
                                        <th class="border-0">Kelas</th>
                                        <th class="border-0 rounded-end" style="width:15%">Aksi</th>
                                    </tr>
                                </thead>
                                <div class="mt-2"></div>
                                <tbody>
                                    <tr v-for="(student, index) in students.data" :key="index">
                                        <td class="fw-bold text-center">{{ ++index + (students.current_page - 1) *
                                            students.per_page }}</td>
                                        <td>{{ student.nisn }}</td>
                                        <td>{{ student.name }}</td>
                                        <td>{{ student.email }}</td>
                                        <td>{{ student.classroom ? student.classroom.title : '-' }}</td>
                                        <td class="text-center">
                                            <Link :href="`/admin/students/${student.id}/edit`"
                                                class="btn btn-sm btn-info border-0 shadow me-2" type="button">
                                            <i class="fa fa-pencil-alt"></i>
                                            </Link>
                                            <button @click.prevent="destroy(student.id)"
                                                class="btn btn-sm btn-danger border-0">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination :links="students.links" align="end" />
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

//import Head and Link from Inertia
import {
    Head,
    Link
} from '@inertiajs/inertia-vue3';

//import ref from vue
import {
    ref
} from 'vue';

//import inertia adapter
import { Inertia } from '@inertiajs/inertia';

//import sweet alert2
import Swal from 'sweetalert2';

export default {
    //layout
    layout: LayoutAdmin,

    //register component
    components: {
        Head,
        Link,
        Pagination
    },

    //props
    props: {
        students: Object,
        classrooms: Array,
        filters: Object,
    },

    //inisialisasi composition API
    setup(props) {

        //define state search
        const search = ref(props.filters.q || '');

        //define state for selected classroom
        const selectedClassroom = ref(props.filters.classroom_id || '');

        //define method search
        const handleSearch = () => {
            Inertia.get('/admin/students', {
                q: search.value,
                classroom_id: selectedClassroom.value,
            });
        }

        //define method filter
        const handleFilter = () => {
            Inertia.get('/admin/students', {
                q: search.value,
                classroom_id: selectedClassroom.value,
            });
        }

        //define method destroy
        const destroy = (id) => {
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

                        Inertia.delete(`/admin/students/${id}`);

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
            search,
            selectedClassroom,
            handleSearch,
            handleFilter,
            destroy,
        }

    }
}

</script>

<style></style>