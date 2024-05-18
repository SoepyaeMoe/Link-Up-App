<template>
    <div class="bg-gray-200 md:flex relative">
        <SideBar></SideBar>
        <div class="w-[100%] h-screen overflow-y-scroll scrollbar-hide mt-10 md:mt-0">
            <div class="md:pt-5 pt-1 mx-auto md:w-[70%] md:px-0 px-2 w-[100%] mt-5">
                <div v-for="(noti, index) in notifications"
                    class="px-4 py-2 shadow-md mb-3 rounded-md bg-gray-100 relative" :key="index">
                    <div class="flex">
                        <img class="w-14 h-14 rounded-full align-middle"
                            :src="noti.profile_photo ? profile_img_path + noti.profile_photo : 'https://ui-avatars.com/api/?name=' + noti.from_name"
                            alt="">
                        <router-link v-if="noti.from_name != 'Admin'"
                            :to="noti.target_id ? 'content-detail/' + noti.target_id : 'user-profile/' + noti.from_id"
                            class="ml-3">
                            <span>
                                <span class="font-semibold">
                                    {{ noti.from_name }}
                                </span>
                                {{ noti.message }}
                            </span>
                            <div>
                                <small>{{ noti.created_at }}</small>
                            </div>
                        </router-link>
                        <div v-if="noti.from_name == 'Admin'" class="ml-3">
                            <span>
                                <span class="font-semibold">
                                    {{ noti.from_name }}
                                </span>
                                {{ noti.message }}
                            </span>
                            <div>
                                <small>{{ noti.created_at }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="absolute top-1 right-1 text-center">
                        <LoadingAnimation class="hidden" />
                        <div>
                            <i class="fa-solid fa-trash text-red-600 hover:cursor-pointer"
                                @click="remove(noti.id, $event)"></i>
                        </div>
                        <p class="text-sm text-red-600 mt-[-7px]">remove</p>
                    </div>
                </div>
                <div v-if="notifications.length == 0">
                    <p class="text-center text-xl">There is no notifications.</p>
                </div>
                <div v-if="loadedData" v-observe-visibility="handleScrolledToBottom"></div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import LoadingAnimation from '../components/LoadingAnimation.vue';
import SideBar from '../components/SideBar.vue';
export default {
    data() {
        return {
            loadedData: false,
            last_page: 1,
            page: 1,
        }
    },
    components: {
        SideBar, LoadingAnimation
    },
    computed: {
        ...mapGetters(['notifications']),
    },
    methods: {
        handleScrolledToBottom() {
            this.page += 1;
            if (this.page > this.last_page) { return };
            this.$axios.get(`notification?page=${this.page}`)
                .then(response => {
                    if (response.data.status == 200) {
                        this.$store.commit('updateNotifications', response.data.data);
                    }
                });
        },
        remove(id, event) {
            event.target.parentElement.parentElement.childNodes[0].classList.remove('hidden');
            event.target.parentElement.classList.add('hidden');

            this.$axios.delete('notification?id=' + id)
                .then(response => {
                    if (response.data.status == 200) {
                        event.target.parentElement.parentElement.parentElement.remove();
                    }
                    event.target.parentElement.parentElement.childNodes[0].classList.add('hidden');
                    event.target.parentElement.classList.remove('hidden');
                });
        }
    },
    mounted() {
        this.$Progress.start();
        this.$axios.get('notification')
            .then(response => {
                if (response.data.status == 200) {
                    this.$Progress.finish();
                    this.$store.commit('setNotifications', response.data.data);
                    this.loadedData = true;
                    this.last_page = response.data.last_page;
                }
            });
        this.$axios.get('notification-read')
            .then(response => {
                if (response.data.status == 200) {
                    this.$store.state.AuthUserData.userInfo.unread_noti = 0;
                }
            })
    },
}
</script>
