import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './routes/app.js'
import axios from 'axios'
import store from './store/index.js'
import "preline/preline";
import VueObserveVisibility from 'vue3-observe-visibility'
import VueProgressBar from "@aacassandra/vue3-progressbar";
// tailwind css
import './index.css'
// import 'https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs'



axios.defaults.baseURL = 'http://localhost:8000/api';
if (localStorage.getItem('token')) {
    axios.defaults.headers.common = { 'Authorization': `Bearer ${localStorage.getItem('token')}` };
}

const options = {
    color: "#0ea5e9",
    failedColor: "red",
    thickness: "3px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300,
    },
    autoRevert: true,
    location: "top",
    inverse: false,
};


const app = createApp(App)

app.config.globalProperties.content_img_path = 'http://localhost:8000/storage/contents/';
app.config.globalProperties.profile_img_path = 'http://localhost:8000/storage/profile/';

app.use(router)
app.config.globalProperties.$axios = axios
app.use(store)
app.use(VueObserveVisibility)
app.use(VueProgressBar, options)
app.mount('#app')
