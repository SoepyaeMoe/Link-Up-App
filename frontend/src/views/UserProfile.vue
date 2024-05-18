<template>
    <div class="md:flex bg-gray-200">
        <SideBar></SideBar>

        <div class="w-[100%] h-screen overflow-y-scroll scrollbar-hide relative pt-14 md:pt-0">

            <!-- <div :class="{ 'hidden': !loadingProfile }" class="w-[100%]">
                <img class="mx-auto" src="@/assets/spinner.svg" alt="">
            </div> -->

            <Back></Back>
            <!-- profile header -->
            <div class="pt-5 mx-auto md:w-[90%] md:px-0 px-2 w-[100%] md:mb-24 mb-48">
                <div class="bg-gray-50 w-[100%] md:h-[250px] h-[200px] rounded-xl shadow-sm relative mb-32">
                    <img v-if="otherUserInfo.cover_photo" :src="profile_img_path + otherUserInfo.cover_photo" alt=""
                        class="w-[100%] h-[100%] object-cover rounded-xl">
                    <img v-if="!otherUserInfo.cover_photo" src="@/image/cover_photo.jpg" alt=""
                        class="w-[100%] h-[100%] object-cover rounded-xl">
                    <div>
                        <div
                            class="rounded-full border-4 border-gray-200 w-28 h-28 shadow-sm absolute bottom-[-30%] md:left-10 md:ml-0 right-0 left-0 mx-auto">
                            <img :src="otherUserInfo.profile_photo_path ? profile_img_path + otherUserInfo.profile_photo_path : 'https://ui-avatars.com/api/?color=7F9CF5&background=EBF4FF&name=' + otherUserInfo.name"
                                alt="" class="w-[100%] h-[100%] rounded-full object-contain">
                        </div>
                        <div class="text-center md:mt-2 mt-14 md:text-start md:ml-40">
                            <p class="text-xl font-semibold">{{ otherUserInfo.name }}</p>
                            <p class="text-base font-light">{{ otherUserInfo.bio }}</p>
                        </div>
                        <div class="my-3 md:ml-40 md:text-start text-center">
                            <span class="me-3 bg-gray-300 rounded-lg py-1 px-2"><span
                                    class="text-blue-600 font-semibold">{{ otherUserInfo.contents_count }}</span>
                                Contents</span>
                            <span class="me-3 bg-gray-300 rounded-lg py-1 px-2"><span
                                    class="text-blue-600 font-semibold">{{ otherUserInfo.follow }}</span>
                                Followers</span>
                            <span class="me-3 bg-gray-300 rounded-lg py-1 px-2"><span
                                    class="text-blue-600 font-semibold">{{ otherUserInfo.heart }}</span>
                                Hearts</span>
                        </div>
                    </div>
                    <div class="md:absolute md:right-0 bottom-[-20%]">
                        <button :class="{ 'hidden': otherUserInfo.follow_status }" @click="follow(otherUserInfo.id)"
                            class="block mx-auto btn-outline border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-gray-50">
                            <div class="flex align-middle">
                                <LoadingAnimation class="me-2 block" :class="{ 'hidden': isNotLoading }" />
                                <i class='bx bx-plus me-2 mt-1 block' :class="{ 'hidden': !isNotLoading }"></i>
                                Follow
                            </div>
                        </button>
                        <button :class="{ 'hidden': !otherUserInfo.follow_status }" @click="follow(otherUserInfo.id)"
                            class="block mx-auto btn-outline border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-gray-50">
                            <div class="flex align-middle">
                                <LoadingAnimation class="me-2 block" :class="{ 'hidden': isNotLoading }" />
                                <i class='bx bx-check font-bold me-2 mt-1 block'
                                    :class="{ 'hidden': !isNotLoading }"></i>
                                unfollow
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <!-- end profile header -->

            <div v-if="otherUserContents">
                <Content :contentContainer="otherUserContents" source_content="otherUserContents"></Content>
                <div v-if="otherUserContents.length" v-observe-visibility="handleScrolledToBottom"></div>
                <p class="text-center text-gray-500" :class="{ 'hidden': !isLoading }">Loading...</p>
            </div>
            <div v-if="otherUserContents.length == 0"
                class="py-5 mx-auto md:w-[70%] md:px-1 px-2 w-[100%] mt-5 bg-gray-100 rounded-md shadow-sm">
                <p class="text-center mb-0 font-semibold text-xl">No Contents</p>
            </div>

        </div>
    </div>
</template>

<script>
import SideBar from '@/components/SideBar.vue';
import Content from '../components/Content.vue';
import { mapActions, mapGetters } from 'vuex';
import Back from '@/components/Back.vue';
import LoadingAnimation from '@/components/LoadingAnimation.vue';

export default {
    components: {
        SideBar, Content, Back, LoadingAnimation
    },
    data() {
        return {
            isNotLoading: true,
            loadingProfile: false,
            isLoading: false,
            last_page: 1,
            page: 1,
        }
    },
    methods: {
        // ...mapActions(['getOtherUserInfo']),
        follow(id) {
            this.isNotLoading = false;
            this.$axios.post(`follow/${id}`)
                .then(response => {
                    if (response.data.status == 200) {
                        this.$store.commit('updateFollow');
                        this.isNotLoading = true;
                    }
                });
        },
        handleScrolledToBottom(isVisible) {
            this.isLoading = true;
            if (!isVisible) { return };
            if (this.page >= this.last_page) { return };
            this.page++;
            this.$axios.get(`contents/${this.$route.params.id}?page=${this.page}`)
                .then(response => {
                    if (response.data.status == 200) {
                        this.$store.state.Contents.otherUserContents.push(...response.data.data);
                        this.isLoading = false;
                    }
                });
        }
    },
    computed: {
        ...mapGetters(['otherUserInfo', 'otherUserContents']),
    },
    mounted() {
        // this.getContent(this.$route.params.id);
        this.loadingProfile = true;
        this.$axios.get(`contents/${this.$route.params.id}`)
            .then(response => {
                if (response.data.status == 200) {
                    this.$store.commit('setOtherUserContents', response.data.data);
                    this.last_page = response.data.last_page;
                    this.loadingProfile = false;
                }
            })
        // this.getOtherUserInfo(this.$route.params.id);
        this.$axios.get(`user?id=${this.$route.params.id}`)
            .then(response => {
                if (response.data.status == 200) {
                    this.$store.commit('setOtherUserInfo', response.data.userInfo)
                }
                if (response.data.status == 404) {
                    this.$router.push({ name: "404Page" })
                }
            });
    },
}
</script>

<style lang="scss" scoped></style>