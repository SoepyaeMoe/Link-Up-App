<template>
    <div>
        <section class="bg-gray-50">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
                <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900">
                    <img class="w-8 h-8 mr-2" src="@/assets/share-link.png" alt="logo">
                    Linkup
                </a>
                <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                            Sign in to your account
                        </h1>
                        <form class="space-y-4 md:space-y-6" @submit.prevent="login">
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your
                                    email</label>
                                <input type="email" name="email" id="email" v-model="email" required
                                    :class="{ 'border border-red-500': emailError }"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                    placeholder="name@example.com">
                                <small class="text-red-500">{{ emailError }}</small>
                            </div>
                            <div>
                                <label for="password"
                                    class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                <input type="password" name="password" id="password" v-model="password" required
                                    placeholder="••••••••" :class="{ 'border border-red-500': passwordError }"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                <small class="text-red-500">{{ passwordError }}</small>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="remember" aria-describedby="remember" type="checkbox"
                                            v-model="checkbox"
                                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="remember" class="text-gray-500">Remember
                                            me</label>
                                    </div>
                                </div>
                                <a href="#" class="text-sm font-medium text-sky-500 hover:underline">Forgot
                                    password?</a>
                            </div>
                            <button type="submit"
                                class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700">Sign
                                in</button>
                            <p class="text-sm font-light text-gray-500">
                                Don’t have an account yet? <router-link to="register"
                                    class="font-medium text-sky-500 hover:underline">Sign
                                    up</router-link>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>

export default {
    data() {
        return {
            email: null,
            password: null,
            checkbox: null,

            emailError: null,
            passwordError: null,
        }
    },
    methods: {
        login() {
            this.$Progress.start();
            this.$axios.post('login', {
                'email': this.email,
                'password': this.password,
            }).then(response => {
                if (response) {
                    // validation
                    if (response.data.status == 403) {
                        this.$Progress.fail();
                        this.passwordError = response.data[0].password ? response.data[0].password[0] : null;
                        this.emailError = response.data[0].email ? response.data[0].email[0] : null;
                        this.emailError = response.data.email ? response.data.email : null;
                    }

                    if (response.data.status == 200) {
                        this.$Progress.finish();
                        let token = response.data.token;
                        localStorage.setItem('token', token);
                        localStorage.setItem('i', btoa(response.data.data.id));
                        this.$axios.defaults.headers.common = { 'Authorization': `Bearer ${token}` };
                        this.$router.push('/home');
                    }
                }
            });
        }
    },
}
</script>

<style lang="scss" scoped></style>