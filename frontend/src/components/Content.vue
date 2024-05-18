<template>
    <div>
        <div class="md:pt-5 pt-1 mx-auto md:w-[70%] md:px-0 px-2 w-[100%] mt-5">
            <div v-if="contentContainer.length != 0">
                <div v-for="(content, index) in contentContainer" :key="index"
                    class="px-4 py-4 shadow-md bg-gray-100 mb-5 rounded-md">
                    <!-- content header -->
                    <div class="flex relative">
                        <img :src="content.user_photo ? profile_img_path + content.user_photo : 'https://ui-avatars.com/api/?name=' + content.user_name"
                            alt="" class="rounded-full w-14">
                        <div class="ms-3 mt-1">
                            <router-link :to="'user-profile/' + content.user_id" class="font-semibold mb-0 block">
                                {{ content.user_name }}
                            </router-link>
                            <small class="p-0">{{ content.created_at }}</small>
                        </div>

                        <img @click="openOption" class="absolute top-2 right-2 hover:cursor-pointer more" id="more"
                            src="@/assets/more.svg" alt="">

                        <!-- more options -->
                        <div id="options"
                            class="options border hidden border-gray-300 rounded-lg bg-gray-50 absolute right-2 top-8 shadow-md">
                            <ul class="py-2">

                                <!-- save and undave -->
                                <li v-if="!content.is_save"
                                    @click="save({ 'content_id': content.id, 'source_content': source_content })"
                                    class="flex option hover:cursor-pointer hover:bg-gray-100 px-3 py-1">
                                    <LoadingAnimation class="me-2 block" :class="{ 'hidden': saveNotLoading }" />
                                    <span :class="{ 'hidden': !saveNotLoading }">
                                        <i class="fa-regular fa-bookmark me-3"></i></span>
                                    Save
                                </li>

                                <li v-if="content.is_save"
                                    @click="save({ 'content_id': content.id, 'source_content': source_content })"
                                    class="flex option hover:cursor-pointer hover:bg-gray-100 px-3 py-1">
                                    <LoadingAnimation class="me-2 block" :class="{ 'hidden': saveNotLoading }" />
                                    <span :class="{ 'hidden': !saveNotLoading }">
                                        <i class="fa-solid fa-bookmark text-blue-600 me-3"></i>
                                    </span>
                                    Unsave
                                </li>
                                <!-- end save and unsave -->

                                <!-- follow and unfollow -->
                                <li v-if="!content.follow_status && content.user_id != authUser.id"
                                    @click="follow({ 'user_id': content.user_id, 'source_content': source_content })"
                                    class="flex option hover:cursor-pointer hover:bg-gray-100 px-3 py-1">
                                    <LoadingAnimation class="me-2 block" :class="{ 'hidden': isNotLoading }" />
                                    <span :class="{ 'hidden': !isNotLoading }"><i
                                            class="fa-solid fa-plus me-3"></i></span>
                                    Follow
                                </li>
                                <li v-if="content.follow_status && content.user_id != authUser.id"
                                    @click="follow({ 'user_id': content.user_id, 'source_content': source_content })"
                                    class="flex option hover:cursor-pointer hover:bg-gray-100 px-3 py-1 text-blue-600">
                                    <LoadingAnimation class="me-2 block" :class="{ 'hidden': isNotLoading }" />
                                    <span :class="{ 'hidden': !isNotLoading }"><i
                                            class="fa-solid fa-check me-3"></i></span>
                                    Unfollow
                                </li>
                                <!-- end follow and unfollow -->

                                <router-link :to="'user-profile/' + content.user_id"
                                    class="option hover:cursor-pointer hover:bg-gray-100 px-3 py-1">
                                    <i class="fa-solid fa-user-large me-1"></i>
                                    View profile
                                </router-link>
                                <li v-if="content.user_id != authUser.id"
                                    class="option hover:cursor-pointer hover:bg-gray-100 px-3 py-1 text-red-600">
                                    <i class="fa-solid fa-ban me-1"></i>
                                    Block
                                </li>

                                <li :id="content.id" v-if="content.user_id == authUser.id"
                                    @click="deleteContent(content.id, $event)"
                                    class="option hover:cursor-pointer hover:bg-gray-100 px-3 py-1 text-red-600">
                                    <i class="fa-solid fa-trash-can me-1"></i>
                                    Delete
                                </li>
                            </ul>
                        </div>
                        <!-- more options -->
                    </div>
                    <!-- end content header -->

                    <div class="mt-5 max-h-[200px] overflow-hidden">
                        <h3 class="font-semibold mb-2 text-lg">{{ content.title }}</h3>
                        <p class="">{{ content.content }}</p>
                    </div>
                    <button class="text-blue-600" @click="seemore">see more...</button>
                    <button class="text-blue-600 hidden" @click="seeless">see less...</button>

                    <div class="mt-2 max-h-[500px] overflow-hidden">
                        <img id="myImg" @click="viewImg" :src="content_img_path + content.content_image" class="mx-auto"
                            alt="">
                    </div>

                    <!-- modal image -->
                    <div id="myModal" class="modal">
                        <span class="close">&times;</span>
                        <img class="modal-content" id="img01">
                    </div>
                    <!-- end modal image -->

                    <!-- <div v-show="content.video_link" class="mt-3">
                            <iframe class="mx-auto w-[100%]" height="315" :src="content.video_link" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                        </div> -->
                    <!-- End Slider -->

                    <hr>
                    <!-- footer -->
                    <div class="mt-3 flex">
                        <div class="me-3">
                            <i class='bx bx-heart text-xl hover:cursor-pointer' v-show="content.react_status == false"
                                @click="sendReact({ 'content_id': content.id, 'index': index, 'source_content': source_content })"></i>
                            <i class='bx bxs-heart text-red-600 text-xl hover:cursor-pointer'
                                @click="sendReact({ 'content_id': content.id, 'index': index, 'source_content': source_content })"
                                v-show="content.react_status == true"></i>
                            {{ content.react }}
                        </div>
                        <div @click="seeComment(content.id, content.user_id, content.user_name)">
                            <i class='bx bx-comment text-xl hover:cursor-pointer'></i>
                            <span class="text-gray-700">
                                {{ content.comment_count }}
                            </span>
                        </div>
                    </div>
                    <!-- end footer -->
                </div>
            </div>
            <div v-else>
                <p class="text-center text-xl font-semibold">No contents</p>
            </div>
        </div>

        <!-- comments modal box -->
        <div class="hidden w-[100%] h-[100%] px-2 z-10 md:top-0 top-3 right-0" style="background-color: rgba(0,0,0,0.5)"
            id="comment_modal">
            <div class="bg-gray-50 md:w-[60%] h-[550px] mx-auto mt-10 rounded-md shadow overflow-hidden">
                <div class="border border-bottom-gray-300 bg-gray-100 relative">
                    <h1 class="text-center font-semibold text-xl my-2">{{ content_user }}'s Post</h1>
                    <div @click="closeCommentBox"
                        class="absolute top-1 right-2 text-2xl hover:cursor-pointer hover:bg-gray-300 px-2 pb-1 rounded">
                        &times;</div>
                </div>
                <div @scroll="fetchMoreComment" class="px-3 overflow-scroll h-[75%] scrollbar-hide mt-2"
                    id="comment_container">
                    <LoadingAnimation class="text-center" :class="{ 'hidden': !moreCommentLoading }" />
                    <div v-if="comments[content_id] != undefined">
                        <div v-for="comment in comments[content_id]" :key="comment.id">
                            <div class="flex mb-2">
                                <img :src="comment.user.profile_photo_path ? profile_img_path + comment.user.profile_photo_path : 'https://ui-avatars.com/api/?color=7F9CF5&background=EBF4FF&name=' + comment.user.name"
                                    alt="" class="w-10 h-10 rounded-full">
                                <div
                                    class="bg-gray-200 ms-2 rounded-md pb-4 pl-2 pr-8 relative max-w-[90%] break-words">
                                    <i @click="commentOption"
                                        class="fa-solid fa-ellipsis-vertical absolute top-1 right-2 text-gray-600 more cursor-pointer"></i>
                                    <router-link :to="'user-profile/' + comment.user.id"
                                        class="font-semibold text-sm">{{
                                            comment.user.name }}</router-link>
                                    <p class="text-sm">{{ comment.comment }}</p>
                                    <p class="text-[8px] text-gray-500 absolute right-1 buttom-0">{{ comment.updated_at
                                        }}
                                    </p>
                                    <!-- comment option -->
                                    <div class="absolute shadow bg-gray-50 top-5 right-[-10px] hidden options rounded">
                                        <div v-if="comment.user_id == authUser.id || authUser.id == content_creator"
                                            @click="deleteComment(comment.id)"
                                            class='bg-gray-100 option px-6 py-1 text-red-600 text-xs hover:bg-gray-200 hover:cursor-pointer rounded'>
                                            <i class="fa-solid fa-trash me-2"></i>Delete
                                        </div>
                                        <div v-if="comment.user_id == authUser.id" @click="editComment(comment.id)"
                                            class='bg-gray-100 option px-6 py-1 text-blue-600 text-xs hover:bg-gray-200 hover:cursor-pointer mt-1 rounded'>
                                            <i class="fa-solid fa-pen me-2"></i>Edit
                                        </div>
                                    </div>
                                    <!-- end comment option -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <img src="../assets/comment.svg" alt="" class="mx-auto mt-16 w-40">
                        <p class="text-xl text-center text-gray-600">No comments here.</p>
                    </div>
                </div>
                <!-- comment text box -->
                <div class="px-3 flex align-middle justify-center pt-3 bg-gray-100 h-[25%]">
                    <textarea class="w-[100%] h-[50px] border-2 rounded-lg px-1 border-blue-500 me-3"
                        placeholder="comment" v-model="comment"></textarea>
                    <i class='bx bxs-send text-2xl mt-2 text-gray-600' @click="send(send_status)"></i>
                </div>
                <!-- end comment text box -->
            </div>
        </div>
        <!-- end comments modal box -->
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import SideBar from '../components/SideBar.vue';
import Swal from 'sweetalert2';
import LoadingAnimation from '@/components/LoadingAnimation.vue';
import axios from 'axios';

