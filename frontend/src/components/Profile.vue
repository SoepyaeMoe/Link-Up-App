<template>
    <div class="md:flex bg-gray-200">
        <SideBar></SideBar>
        <!-- profile container -->
        <div
            class="mt-10 md:mt-0 pt-5 mx-auto md:w-[70%] md:px-0 px-2 w-[100%] overflow-y-scroll scrollbar-hide h-screen relative">
            <Back></Back>

            <!-- profile photo upload modal -->
            <div class="absolute bg-gray-50 z-30 w-[100%] rounded py-3 shadow" :class="{ 'hidden': !open }">
                <h3 class="text-lg font-semibold uppercase ms-3">Profile Photo Upload</h3>
                <div class="mx-3 mt-3">
                    <input type="file" class="form-control" @change="showUploadFile">
                </div>
                <div class="mx-3 mt-3">
                    <div class="w-[300px] min-[300px] h-[300px] border border-collapse mx-auto text-center">
                        <span :class="{ 'hidden': showImage }"><i
                                class="fa-regular fa-image text-5xl text-gray-500 mt-32"></i></span>
                        <img :class="{ 'hidden': !showImage }" class="w-[100%] h-[100%] object-cover" :src="imgSrc"
                            alt="" id="profileImage">
                    </div>
                    <div class="mt-3 text-end">
                        <button class="btn bg-red-600 me-2 hover:bg-red-700 text-gray-50"
                            @click="open = !open">Cancel</button>
                        <button class="btn bg-blue-600 hover:bg-blue-700 text-gray-50" @click="upload">
                            <LoadingAnimation :class="{ 'hidden': !uploadLoading }" class="inline-block me-2" />Upload
                        </button>
                    </div>
                </div>
            </div>
            <!-- end profile photo upload modal -->

            <!-- profile header -->
            <div class="bg-gray-50 w-[100%] md:h-[250px] h-[200px] rounded-xl shadow-sm relative mb-32">
                <img v-if="authUser.cover_photo" :src="profile_img_path + authUser.cover_photo" alt=""
                    class="w-[100%] h-[100%] object-cover rounded-xl">
                <img v-if="!authUser.cover_photo" src="@/image/cover_photo.jpg" alt=""
                    class="w-[100%] h-[100%] object-cover rounded-xl">
                <div
                    class="rounded-full border-4 border-gray-200 w-28 h-28 shadow-sm absolute bottom-[-30%] md:left-10 md:ml-0 right-0 left-0 mx-auto">
                    <img :src="authUser.profile_photo_path ? profile_img_path + authUser.profile_photo_path : 'https://ui-avatars.com/api/?color=7F9CF5&background=EBF4FF&name=' + authUser.name"
                        alt="" class="w-[100%] h-[100%] rounded-full object-cover">
                    <div @click="open = true"
                        class="absolute text-gray-600 bg-gray-200 rounded-full px-1 bottom-1 right-2 hover:cursor-pointer">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                </div>
                <div class="text-center md:mt-2 mt-14 md:text-start md:ml-40">
                    <p class="text-xl font-semibold">{{ authUser.name }}</p>
                    <p class="font-light text-sm">{{ authUser.bio }}</p>
                </div>
            </div>
            <!-- end profile header -->

            <div class="md:grid md:grid-cols-12 md:gap-5">
                <!-- profile menu for >mediun screen -->
                <div class="md:col-span-4 hidden md:block">
                    <router-link to="/profile" class="bg-gray-50 px-5 py-3 rounded block">
                        <img src="@/assets/user.svg" alt="" class="inline me-2 w-5">
                        Personal Information
                    </router-link>

                    <router-link to="/profile/update" class="bg-gray-50 px-5 py-3 rounded block mt-2">
                        <img src="@/assets/edit.svg" alt="" class="inline me-2 w-5">
                        Update Your Profile
                    </router-link>

                    <router-link to="/change-password" class="bg-gray-50 px-5 py-3 rounded block mt-2">
                        <img src="@/assets/lock.svg" alt="" class="inline me-2 w-5">
                        Change Your Password
                    </router-link>

                    <router-link to="/saved-contents" class="bg-gray-50 px-5 py-3 rounded block mt-2">
                        <img src="@/assets/bookmark.svg" alt="" class="inline me-2 w-5">
                        Saved Contents
                    </router-link>

                    <router-link to="/block-lists" class="bg-gray-50 px-5 py-3 rounded block mt-2">
                        <img src="@/assets/block.svg" alt="" class="inline me-2 w-5">
                        Blick Lists
                    </router-link>
                </div>
                <!-- profile menu end for >medium screen  -->

                <!-- profile menu for < medium screen -->
                <div class="flex justify-between mb-3 md:hidden">
                    <router-link to="/profile" class="px-6 py-3 rounded block mt-2">
                        <img src="@/assets/user.svg" alt="" class="inline w-5">
                    </router-link>

                    <router-link to="/profile/update" class="px-6 py-3 rounded block mt-2">
                        <img src="@/assets/edit.svg" alt="" class="inline w-5">
                    </router-link>

                    <router-link to="/change-password" class="px-6 py-3 rounded block mt-2">
                        <img src="@/assets/lock.svg" alt="" class="inline w-5">
                    </router-link>

                    <router-link to="/saved-contents" class="px-6 py-3 rounded block mt-2">
                        <img src="@/assets/bookmark.svg" alt="" class="inline w-5">
                    </router-link>

                    <router-link to="/block-lists" class="px-6 py-3 rounded block mt-2">
                        <img src="@/assets/block.svg" alt="" class="inline w-5">
                    </router-link>
                </div>
                <div class="md:col-span-8 bg-gray-50 rounded px-5 sm:px-16 py-8 ">
                    <slot />
                </div>
            </div>

        </div>
        <!-- end profile container -->
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import SideBar from '@/components/SideBar.vue';
import Back from '@/components/Back.vue';
import LoadingAnimation from './LoadingAnimation.vue';
import Swal from 'sweetalert2';
export default {
    components: {
        SideBar, Back, LoadingAnimation
    },
    data() {
        return {
            open: false,
            showImage: false,
            imgSrc: null,
            uploadLoading: false,

            file: null,
        }
    },
    methods: {
        showUploadFile(e) {
            // const targetTag = document.getElementById('profileImage');
            this.imgSrc = URL.createObjectURL(e.target.files[0]);
            this.file = e.target.files[0];
            this.showImage = true;
            // targetTag.src = image;
        },
        upload() {
            this.uploadLoading = true;
            this.$axios.post('profile-upload', {
                'file': this.file
            },
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }).then(response => {
                    if (response.data.status == 201) {
                        this.$store.state.AuthUserData.userInfo.profile_photo_path = response.data.data;
                        this.file = null;
                        this.imgSrc = null;
                        this.open = false;
                        this.showImage = false;
                        this.uploadLoading = false;

                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: "Profile Photo Upload Success!"
                        });
                    }
                });
        }
    },
    computed: {
        ...mapGetters(['authUser']),
    },
}
</script>

<style lang="scss" scoped></style>