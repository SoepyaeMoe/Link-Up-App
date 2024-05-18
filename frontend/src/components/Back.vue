<template>
    <div>
        <!-- parent container must have position relative full width-->
        <button
            class="absolute bg-gray-500 rounded-md md:top-4 top-13 left-1 w-7 h-5 z-20 text-gray-50 hover:bg-gray-400"
            @click="back">
            <!-- <img src="../assets/back.svg"> -->
            <i class='bx bx-arrow-back'></i>
        </button>
    </div>
</template>

<script>
export default {
    methods: {
        back() {
            window.history.back(-1);
        }
    },
}
</script>

<style lang="scss" scoped></style>