import { createStore } from 'vuex';
import { findIndexById } from './Functions/helpers';
export default createStore({
    state: {
        shouldScroll: false,
        userIsScrollingUp: false,
        gallary: [],
        currentIndex: null,
        showGallary: false,
        isLargeScreen: window.innerWidth > 1024,
        showDetails: false,
        users: null,
        activeDetailsChat: null,
    },
    mutations: {
        setShouldScroll(state, value) {
            state.shouldScroll = value;
        },
        setUserIsScrollingUp(state, value) {
            state.userIsScrollingUp = value;
        },
        setGallary(state, value) {
            state.gallary.push(value);
        },
        setCurrentIndex(state,value){
            if(value !== null){
                let index = findIndexById(state.gallary,value);
                index = index === -1 ? value : index
                state.currentIndex = index;
            } else {
                state.currentIndex = value;
            }
        },
        setShowGallary(state, value) {
            state.showGallary = value;
        },
        setIsLargeScreen(state, value) {
            state.isLargeScreen = value;
        },
        setShowDetails(state, value) {
            state.showDetails = value;
        },
        setUsers(state, value) {
            state.users = value;
        },
        setActiveDetailsChat(state, value) {
            state.activeDetailsChat = value;
        },
    },
    actions: {
        triggerScroll({ commit }) {
            commit('setShouldScroll', true);
        },
        resetScroll({ commit }) {
            commit('setShouldScroll', false);
        },
        resetCurrentIndex({ commit }) {
            commit('setCurrentIndex', null);
        },
        resetActiveChat({ commit }) {
            commit('setActiveDetailsChat', null);
        },
    },
    getters: {
        getGallary: state => state.gallary,
        getCurrentIndex: state => state.currentIndex,
        getShowGallary: state => state.showGallary,
        getShowDetails: state => state.showDetails,
        getUsers: state => state.users,
        getActiveDetailsChat: state => state.activeDetailsChat,
    }
});
