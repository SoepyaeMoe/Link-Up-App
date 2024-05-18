<template>
    <div>
        <div class="absolute top-2 md:w-[20%] w-[50%] right-2">
            <form @submit.prevent="search" class="flex z-10 shadow-sm">
                <input @keyup.backspace="empty = true" type="text" class="form-control outline-none z-10"
                    placeholder="Search content..." v-model="key">
                <button type="submit" class="bg-blue-600 py-2 px-3 rounded text-white z-10 hover:bg-blue-700">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
        <!-- search items -->
        <div class="absolute top-12 right-2 border bg-gray-50 md:w-[30%] w-[80%] z-10 shadow-sm"
            :class="{ 'hidden': empty }">
            <div v-if="contents.length == 0" class="border rounded px-2 py-4">
                <div class="font-semibold text-center">No result found!</div>
            </div>
            <div v-for="content in contents" :key="content.id">
                <router-link :to="'content-detail/' + content.id"
                    class="border block rounded px-2 py-1 flex-auto hover:bg-gray-100">
                    <div class="font-semibold">{{ content.title }}</div>
                    <div class="text-gray-700 font-light text-sm truncate">{{ content.content }}</div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            key: null,
            empty: true,
            contents: [],
        }
    },
    methods: {
        search() {
            if (this.key == null || this.key == '') {
                this.empty = true;
                return;
            }
            this.$axios.post('search-content', { 'key': this.key })
                .then(response => {
                    if (response.data.status == 200) {
                        this.contents = response.data.data;
                        this.empty = false;
                    }
                });
        }
    },
}
</script>

<style lang="scss" scoped></style>