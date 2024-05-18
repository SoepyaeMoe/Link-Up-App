import { createStore } from 'vuex';
import AuthUserData from './modules/AuthUserData';
import Contents from './modules/Contents';
import UserProfile from './modules/UserProfile';

export default createStore({
    modules: { AuthUserData, Contents, UserProfile },
});