export default {
    components: {
        SideBar, LoadingAnimation
    },
    props: ['contentContainer', 'source_content'],
    data() {
        return {
            content_creator: null,

            content_id: null,
            comment: null,
            react_status: null,
            send_status: 'send',
            current_comment_id: null,
            content_user: null,

            open: false,
            isNotLoading: true,
            saveNotLoading: true,
            moreCommentLoading: false,

            last_page: 1,
            comment_count: 1,
            paginate: 10,
            oldScrollHeight: null,
        }
    },
    methods: {
        // all action is in the content module
        ...mapActions(['getContent', 'getComment', 'sendComment', 'sendReact']),
        seemore(e) {
            const targetElement = e.target.parentElement.children[1];
            targetElement.classList.remove('max-h-[200px]');

            // hide seemore button
            e.currentTarget.classList.add('hidden');
            // show seeless
            e.target.parentElement.children[3].classList.remove('hidden');
        },
        seeless(e) {
            const targetElement = e.target.parentElement.children[1];
            targetElement.classList.add('max-h-[200px]');

            // show seemore button
            e.currentTarget.classList.add('hidden');
            // hide seeless
            e.target.parentElement.children[2].classList.remove('hidden');
        },
        viewImg(e) {
            // Get the modal
            var modal = document.getElementById("myModal");

            // Get the image and insert it inside the modal - use its "alt" text as a caption
            var modalImg = document.getElementById("img01");

            modal.style.display = "block";
            modalImg.src = e.target.src;

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modal.style.display = "none";
            }
        },
        closeCommentBox() {
            const comment_modal = document.getElementById('comment_modal');
            comment_modal.classList.remove('fixed');
            comment_modal.classList.add('hidden');
        },
        fetchComment(content_id) {
            if (this.comments.hasOwnProperty(content_id)) { return }
            this.moreCommentLoading = true;
            this.$axios.get(`comments/${content_id}?paginate=${this.paginate}`)
                .then(response => {
                    if (response.data.status == 200) {
                        this.last_page = response.data.last_page;
                        axios.get(`comments/${content_id}?page=${this.last_page}&paginate=${this.paginate}`)
                            .then(response => {
                                if (response.data.status == 200) {
                                    this.$store.commit('setComment', response.data.data);
                                    this.moreCommentLoading = false;
                                    if (response.data.data.length < this.paginate) {
                                        this.last_page -= 1;
                                        if (this.last_page <= 0) { return };
                                        axios.get(`comments/${content_id}?page=${this.last_page}&paginate=${this.paginate}`)
                                            .then(response => {
                                                if (response.data.status == 200) {
                                                    let key = response.data.data[0].content_id;
                                                    this.$store.state.Contents.comments[key].unshift(...response.data.data);
                                                    setTimeout(() => {
                                                        const commentContainer = document.getElementById('comment_container');
                                                        this.oldScrollHeight = commentContainer.scrollHeight;
                                                        commentContainer.scrollTop = commentContainer.scrollHeight;
                                                    }, 10);
                                                    this.moreCommentLoading = false;
                                                }
                                            });
                                    }
                                }
                            });
                    }
                });
        },
        // open comment modal and fetch comment data in current content
        seeComment(content_id, content_creator, content_user) {
            // open comment modal
            const comment_modal = document.getElementById('comment_modal');
            comment_modal.classList.remove('hidden');
            comment_modal.classList.add('fixed');
            this.content_id = content_id;
            // fetch comment data in current content
            this.fetchComment(content_id);
            this.content_user = content_user;
            this.content_creator = content_creator;
        },
        fetchMoreComment(e) {
            if (e.target.scrollTop <= 0) {
                this.last_page -= 1;
                if (this.last_page <= 0) { return };
                this.moreCommentLoading = true;
                axios.get(`comments/${this.content_id}?page=${this.last_page}&paginate=${this.paginate}`)
                    .then(response => {
                        if (response.data.status == 200) {
                            let key = response.data.data[0].content_id;
                            this.$store.state.Contents.comments[key].unshift(...response.data.data);
                            setTimeout(() => {
                                const commentContainer = document.getElementById('comment_container');
                                commentContainer.scrollTop = commentContainer.scrollHeight - this.oldScrollHeight;
                                this.oldScrollHeight = commentContainer.scrollHeight;
                            }, 10);
                            this.moreCommentLoading = false;
                        }
                    });
            }
        },
        // send comment
        send(send_status) {
            if (send_status == 'send') {
                const commentContainer = document.getElementById('comment_container')
                axios.post(`comments/${this.content_id}`, {
                    'comment': this.comment,
                }).then(response => {
                    if (response.data.status == 200) {
                        this.$store.commit('setNewComment', response.data.data);
                        setTimeout(() => {
                            commentContainer.scrollTop = commentContainer.scrollHeight;
                        }, 10);
                    }
                });
            }

            if (send_status == 'edit') {
                axios.put(`comments/${this.current_comment_id}?comment=${this.comment}`)
                    .then(response => {
                        if (response.data.status == 200) {
                            this.$store.state.Contents.comments[this.content_id].forEach(comment => {
                                if (comment.id == this.current_comment_id) {
                                    comment.comment = response.data.data.comment;
                                }
                            });
                        }
                    });
            }
            // this.sendComment({ 'comment': comment, 'content_id': content_id });
            this.send_status = 'send';
            this.comment = null;
        },
        openOption(e) {
            let targetTag = e.target.parentElement.children[3];
            targetTag.classList.toggle('hidden');
        },
        // follow user
        follow(data) {
            this.isNotLoading = false;
            this.$axios.post(`follow/${data.user_id}`)
                .then(response => {
                    if (response.data.status == 200) {
                        this.$store.commit('updateFollowInHome', data);
                        this.isNotLoading = true;
                    }
                });
        },
        // save content
        save(data) {
            this.saveNotLoading = false;
            this.$axios.post('save-content', { 'content_id': data.content_id })
                .then(response => {
                    if (response.data.status == 200) {
                        this.$store.commit('saveContent', data);
                        this.saveNotLoading = true;
                    }
                })
        },
        // toggle option in comment
        commentOption(e) {
            let targetTag = e.target.parentElement.children[4];
            targetTag.classList.toggle('hidden');
        },
        deleteComment(comment_id) {
            axios.delete(`comments/${comment_id}`)
                .then(response => {
                    if (response.data.status == 200) {
                        let comments = this.comments[this.content_id];
                        let contents = this.contents;
                        this.$store.state.Contents.comments[this.content_id] = comments.filter(element => {
                            return element.id != comment_id;
                        });
                        contents.forEach(e => {
                            if (e.id == this.content_id) {
                                if (e.comment_count == 0) { return };
                                e.comment_count -= 1;
                            }
                        });
                    }
                });

        },
        editComment(comment_id) {
            let comments = this.comments[this.content_id];
            comments.forEach(comment => {
                if (comment.id == comment_id) {
                    this.comment = comment.comment;
                    this.send_status = 'edit';
                    this.current_comment_id = comment_id;
                }
            });
        },
        deleteContent(content_id, e) {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete it!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete!",
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`content/${content_id}`)
                        .then(response => {
                            if (response.data.status == 200) {
                                let contents = this.$store.state.Contents.contents;
                                this.$store.state.Contents.contents = contents.filter(content => {
                                    return content.id != e.target.id
                                });
                                Swal.fire({
                                    title: "Delete!",
                                    text: "Your content has been deleted.",
                                    icon: "success"
                                });
                            }
                        });
                }
            });
        }
    },
    computed: {
        ...mapGetters(['contents', 'comments', 'otherUserInfo', 'authUser']),
    },
    mounted() {
        document.addEventListener('click', (e) => {
            if (!e.target.matches('.option') && !e.target.matches('.more')) {
                let targtClass = document.getElementsByClassName('options');

                for (let i = 0; i < targtClass.length; i++) {
                    targtClass[i].classList.add('hidden');
                }
            }
        });
    },
}
</script>

<style scoped>
.modal {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 999;
    /* Sit on top */
    padding-top: 100px;
    /* Location of the box */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.9);
    /* Black w/ opacity */
}

.modal-content {
    margin: 0 auto;
    display: block;
    width: 80%;
}

@media only screen and (max-width: 700px) {
    .modal-content {
        width: 100%;
    }
}

.modal-content {
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {
        -webkit-transform: scale(0)
    }

    to {
        -webkit-transform: scale(1)
    }
}

@keyframes zoom {
    from {
        transform: scale(0)
    }

    to {
        transform: scale(1)
    }
}

.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}
</style>