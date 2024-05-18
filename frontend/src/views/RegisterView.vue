<template>
    <div>
        <section class="bg-gray-50 py-10">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
                <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900">
                    <img class="w-8 h-8 mr-2" src="@/assets/share-link.png" alt="logo">
                    Linkup
                </a>
                <div class="w-full bg-white rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
                            Create Accound
                        </h1>
                        <form class="space-y-4 md:space-y-6" @submit.prevent="register">

                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Your
                                    name</label>
                                <input type="text" name="name" id="name" v-model="name"
                                    :class="{ 'border border-red-500': nameError }"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                    placeholder="Enter your name" required>
                                <small class="text-red-500">{{ nameError }}</small>
                            </div>

                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Your
                                    email</label>
                                <input type="email" name="email" id="email" v-model="email"
                                    :class="{ 'border border-red-500': emailError }"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                    placeholder="name@email.com" required>
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

                            <div>
                                <label for="cpassword" class="block mb-2 text-sm font-medium text-gray-900">Confirm
                                    Password</label>
                                <input type="password" name="cpassword" id="cpassword" v-model="confirmation_password"
                                    placeholder="••••••••" required
                                    :class="{ 'border border-red-500': confirmation_passwordError }"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5">
                                <small class="text-red-500">{{ confirmation_passwordError }}</small>
                            </div>

                            <button type="submit"
                                class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center">Register</button>
                            <p class="text-sm font-light text-gray-500">
                                Already have an account? <router-link to="login"
                                    class="font-medium text-sky-500 hover:underline">Login
                                    here</router-link>
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
            name: null,
            email: null,
            password: null,
            confirmation_password: null,

            nameError: null,
            emailError: null,
            passwordError: null,
            confirmation_passwordError: null,
        }
    },
    methods: {
        register() {
            this.$Progress.start();
            this.$axios.post('register', {
                'name': this.name,
                'email': this.email,
                'password': this.password,
                'confirmation_password': this.confirmation_password
            }).then(response => {
                if (response.data.status == 403) {
                    this.$Progress.fail();
                    this.nameError = response.data[0].name ? response.data[0].name[0] : null;
                    this.emailError = response.data[0].email ? response.data[0].email[0] : null;
                    this.passwordError = response.data[0].password ? response.data[0].password[0] : null;
                    this.confirmation_passwordError = response.data[0].confirmation_password ? response.data[0].confirmation_password[0] : null;
                }

                if (response.data.status == 201) {
                    this.$Progress.finish();
                    let token = response.data.token;
                    if (localStorage.getItem('token')) {
                        localStorage.removeItem('token');
                    }
                    localStorage.setItem('token', token);
                    localStorage.setItem('i', btoa(response.data.data.id));
                    this.$axios.defaults.headers.common = { 'Authorization': `Bearer ${token}` };
                    this.$router.push('/home');
                }
            })
        }
    },
}
</script>

<style lang="scss" scoped></style>