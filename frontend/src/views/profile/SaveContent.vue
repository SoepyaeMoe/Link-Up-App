<template>
    <div>
        <Profile>
            <h2 v-if="savedContents == 0" class="text-xl font-semibold text-center">
                There is no saved contents.
            </h2>
            <div class="max-h-[300px] overflow-auto scrollbar-hide">
                <div class="mb-2 rounded-md pt-3 px-2 relative shadow-lg bg-gray-100 hover:bg-gray-200"
                    v-for="content in savedContents" :key="content.id">
                    <router-link :to="'content-detail/' + content.content_id" class="flex hover:cursor-pointer">
                        <img class="h-12 w-12 flex-none rounded-full bg-gray-50" :src="content.user_photo ? profile_img_path+content.user_photo : 'https://ui-avatars.com/api/?name='+content.user_name"
                            alt="">
                        <div class="ms-2 min-w-0 flex-auto">
                            <p class="font-semibold truncate leading-5">{{ content.title }}</p>
                            <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ content.content }}</p>
                        </div>
                    </router-link>
                    <p class="text-gray-500 text-end"><small>{{ content.created_at }}</small></p>
                    <img @click="removeSave(content.content_id)" class="absolute top-1 right-1 cursor-pointer"
                        src="@/assets/bookmarkminus.svg" alt="">
                </div>
            </div>
        </Profile>
    </div>
</template>

<script>
import Profile from '@/components/Profile.vue';
import { mapActions, mapGetters } from 'vuex';
import Swal from 'sweetalert2';
export default {
    components: { Profile },
    methods: {
        ...mapActions(['getSavedContents']),
        removeSave(content_id) {

            Swal.fire({
                title: "Are you sure?",
                text: "You want to unsave it!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Unsave!",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$axios.post('save-content', { 'content_id': content_id })
                        .then(response => {
                            if (response.data.status == 200) {
                                this.$store.commit('removeSaveContent', content_id);
                                Swal.fire({
                                    title: "Unsaved!",
                                    text: "Your file has been removed from saved.",
                                    icon: "success"
                                });
                            }
                        });
                }
            });
        }

    },
    computed: {
        ...mapGetters(['savedContents']),
    },
    mounted() {
        this.getSavedContents();
    },
}
</script>

<style lang="scss" scoped></style>