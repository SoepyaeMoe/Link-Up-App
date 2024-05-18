import axios from 'axios';

export default {
    state: {
        userInfo: [],
    },
    getters: {
        authUser(state) {
            return state.userInfo;
        }
    },
    mutations: {
        setData(state, data) {
            state.userInfo = data;
        }
    },
    actions: {
        getData({ commit }) {
            axios.get('user')
                .then(response => {
                    if(response.data.status == 200){
                        commit('setData', response.data.userInfo);
                    }
                });
        },
        updateData({ commit }, data) {
            commit('setData', data);
        }
    },
}