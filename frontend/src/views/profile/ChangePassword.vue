<template>
    <div>
        <Profile>
            <h1 class="text-xl font-semibold text-center mb-5">Change Password</h1>

            <!-- old password -->
            <div class="mb-3 relative">
                <input type="password" id="old_password" :class="{ 'border-red-600': old_password_error != null }"
                    placeholder="Old Password" class="form-control border" v-model="old_password">
                <!-- password show hide toggle button -->
                <div class="absolute top-2 right-2 cursor-pointer hidden">
                    <i @click="hidePass" class="fa-solid fa-eye text-gray-500"></i>
                </div>
                <div class="absolute top-2 right-2 cursor-pointer">
                    <i @click="showPass" class="fa-solid fa-eye-slash text-gray-500"></i>
                </div>
                <!-- end password show hide toggle button -->
                <small class="text-red-600">{{ old_password_error }}</small>
            </div>
            <!-- end old password -->

            <!-- new password -->
            <div class="mb-3 relative">
                <input type="password" id="new_password" :class="{ 'border-red-600': new_password_error != null }"
                    placeholder="New Password" class="form-control" v-model="new_password">
                <div class="absolute top-2 right-2 cursor-pointer hidden">
                    <i @click="hidePass" class="fa-solid fa-eye text-gray-500"></i>
                </div>
                <div class="absolute top-2 right-2 cursor-pointer">
                    <i @click="showPass" class="fa-solid fa-eye-slash text-gray-500"></i>
                </div>
                <small class="text-red-600">{{ new_password_error }}</small>
            </div>
            <!-- end new password -->

            <!-- confirm password -->
            <div class="mb-3 relative">
                <input type="password" id="confrim_password"
                    :class="{ 'border-red-600': confirm_password_error != null }" placeholder="Confirm Password"
                    class="form-control" v-model="confirm_password">
                <div class="absolute top-2 right-2 cursor-pointer hidden">
                    <i @click="hidePass" class="fa-solid fa-eye text-gray-500"></i>
                </div>
                <div class="absolute top-2 right-2 cursor-pointer">
                    <i @click="showPass" class="fa-solid fa-eye-slash text-gray-500"></i>
                </div>
                <small class="text-red-600">{{ confirm_password_error }}</small>
            </div>
            <!-- end confirm password -->

            <button class="btn bg-blue-600 hover:bg-blue-700 mt-5 mx-auto block text-gray-50"
                @click="change">Change</button>
        </Profile>
    </div>
</template>

<script>
import Profile from '@/components/Profile.vue';
import Swal from 'sweetalert2';

export default {
    components: { Profile },
    data() {
        return {
            old_password: null,
            new_password: null,
            confirm_password: null,

            old_password_error: null,
            new_password_error: null,
            confirm_password_error: null,
        }
    },
    methods: {
        change() {
            this.$Progress.start();
            this.$axios.post('change-password', {
                'old_password': this.old_password,
                'new_password': this.new_password,
                'confirm_password': this.confirm_password
            }).then(response => {
                // validation fail message response
                if (response.data.status == 403) {
                    this.$Progress.fail();
                    this.old_password_error = response.data[0].old_password ? response.data[0].old_password[0] : null;
                    this.new_password_error = response.data[0].new_password ? response.data[0].new_password[0] : null;
                    this.confirm_password_error = response.data[0].confirm_password ? response.data[0].confirm_password[0] : null;
                }

                if (response.data.status == 200) {
                    this.$Progress.finish();
                    // success toast alert
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Password Change Success",
                    });

                    // reset input validation error
                    this.old_password = null;
                    this.new_password = null;
                    this.confirm_password = null;

                    this.old_password_error = null;
                    this.new_password_error = null;
                    this.confirm_password_error = null;
                }
            });
        },
        showPass(e) {
            const parent = e.target.parentElement.parentElement;
            e.target.parentElement.classList.add('hidden');
            parent.children[0].type = 'text';
            parent.children[1].classList.remove('hidden');
        },
        hidePass(e) {
            const parent = e.target.parentElement.parentElement;
            e.target.parentElement.classList.add('hidden');
            parent.children[0].type = 'password';
            parent.children[2].classList.remove('hidden');
        }
    },
}
</script>

<style lang="scss" scoped></style>