<template>
    <div class="bg-gray-200 md:flex relative">
        <Search class="hidden md:block"></Search>
        <SideBar></SideBar>
        <div class="w-[100%] h-screen overflow-y-scroll scrollbar-hide md:mt-0 mt-10" id="contents_container">
            <Content id="content" :contentContainer="contents" source_content="contents"></Content>
            <div v-if="contents.length" v-observe-visibility="handleScrolledToBottom"></div>
            <p v-if="!contents.length" class="text-center text-gray-500" :class="{ 'hidden': !isLoading }">Loading...
            </p>
        </div>
    </div>
</template>

<script>
import SideBar from '../components/SideBar.vue';
import Content from '../components/Content.vue';
import { mapActions, mapGetters } from 'vuex';
import Search from '../components/Search.vue';
import LoadingAnimation from '../components/LoadingAnimation.vue';

export default {
    components: {
        SideBar, Content, Search, LoadingAnimation
    },
    data() {
        return {
            isLoading: false,
            page: 1,
            loading: true,
            last_page: 1,
        }
    },
    computed: {
        ...mapGetters(['contents'],)
    },
    methods: {
        ...mapActions(['getContent']),
        handleScrolledToBottom(isVisible) {
            this.isLoading = true;
            if (!isVisible) { return };
            if (this.page >= this.last_page) {
                return;
            };
            this.page++;
            this.$axios.get(`contents?page=${this.page}`)
                .then(response => {
                    if (response.data.status == 200) {
                        this.$store.state.Contents.contents.push(...response.data.data);
                        this.last_page = response.data.last_page;
                        this.isLoading = false;
                    }
                })
        }
    },
    mounted() {
        // this.getContent();
        this.$axios.get(`contents?page=1`)
            .then(response => {
                if (response.data.status == 200) {
                    this.$store.commit('setContents', response.data.data);
                    this.last_page = response.data.last_page;
                }
            });
    },
}
</script>