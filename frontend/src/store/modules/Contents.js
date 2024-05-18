import axios from "axios";
export default {
    state: {
        contents: [],
        comments: [],
        // comments: [
        //     content_id=1: [{}, {}],
        //     content_id=2: [{}, {}],
        // ]
        savedContents: [],
        contentDetail: [],
        otherUserContents: [],
        notifications: [],
        users: [],
    },
    getters: {
        contents(state) {
            return state.contents;
        },
        comments(state) {
            return state.comments;
        },
        savedContents(state) {
            return state.savedContents;
        },
        contentDetail(state) {
            return state.contentDetail;
        },
        otherUserContents(state) {
            return state.otherUserContents;
        },
        notifications(state) {
            return state.notifications;
        },
        users(state) {
            return state.users;
        }
    },
    mutations: {
        setContents(state, data) {
            state.contents = data;
        },
        setComment(state, data) {
            if (data.length != 0) {
                let key = data[0].content_id;
                state.comments[key] = data;
            }
        },
        // set new commet in state
        setNewComment(state, data) {
            if (state.comments.hasOwnProperty(data.content_id)) {
                state.comments[data.content_id].push(data);
                state.contents.forEach(e => {
                    if (e.id == data.content_id) {
                        e.comment_count += 1;
                    }
                });
            } else {
                state.comments[data.content_id] = [data];
                state.contents.forEach(e => {
                    if (e.id == data.content_id) {
                        e.comment_count += 1;
                    }
                });
            }
        },

        setOtherUserContents(state, data) {
            state.otherUserContents = data;
        },

        // update react status 
        updateReactStatus(state, data) {
            if (data.source_content == 'contents') {
                if (data.index != null) {
                    if (state.contents[data.index].react_status == true) {
                        state.contents[data.index].react -= 1;
                    } else {
                        state.contents[data.index].react += 1;
                    }
                    state.contents[data.index].react_status = !state.contents[data.index].react_status;
                } else {
                    if (state.contentDetail.react_status == true) {
                        state.contentDetail.react -= 1;
                    } else {
                        state.contentDetail.react += 1;
                    }
                    state.contentDetail.react_status = !state.contentDetail.react_status;
                }
            }
            if (data.source_content == 'otherUserContents') {
                if (data.index != null) {
                    if (state.otherUserContents[data.index].react_status == true) {
                        state.otherUserContents[data.index].react -= 1;
                    } else {
                        state.otherUserContents[data.index].react += 1;
                    }
                    state.otherUserContents[data.index].react_status = !state.otherUserContents[data.index].react_status;
                }
            }
        },

        // set saved content
        setSavedContent(state, data) {
            state.savedContents = data;
        },

        // set content detail
        setContentDetail(state, data) {
            state.contentDetail = data;
        },

        // update follow in content detail
        updateFollow(state) {
            state.contentDetail.follow_status = !state.contentDetail.follow_status;
        },

        // update follow status in home page
        updateFollowInHome(state, data) {
            if (data.source_content == 'contents') {
                state.contents.forEach(content => {
                    if (content.user_id == data.user_id) {
                        content.follow_status = !content.follow_status;
                    }
                });
            }
            if (data.source_content == 'otherUserContents') {
                state.otherUserContents.forEach(content => {
                    if (content.user_id == data.user_id) {
                        content.follow_status = !content.follow_status;
                    }
                });
            }
        },
        // update save content
        saveContent(state, data) {
            if (data.source_content == 'contents') {
                state.contents.forEach(content => {
                    if (content.id == data.content_id) {
                        content.is_save = !content.is_save;
                    }
                });
            }
            if (data.source_content == 'otherUserContents') {
                state.otherUserContents.forEach(content => {
                    if (content.id == data.content_id) {
                        content.is_save = !content.is_save;
                    }
                });
            }
        },
        // remove save content
        removeSaveContent(state, id) {
            state.savedContents = state.savedContents.filter(content => content.content_id != id);
        },
        // set notifications
        setNotifications(state, data) {
            state.notifications = data;
        },
        // add notifications
        updateNotifications(state, data) {
            state.notifications.push(...data);
        },
        // set users
        setUsers(state, data) {
            state.users = data;
        },
        updateUsers(state, data) {
            state.users.push(...data);
        }
    },
    actions: {
        getContent({ commit }) {
            axios.get(`contents?page=1`)
                .then(response => {
                    if (response.data.status == 200) {
                        commit('setContents', response.data.data)
                    }
                });
        },

        // sent react
        sendReact({ commit }, data) {
            commit('updateReactStatus', { 'index': data.index || data.index == 0 ? data.index : null, 'source_content': data.source_content });
            // console.log(data.index || data.index == 0 ? data.index : null);
            axios.post(`react/${data.content_id}`);
        },

        // fetch saved contents
        getSavedContents({ commit }) {
            axios.get('saved-content')
                .then(response => {
                    if (response.data.status == 200) {
                        commit('setSavedContent', response.data.data);
                    }
                });
        },
    }
}