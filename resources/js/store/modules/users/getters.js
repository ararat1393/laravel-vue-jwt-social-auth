export default {
    // the first parameter always
    allUsers: function (state) {
        return state.users;
    },
    usersCount:function (state) {
        return state.users.length;
    }
}
