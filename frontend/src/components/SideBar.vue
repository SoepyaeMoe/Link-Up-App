<template>
    <div>
        <!--  md screen > -->
        <div
            class="bg-gray-50 w-64 min-w-72 overflow-scroll h-screen scrollbar-hide md:flex flex-col justify-between hidden">
            <div class="inline-block text-center shadow">
                <div>
                    <h1 class="text-xl font-bold uppercase py-3 text-blue-500" style="letter-spacing: 3px">linkup</h1>
                </div>
            </div>
            <div class="flex justify-between flex-col h-full">

                <!-- menu list -->
                <div class="mt-3">
                    <router-link to="/create-content"
                        class="bg-blue-500 py-2 px-3 mb-3 block rounded shadow mx-3 text-center text-gray-100 font-semibold hover:shadow-lg hover:bg-blue-600 duration-75">+
                        Create Contents</router-link>
                    <router-link to="/home"
                        class="block text-font-semibold hover:cursor-pointer hover:bg-gray-100 hover:text-gray-900 px-5 py-4"><img
                            class="inline me-3" src="../assets/home.svg" alt=""> Home</router-link>
                    <router-link to="/find-friend"
                        class="block text-font-semibold hover:cursor-pointer hover:bg-gray-100 hover:text-gray-900 px-5 py-4"><img
                            class="inline me-3" src="../assets/users.svg" alt=""> Find Friends</router-link>
                    <router-link to="/saved-contents"
                        class="block text-font-semibold hover:cursor-pointer hover:bg-gray-100 hover:text-gray-900 px-5 py-4"><img
                            class="inline me-3" src="../assets/bookmark.svg" alt=""> Saved</router-link>
                    <router-link to="/notifications"
                        class="block text-font-semibold hover:cursor-pointer relative hover:bg-gray-100 hover:text-gray-900 px-5 py-4"><img
                            class="inline me-3" src="../assets/bell.svg" alt="">Notifications
                        <div v-show="authUser.unread_noti != 0"
                            class="bg-red-600 py-0.5 rounded-md absolute top-0 right-1 px-1">
                            <span class="text-white">{{ authUser.unread_noti }}</span>
                        </div>
                    </router-link>
                    <router-link to="/profile"
                        class="block text-font-semibold hover:cursor-pointer hover:bg-gray-100 hover:text-gray-900 px-5 py-4"><img
                            class="inline me-3" src="../assets/user.svg" alt=""> Profile</router-link>
                </div>
                <!-- end menu list -->

                <!-- profile -->
                <div class="relative mb-2">
                    <div @click="isHide = !isHide"
                        class="flex align-middle border border-gray-300 rounded-md bg-gray-100 px-2 hover:cursor-pointer hover:bg-gray-200">
                        <img class="rounded-full me-3 w-12 h-12 mt-2 object-cover"
                            :src="authUser.profile_photo_path ? profile_img_path + authUser.profile_photo_path : 'https://ui-avatars.com/api/?color=7F9CF5&background=EBF4FF&name=' + authUser.name"
                            alt="">
                        <div class="my-2">
                            <p class="font-semibold mb-0">{{ authUser.name }}</p>
                            <small class="text-gray-700 mb-0 truncate leading-5">{{ authUser.email }}</small>
                        </div>
                    </div>
                    <div class="absolute bg-gray-100 shadow-lg top-[-70px] right-[10px]" :class="{ 'hidden': isHide }">
                        <ul>
                            <router-link to="/profile" class="py-1 hover:bg-gray-200 px-2 cursor-pointer"><span
                                    class="bg-blue-600 p-1 text-gray-50 rounded-md px-11">Profile</span></router-link>
                            <li class="py-1 hover:bg-gray-200 px-11 cursor-pointer text-red-500" @click="logout">Logout
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- end profile -->

            </div>
        </div>

        <!--md screen <  -->
        <div class="md:hidden flex fixed top-0 w-[100%] z-20 justify-between bg-gray-100 rounded-b py-2 px-1 shadow">
            <router-link to="/create-content"
                class="block text-font-semibold hover:cursor-pointer hover:bg-gray-100 hover:text-gray-900 p-1 rounded">
                <img class="inline" src="../assets/plus-solid.svg" alt="">
            </router-link>
            <router-link to="/home"
                class="block text-font-semibold hover:cursor-pointer hover:bg-gray-100 hover:text-gray-900 p-1 rounded">
                <img class="inline" src="../assets/home.svg" alt=""></router-link>
            <router-link to="/find-friend"
                class="block text-font-semibold hover:cursor-pointer hover:bg-gray-100 hover:text-gray-900 p-1 rounded"><img
                    class="inline" src="../assets/users.svg" alt=""></router-link>
            <router-link to="/notifications"
                class="block text-font-semibold hover:cursor-pointer hover:bg-gray-100 hover:text-gray-900 p-1 rounded"><img
                    class="inline" src="../assets/bell.svg" alt=""></router-link>
            <router-link to="/profile"
                class="block text-font-semibold hover:cursor-pointer hover:bg-gray-100 hover:text-gray-900 p-1 rounded"><img
                    class="inline" src="../assets/user.svg" alt=""></router-link>
            <div to="/profile"
                class="block font-bold hover:cursor-pointer hover:bg-gray-100 hover:text-gray-900 p-1 rounded">
                <img @click="searchOpen = !searchOpen" class="inline" src="../assets/search.svg" alt=""
                    :class="{ 'hidden': searchOpen }">
                <div @click="searchOpen = !searchOpen" class="font-bold text-lg" :class="{ 'hidden': !searchOpen }"><i
                        class="fa-solid fa-xmark"></i></div>
            </div>
        </div>
        <div class="relative">
            <Search class="md:hidden" :class="{ 'hidden': !searchOpen }" />
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import Search from './Search.vue'
import Swal from 'sweetalert2'
export default {
    data() {
        return {
            isHide: true,
            searchOpen: false
        }
    },
    components: {
        Search,
    },
    methods: {
        ...mapActions(['getData']),
        logout() {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-danger m-2 text-white",
                    cancelButton: "btn btn-success text-white"
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
        }
    },
    computed: {
        ...mapGetters(['authUser']),
    },
    mounted() {
        this.getData();
    },
}

</script>

<style lang="scss" scoped></style>