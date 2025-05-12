<template>
    <Head>
        <title>Registrasi Siswa - Aplikasi Ujian Online</title>
    </Head>
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100">
                <h2 class="text-center mb-4">Registrasi Siswa</h2>
                <form @submit.prevent="submit">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nomor Test (NIK)</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    v-model="form.nisn" 
                                    placeholder="Masukkan Nomor Test"
                                >
                                <div v-if="errors.nisn" class="text-danger mt-2">
                                    {{ errors.nisn }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Lengkap</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    v-model="form.name" 
                                    placeholder="Masukkan Nama Lengkap"
                                >
                                <div v-if="errors.name" class="text-danger mt-2">
                                    {{ errors.name }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Email (Opsional)</label>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    v-model="form.email" 
                                    placeholder="Masukkan Email"
                                >
                                <div v-if="errors.email" class="text-danger mt-2">
                                    {{ errors.email }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nomor Telepon</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    v-model="form.phone" 
                                    placeholder="Masukkan Nomor Telepon"
                                >
                                <div v-if="errors.phone" class="text-danger mt-2">
                                    {{ errors.phone }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Kelas</label>
                                <select 
                                    class="form-select" 
                                    v-model="form.classroom_id"
                                >
                                    <option value="" disabled>Pilih Kelas</option>
                                    <option 
                                        v-for="classroom in classrooms" 
                                        :key="classroom.id" 
                                        :value="classroom.id"
                                    >
                                        {{ classroom.title }}
                                    </option>
                                </select>
                                <div v-if="errors.classroom_id" class="text-danger mt-2">
                                    {{ errors.classroom_id }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Jenis Kelamin</label>
                                <select 
                                    class="form-select" 
                                    v-model="form.gender"
                                >
                                    <option value="" disabled>Pilih Jenis Kelamin</option>
                                    <option value="L">Laki - Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <div v-if="errors.gender" class="text-danger mt-2">
                                    {{ errors.gender }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Password</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    v-model="form.password" 
                                    placeholder="Masukkan Password"
                                >
                                <div v-if="errors.password" class="text-danger mt-2">
                                    {{ errors.password }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Konfirmasi Password</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    v-model="form.password_confirmation" 
                                    placeholder="Konfirmasi Password"
                                >
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </div>

                    <div class="text-center mt-3">
                        <p>Sudah punya akun? <Link href="/" class="text-primary">Login</Link></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    //import layout
    import LayoutStudent from '../../../Layouts/Student.vue';

    //import Head and Link from Inertia
    import {
        Head,
        Link
    } from '@inertiajs/inertia-vue3';

    //import reactive from vue
    import { reactive } from 'vue';

    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

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
            errors: Object,
            classrooms: Array,
        },

        //inisialisasi composition API
        setup() {
            //define form with reactive
            const form = reactive({
                nisn: '',
                name: '',
                email: '',
                phone: '',
                classroom_id: '',
                gender: '',
                password: '',
                password_confirmation: ''
            });

            //method "submit"
            const submit = () => {
                //send data to server
                Inertia.post('/register', {
                    //data
                    nisn: form.nisn,
                    name: form.name,
                    email: form.email,
                    phone: form.phone,
                    classroom_id: form.classroom_id,
                    gender: form.gender,
                    password: form.password,
                    password_confirmation: form.password_confirmation
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Registrasi Berhasil!',
                            text: 'Anda berhasil mendaftar dan login.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                });
            }

            //return
            return {
                form,
                submit,
            };
        }
    }
</script>

<style scoped>
</style>