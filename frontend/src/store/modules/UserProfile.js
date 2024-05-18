import axios from "axios";
export default {
    state: {
        otherUserInfo: [],
    },
    getters: {
        otherUserInfo(state) {
            return state.otherUserInfo;
        },
    },
    mutations: {
        setOtherUserInfo(state, data) {
            state.otherUserInfo = data;
        },
        updateFollow(state, id) {
            if (state.otherUserInfo.follow_status == true) {
                state.otherUserInfo.follow -= 1;
                state.otherUserInfo.follow_status = false;
            } else {
                state.otherUserInfo.follow += 1;
                state.otherUserInfo.follow_status = true;
            }
        },
    },
    actions: {
        // getOtherUserInfo({ commit }, id) {
        //     axios.get(`user?id=${id}`)
        //         .then(response => {
        //             if (response.data.status == 200) {
        //                 commit('setOtherUserInfo', response.data.userInfo)
        //             }
        //         });
        // },
    }
}