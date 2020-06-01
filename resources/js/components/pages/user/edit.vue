<template>
    <userForm :model="model" :error="error" :errors="errors" @update="submit"></userForm>
</template>

<script>
    import userForm from './form'
    import {mapActions, mapGetters, mapMutations} from "vuex";
    export default {
        name: "EditUser",
        data:function(){
            return {
                model:{},
                error:false,
                errors:{}
            }
        },
        computed:{
            ...mapGetters(['allUsers']),
        },
        methods:{
            ...mapActions(['fetchUsers']),

            fetchUser(userId){
                this.axios.get(`/users/${userId}`)
                    .then((response)=>{
                        this.model = response.data.data;
                    })
                    .catch((error)=>{console.log(error.response.data.message)})
            },

            submit(model) {
                this.axios.patch(`/users/${model.id}`,model )
                    .then(response => {
                        this.$router.push({name: 'Users'})
                    })
                    .catch(error => {
                        this.error = true
                        this.errors = error.response.data.errors || {}
                    });
            }
        },

        async mounted(){
            this.fetchUsers;
            this.fetchUser(this.$route.params.id);
        },

        components:{
            userForm,
        },
    }
</script>

<style scoped>

</style>
