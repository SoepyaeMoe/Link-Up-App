import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import HomeView from '@/views/HomeView.vue'
import ProfileInfo from '@/views/profile/ProfileInfo.vue'
import NotFound from '@/components/NotFound.vue'
import ProfileUpdate from '@/views/profile/ProfileUpdate.vue'
import ChangePassword from '@/views/profile/ChangePassword.vue'
import SaveContent from '@/views/profile/SaveContent.vue'
import BlockList from '@/views/profile/BlockList.vue'
import CreateContent from '@/views/CreateContent.vue'
import UserProfile from '@/views/UserProfile.vue'
import ContentDetail from '@/views/ContentDetail.vue'
import FindFriend from '@/views/FindFriend.vue'
import Notification from '@/views/Notification.vue'

function isAuthenticated() {
    if (localStorage.getItem('token')) {
        return true;
    } else {
        return { path: '/login' }
    }
}

const routes = [
    { path: '/:pathMatch(.*)*', name: '404Page', component: NotFound },
    { path: '/', component: LoginView },
    { path: '/login', component: LoginView },
    { path: '/register', component: RegisterView },
    { path: '/home', component: HomeView, beforeEnter: isAuthenticated },
    { path: '/profile', component: ProfileInfo, beforeEnter: isAuthenticated },
    { path: '/profile/update', component: ProfileUpdate, beforeEnter: isAuthenticated },
    { path: '/change-password', component: ChangePassword, beforeEnter: isAuthenticated },
    { path: '/saved-contents', component: SaveContent, beforeEnter: isAuthenticated },
    { path: '/block-lists', component: BlockList, beforeEnter: isAuthenticated },
    { path: '/create-content', component: CreateContent, beforeEnter: isAuthenticated },
    {
        path: '/user-profile/:id', component: UserProfile, beforeEnter: (to, from) => {
            if (localStorage.getItem('token')) {
                if (to.params.id == atob(localStorage.getItem('i'))) {
                    return { path: '/profile' }
                }
            } else {
                return { path: '/login' }
            }
        }
    },
    { path: '/content-detail/:id', component: ContentDetail, beforeEnter: isAuthenticated },
    { path: '/find-friend', component: FindFriend, beforeEnter: isAuthenticated },
    { path: '/notifications', component: Notification, beforeEnter: isAuthenticated },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: "md:bg-blue-600 md:text-gray-50 bg-blue-600",
    // linkExactActiveClass: "active",
});

export default router;