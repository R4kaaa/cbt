<template>

    <Head>
        <title>Tambah Guru - Aplikasi Ujian Online</title>
    </Head>
    <div class="container-fluid mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
                <Link href="/admin/tutors" class="btn btn-md btn-primary border-0 shadow mb-3" type="button"><i
                    class="fa fa-long-arrow-alt-left me-2"></i> Kembali</Link>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5><i class="fa fa-user-tie"></i> Tambah Guru</h5>
                        <hr>
                        <form @submit.prevent="submit">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label>NIP</label>
                                        <input type="text" class="form-control" placeholder="Masukkan NIP"
                                            v-model="form.nip">
                                        <div v-if="errors.nip" class="alert alert-danger mt-2">
                                            {{ errors.nip }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label>Nama Lengkap</label>
                                        <input type="text" class="form-control" placeholder="Masukkan Nama Guru"
                                            v-model="form.name">
                                        <div v-if="errors.name" class="alert alert-danger mt-2">
                                            {{ errors.name }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label>Email</label>
                                        <input type="email" class="form-control" placeholder="Masukkan Email Guru"
                                            v-model="form.email">
                                        <div v-if="errors.email" class="alert alert-danger mt-2">
                                            {{ errors.email }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label>Nomor Telepon</label>
                                        <input type="text" class="form-control" placeholder="Masukkan Nomor Telepon"
                                            v-model="form.phone">
                                        <div v-if="errors.phone" class="alert alert-danger mt-2">
                                            {{ errors.phone }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label>Jenis Kelamin</label>
                                        <select class="form-select" v-model="form.gender">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L">Laki - Laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        <div v-if="errors.gender" class="alert alert-danger mt-2">
                                            {{ errors.gender }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label>Password</label>
                                        <input type="password" class="form-control" placeholder="Masukkan Password"
                                            v-model="form.password">
                                        <div v-if="errors.password" class="alert alert-danger mt-2">
                                            {{ errors.password }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label>Konfirmasi Password</label>
                                        <input type="password" class="form-control"
                                            placeholder="Masukkan Konfirmasi Password"
                                            v-model="form.password_confirmation">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-md btn-primary border-0 shadow me-2">Simpan</button>
                            <button type="reset" class="btn btn-md btn-warning border-0 shadow">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LayoutAdmin from '../../../Layouts/Admin.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';

export default {
    layout: LayoutAdmin,
    components: {
        Head,
        Link
    },
    props: {
        errors: Object,
    },
    setup() {
        const form = reactive({
            nip: '',
            name: '',
            email: '',
            phone: '',
            gender: '',
            password: '',
            password_confirmation: ''
        });

        const submit = () => {
            Inertia.post('/admin/tutors', {
                nip: form.nip,
                name: form.name,
                email: form.email,
                phone: form.phone,
                gender: form.gender,
                password: form.password,
                password_confirmation: form.password_confirmation
            }, {
                onSuccess: () => {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Tutor Berhasil Disimpan.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
            });
        }

        return {
            form,
            submit,
        };
    }
}
</script>

<style></style>