import Vue from 'vue';
import Vuex from 'vuex';

import users from './modules/users/store'

Vue.use(Vuex);

//=======vuex store start===========
const store = new Vuex.Store({
    state :{
    },
    mutations:{
    },
    actions:{

    },
    getters:{

    },
    modules:{
        users,
    },
});

//=======vuex store end===========
export default store;
