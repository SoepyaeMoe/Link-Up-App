<template>
    <div>
        <Profile>
            <form @submit.prevent="update()">
                <div class="mb-3">
                    <input class="form-control mb-0" type="text" placeholder="Your Name" v-model="data.name">
                    <small class="text-red-600">{{ name_error }}</small>
                </div>
                <input class="form-control mb-3" type="text" placeholder="Your Email" v-model="data.email" disabled>
                <input class="form-control mb-3" type="text" placeholder="Your Phone" v-model="data.phone">
                <input class="form-control mb-3" type="text" placeholder="Share Your Bio" v-model="data.bio">
                <input class="form-control mb-3" type="date" placeholder="Your Date Of Birth"
                    v-model="data.date_of_birth">
                <input class="form-control mb-3" type="text" placeholder="Your Address" v-model="data.address">
                <button class="btn bg-blue-600 hover:bg-blue-700 block text-gray-50 mx-auto">Update</button>
            </form>
        </Profile>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import Profile from '@/components/Profile.vue';
import Swal from 'sweetalert2';

export default {
    components: { Profile },
    data() {
        return {
            data: {
                name: '',
                email: '',
                phone: '',
                date_of_birth: '',
                address: '',
                bio: '',

            },
            name_error: '',
        }
    },
    methods: {
        ...mapActions(['updateData']),
        update() {
            this.$axios.post('user', this.data)
                .then(response => {
                    // validation fail message response
                    if (response.data.status == 403) {
                        this.name_error = response.data[0].name ? response.data[0].name[0] : '';
                    }

                    if (response.data.status == 200) {
                        // vuex storage update
                        this.updateData(response.data.userInfo);

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
                            title: "Update Success",
                        });
                    }
                });
        }
    },
    mounted() {
        this.data.name = this.authUser.name;
        this.data.email = this.authUser.email;
        this.data.bio = this.authUser.bio ? this.authUser.bio : '';
        this.data.phone = this.authUser.phone ? this.authUser.phone : '';
        this.data.date_of_birth = this.authUser.date_of_birth ? this.authUser.date_of_birth : '';
        this.data.address = this.authUser.address ? this.authUser.address : '';
    },
    computed: {
        ...mapGetters(['authUser']),
    },
}
</script>


<style lang="scss" scoped></style>