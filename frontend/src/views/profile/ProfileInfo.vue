<template>
    <div>
        <Profile>
            <div class="flex">
                <div>
                    <p class="font-semibold mb-5">Name:</p>
                    <p class="font-semibold mb-5">Email:</p>
                    <p class="font-semibold mb-5">Phone:</p>
                    <p class="font-semibold mb-5">Address:</p>
                    <p class="font-semibold">Date Of Birth:</p>
                </div>
                <div class="ms-16">
                    <p class="mb-5">{{ authUser.name }}</p>
                    <p class="mb-5">{{ authUser.email }}</p>
                    <p class="mb-5" v-if="authUser.phone">{{ authUser.phone }}</p>
                    <p class="mb-5" v-else>not add yet</p>
                    <p class="mb-5" v-if="authUser.address">{{ authUser.address }}</p>
                    <p class="mb-5" v-else>not add yet</p>
                    <p class="mb-5" v-if="authUser.date_of_birth">{{ authUser.date_of_birth }}</p>
                    <p class="mb-5" v-else>not add yet</p>
                </div>
            </div>
            <div>
                <button @click="logout"
                    class="btn w-[100%] text-red-600 bg-gray-200 hover:cursor-pointer hover:bg-gray-300">Logout</button>
                <button @click="deleteAccound"
                    class="btn w-[100%] mt-2 bg-red-600 text-gray-100 hover:cursor-pointer hover:bg-red-700">Delete Your
                    Accound</button>
            </div>
        </Profile>
    </div>
</template>

<script>
import Profile from '@/components/Profile.vue';
import { mapGetters } from 'vuex';
import Swal from 'sweetalert2';
import axios from 'axios';

export default {
    components: { Profile },
    computed: {
        ...mapGetters(['authUser']),
    },
    methods: {
        logout() {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-danger m-2 text-gray-50",
                    cancelButton: "btn btn-success text-gray-50"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Are you sure, you want to logout?",
                // text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Logout",
                cancelButtonText: "No, cancel!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$axios.post('logout');
                    localStorage.removeItem('token');
                    localStorage.removeItem('id');
                    this.$router.push('/login');
                }
            });
        },
        async deleteAccound() {
            const { value: password } = await Swal.fire({
                title: "Enter your password",
                input: "password",
                inputLabel: "Password",
                inputPlaceholder: "Enter your password",
                customClass: {
                    input: "text-center",
                },
                inputAttributes: {
                    maxlength: "20",
                    autocapitalize: "off",
                    autocorrect: "off",
                }
            });
            if (password) {
                axios.post('password-check', { 'password': password })
                    .then(response => {
                        if (response.data.password) {
                            const swalWithBootstrapButtons = Swal.mixin({
                                customClass: {
                                    confirmButton: "btn btn-danger m-2 text-gray-50",
                                    cancelButton: "btn btn-success text-gray-50"
                                },
                                buttonsStyling: false
                            });
                            swalWithBootstrapButtons.fire({
                                text: "Are you sure, you want to delete your accound?",
                                // text: "You won't be able to revert this!",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Delete",
                                cancelButtonText: "No, cancel!",
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    axios.delete('delete-accound')
                                        .then(response => {
                                            if (response.status == 200) {
                                                // this.$axios.post('logout');
                                                localStorage.removeItem('token');
                                                localStorage.removeItem('id');
                                                this.$router.push('/login');
                                            }
                                        });
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Wroung password!'
                            });
                        }
                    });
            }
        }
    },
}
</script>

<style lang="scss" scoped></style>