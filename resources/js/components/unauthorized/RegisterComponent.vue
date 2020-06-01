<template>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form method="POST" autocomplete="off" @submit.prevent="register">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" :class="['form-control', { 'is-invalid': error && errors.name }]" value="user.name" v-model="user.name" autocomplete="name" autofocus>
                                    <span class="invalid-feedback" role="alert" v-if="error && errors.name"><strong>{{ errors.name[0] }}</strong></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="email" type="text" :class="['form-control', { 'is-invalid': error && errors.email }]" v-model="user.email" value="user.email"  autocomplete="email">
                                    <span class="invalid-feedback" role="alert" v-if="error && errors.email"><strong>{{ errors.email[0] }}</strong></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" :class="['form-control', { 'is-invalid': error && errors.password }]" v-model="user.password" value="user.password"  autocomplete="new-password">
                                    <span class="invalid-feedback" role="alert" v-if="error && errors.password"><strong>{{ errors.password[0] }}</strong></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" :class="['form-control', { 'is-invalid': error && errors.password_confirmation }]" v-model="user.password_confirmation" value="user.password_confirmation"  autocomplete="new-password">
                                    <span class="invalid-feedback" role="alert" v-if="error && errors.password_confirmation"><strong>{{ errors.password_confirmation[0] }}</strong></span>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        name: "RegisterComponent",
        data() {
            return {
                user:{
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation:""
                },
                error: false,
                errors: {}
            };
        },
        methods: {
            register(){
                this.$auth.register({data: this.user})
                    .then(response => {
                        console.log(response)
                        this.$router.push({name: 'login', params: {successRegistrationRedirect: true}})
                    })
                    .catch(error => {
                        console.log(error.response)
                        this.error = true
                        this.errors = error.response.data.errors || {}
                    });
            }
        }
    };
</script>

<style scoped>

</style>
