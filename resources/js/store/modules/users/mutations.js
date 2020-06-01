export default {
    // the first parameter always
    setAllUsers(state,users){
        return state.users = users;
    },
    createUser(state,user){
        return state.users.unshift(user);
    },
    updateUser(state,newUser){
        let index = state.users.findIndex((user => user.id == newUser.id));
        state.users[index] = newUser;
    },
    deleteUser(state,userId){
        state.users =  state.users.filter(function(user) {
            return user.id != userId;
        });
    }
}
