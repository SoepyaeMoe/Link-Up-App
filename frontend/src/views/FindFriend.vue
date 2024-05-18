<template>
    <div class="bg-gray-200 md:flex relative">
        <SideBar></SideBar>

        <div class="md:absolute md:top-2 md:w-[20%] w-[50%] md:right-2 right-0 top-14 fixed">
            <form @submit.prevent="search_people" class="flex z-10 shadow-sm">
                <input @keyup.backspace="isEmptyKey" type="text" class="form-control outline-none z-10"
                    placeholder="Search people..." v-model="key">
                <button type="submit" class="bg-blue-600 py-2 px-3 rounded text-white z-10 hover:bg-blue-700">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        <div class="w-[100%] h-screen overflow-y-scroll scrollbar-hide pt-10 md:pt-0">
            <div v-if="users.length != 0" class="md:pt-5 pt-1 mx-auto md:w-[70%] md:px-0 px-2 w-[100%] md:mt-5 mt-14">
                <div v-for="user in users" class="px-4 py-2 shadow-md mb-3 rounded-md bg-gray-100" :key="user.id">
                    <div class="flex justify-between">
                        <div class="flex">
                            <img class="w-14 h-14 rounded-full align-middle"
                                :src="user.profile_photo_path ? profile_img_path + user.profile_photo_path : 'https://ui-avatars.com/api/?name=' + user.name"
                                alt="">
                            <div class="ml-3">
                                <router-link :to="'user-profile/' + user.id" class="font-semibold">
                                    {{ user.name }}
                                </router-link>
                                <p class="text-sm text-gray-500">{{ user.follow }} followers</p>
                            </div>
                        </div>
                        <div>
                            <button class="btn bg-blue-600 hover:bg-blue-700 text-white flex"
                                @click="follow(user.id, $event, 'follow')">
                                <LoadingAnimation class="mr-3 hidden" />
                                Follow
                            </button>
                            <button class="btn hidden bg-blue-600 hover:bg-blue-700 text-white"
                                @click="follow(user.id, $event, 'unfollow')">
                                <LoadingAnimation class="mr-3 hidden" />
                                Unfollow
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="loadedData" v-observe-visibility="handleScrolledToBottom"></div>
            </div>

            <div v-if="users.length == 0" class="mt-20">
                <p class="font-semibold text-xl text-center">No user found.</p>
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
            last_page: 1,
            page: 1,
            loadedData: false,

            key: null,
        }
    },
    components: {
        SideBar, LoadingAnimation
    },
    computed: {
        ...mapGetters(['users']),
    },
    methods: {
        handleScrolledToBottom() {
            this.page += 1;
            if (this.page > this.last_page) { return };
            this.$axios.get(`users?page=${this.page}`)
                .then(response => {
                    if (response.data.status == 200) {
                        this.$store.commit('updateUsers', response.data.data.data);
                    }
                });
        },
        toggleFollowStatus(event, follow_status) {
            if (follow_status == 'follow') {
                event.target.classList.add('hidden');
                event.target.parentElement.children[1].classList.remove('hidden');
                event.target.parentElement.children[1].classList.add('flex');
            }
            if (follow_status == 'unfollow') {
                event.target.classList.add('hidden');
                event.target.parentElement.children[0].classList.remove('hidden');
            }
        },
        follow(user_id, event, follow_status) {
            event.target.childNodes[0].classList.remove('hidden');
            this.$axios.post(`follow/${user_id}`)
                .then(response => {
                    if (response.data.status == 200) {
                        this.toggleFollowStatus(event, follow_status);
                        event.target.childNodes[0].classList.add('hidden');
                    }
                });
        },
        search_people() {
            if (this.key != '' && this.key != null) {
                this.$axios.get(`users?key=${this.key}`)
                    .then(response => {
                        if (response.data.status == 200) {
                            this.$store.commit('setUsers', response.data.data.data);
                            this.last_page = response.data.data.last_page;
                            this.loadedData = true;
                        }
                    });
            }
        },
        getUsers() {
            this.$axios.get('users')
                .then(response => {
                    this.$Progress.finish();
                    if (response.data.status == 200) {
                        this.$store.commit('setUsers', response.data.data.data);
                        this.last_page = response.data.data.last_page;
                        this.loadedData = true;
                    }
                });
        },
        isEmptyKey() {
            if (this.key == null || this.key == '') {
                this.getUsers();
            }
        }
    },
    mounted() {
        this.$Progress.start();
        this.getUsers();
    },
}
</script>

<style lang="scss" scoped></style>