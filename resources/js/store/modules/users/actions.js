import axios from 'axios';

export default {
    // after get data from backend we should save it into state by help mutation
    fetchUsers({commit},page = 1) {
        return new Promise(async (resolve, reject) => {
            await axios.get('/users?page='+page)
                .then((response) => {
                    commit("setAllUsers", response.data.data);
                    resolve(response);
                })
                .catch((error) => {
                    reject(error);
                });
        });
    },

    deleteUser({commit},userId){
        return new Promise(async (resolve, reject) => {
            await axios.delete(`/users/${userId}`)
                .then((response) => {
                    commit("deleteUser", userId);
                    resolve(response);
                })
                .catch((error) => {
                    reject(error);
                });
        });
    }
}
