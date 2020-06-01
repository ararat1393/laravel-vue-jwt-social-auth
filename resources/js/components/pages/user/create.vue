<template>
    <userForm :model="model" :error="error" :errors="errors" @create="submit"></userForm>
</template>

<script>
    import userForm from './form'
    export default {
        data(){
            return{
                model:{
                    name:'',
                    surname:'',
                    email:'',
                    phone:'',
                    password:'',
                    cover_photo:'',
                    status:0,
                    gender:1,
                    role:0,
                    newRecord:true
                },
                errors:{},
                error:false,
            }
        },
        methods:{
            submit(model) {
                let self = this;
                this.axios.post('/users',model)
                    .then(response => {
                        self.createUser(response.data.user)
                        self.$router.push({name: 'Users'})
                    })
                    .catch(error => {
                        self.error = true
                        self.errors = error.response.data.errors || {}
                    });
            },
        },
        components:{
            userForm,
        },
    }
</script>

<style scoped>

</style>
