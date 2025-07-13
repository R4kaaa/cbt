<template>

    <Head>
        <title>Tutor - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-3 col-12 mb-2">
                        <Link href="/admin/tutors/create" class="btn btn-md btn-primary border-0 shadow w-100"
                            type="button">
                        <i class="fa fa-plus-circle"></i>
                        Tambah
                        </Link>
                    </div>
                    <div class="col-md-5 col-12 mb-2">
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
                                        <th class="border-0">NIP</th>
                                        <th class="border-0">Nama Lengkap</th>
                                        <th class="border-0">Email</th>
                                        <th class="border-0">Telepon</th>
                                        <th class="border-0">Jenis Kelamin</th>
                                        <th class="border-0 rounded-end" style="width:15%">Aksi</th>
                                    </tr>
                                </thead>
                                <div class="mt-2"></div>
                                <tbody>
                                    <tr v-for="(tutor, index) in tutors.data" :key="index">
                                        <td class="fw-bold text-center">{{ ++index + (tutors.current_page - 1) *
                                            tutors.per_page }}</td>
                                        <td>{{ tutor.nip }}</td>
                                        <td>{{ tutor.name }}</td>
                                        <td>{{ tutor.email }}</td>
                                        <td>{{ tutor.phone }}</td>
                                        <td>{{ tutor.gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td class="text-center">
                                            <Link :href="`/admin/tutors/${tutor.id}/edit`"
                                                class="btn btn-sm btn-info border-0 shadow me-2" type="button">
                                            <i class="fa fa-pencil-alt"></i>
                                            </Link>
                                            <button @click.prevent="destroy(tutor.id)"
                                                class="btn btn-sm btn-danger border-0">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <Pagination :links="tutors.links" align="end" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import Pagination from '../../../Components/Pagination.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';

export default {
    layout: LayoutAdmin,
    components: {
        Head,
        Link,
        Pagination
    },
    props: {
        tutors: Object,
        filters: Object,
    },
    setup(props) {
        const search = ref(props.filters.q || '');

        const handleSearch = () => {
            Inertia.get('/admin/tutors', {
                q: search.value,
            });
        }

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
                        Inertia.delete(`/admin/tutors/${id}`);
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Tutor Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }
                })
        }

        return {
            search,
            handleSearch,
            destroy,
        }
    }
}
</script>

<style></style>