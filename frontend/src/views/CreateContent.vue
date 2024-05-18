<template>
    <div class="bg-gray-200 md:flex">
        <SideBar />
        <div class="relative pt-14 md:pt-0 w-[100%]">
            <Back></Back>
            <div class="pt-10 mx-auto md:w-[60%] md:px-0 px-3 w-[100%] overflow-y-scroll scrollbar-hide h-screen">
                <div class="bg-gray-50 rounded-md py-3">
                    <h1 class="text-xl font-semibold ms-4">Share Content</h1>
                    <form class="mt-5 px-4">
                        <div class="mb-3">
                            <label for="des">Title</label>
                            <input type="text" id="des" class="form-control mt-1" placeholder="Title"
                                v-model="data.title">
                        </div>

                        <div class="mb-3">
                            <label for="content">Content</label>
                            <textarea type="text" cols="30" id="content"
                                :class="{ 'border border-red-600': contentError != null }" class="form-control mt-1"
                                placeholder="Content" v-model='data.content'></textarea>
                            <small :class="{ 'text-red-600': contentError != null }">{{ contentError }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="file">Image</label>
                            <button :class="{ 'hidden': !uploaded }" @click="cancel" type="button"
                                class="hover:bg-red-600 hover:text-white duration-200 float-end border rounded-md py-1 px-1 text-red-600 border-red-600 text-xs uppercase">
                                <i class="fa-solid fa-xmark"></i>Cancel
                            </button>
                            <div class="relative">
                                <input type="file" id="targetFile" ref="inputFile" class="form-control mt-1"
                                    @change="uploadImg">
                                <div class="absolute right-2 top-4" :class="{ 'hidden': !loading }">
                                    <LoadingAnimation></LoadingAnimation>
                                </div>
                            </div>
                            <small :class="{ 'text-red-600': imageError != null }">{{ imageError }}</small>
                            <small :class="{ 'text-green-600': imageSuccess != null }">{{ imageSuccess }}</small>
                        </div>

                        <!-- <div class="mb-3">
                            <label for="vd_link">U Tube embded link</label>
                            <input type="text" id="vd_link" class="form-control mt-1" v-model="data.video_link">
                        </div> -->

                        <button type="button" class="btn bg-blue-600 mx-auto block text-gray-50"
                            @click="create">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SideBar from '../components/SideBar.vue';
import Back from '../components/Back.vue';
import LoadingAnimation from '../components/LoadingAnimation.vue';

export default {
    components: { SideBar, Back, LoadingAnimation },
    data() {
        return {
            data: {
                title: null,
                content: null,
                video_link: null,

                image: null,
            },
            loading: false,
            uploaded: false,
            imageError: null,
            imageSuccess: null,
            contentError: null,
        }
    },
    methods: {
        // img upload
        uploadImg(e) {
            this.loading = true;
            this.$axios.post('upload-content-img', { 'image': e.target.files[0] }, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                if (response.data.status == 201) {
                    this.data.image = response.data.data;
                    this.imageSuccess = response.data.message;
                    this.uploaded = true;
                    this.imageError = null;
                    this.loading = false;
                }

                if (response.data.status == 403) {
                    this.imageError = response.data[0].image ? response.data[0].image[0] : null;
                    this.loading = false;
                }
            });
        },
        // img upload cancel
        cancel() {
            this.$axios.post('upload-cancel', { 'image': this.data.image })
                .then(response => {
                    if (response.data.status == 200) {
                        this.uploaded = false;
                        this.imageSuccess = null;
                        this.$refs.inputFile.value = '';
                    }
                });
        },
        create() {
            this.$Progress.start();
            this.$axios.post('create-content', this.data)
                .then(response => {
                    if (response.data.status == 403) {
                        this.$Progress.fail();
                        this.contentError = response.data[0].content ? response.data[0].content[0] : null;
                    }
                    if (response.data.status == 201) {
                        this.$Progress.finish();
                        this.$router.push('/home');
                    }
                });
        },
    },
}
</script>

<style lang="scss" scoped></style>