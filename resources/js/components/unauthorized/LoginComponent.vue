<template>
    <div class="row justify-content-center" v-if="!isSocial">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Login</div>

                <div class="card-body">
                    <form method="POST" autocomplete="off" @submit.prevent="login" v-if="!success" >
                        <div class="text-center">
                            <span class="text-danger" v-if="message" role="alert"><strong>{{ message }}</strong></span>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" v-model="user.email" value="user.email"  autocomplete="email" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" v-model="user.password" value="user.password"  autocomplete="current-password">
                            </div>
                        </div>
                        <div class="form-group row text-center">
                            <div class="col-md-6 offset-md-4">
                                <!--Facebook-->
                                <a @click="AuthProvider('facebook')" class="btn-floating btn-lg btn-fb" type="button" role="button"><i class="fab fa-facebook-f"></i></a>
                                <!--Twitter-->
                                <a @click="AuthProvider('twitter')" class="btn-floating btn-lg btn-tw" type="button" role="button"><i class="fab fa-twitter"></i></a>
                                <!--Google +-->
                                <a @click="AuthProvider('google')" class="btn-floating btn-lg btn-gplus" type="button" role="button"><i class="fab fa-google-plus-g"></i></a>
                                <!--Github-->
                                <a @click="AuthProvider('github')" class="btn-floating btn-lg btn-git" type="button" role="button"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" v-bind:checked="{'checked':user.rememberMe,'':user.rememberMe}" v-model="user.rememberMe" id="remember">
                                    <label class="form-check-label" for="remember">Remember Me</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                                <router-link :to="{name: 'reset'}" class="btn btn-link">Forgot Your Password</router-link>
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
                    email: '',
                    password: ''
                },
                error:false,
                message:false,
                success: false,
                redirectToProfile:'Profile',
                redirectToDashboard :'Dashboard',
                isProcessing: false,
                isSocial:false,
            };
        },
        created(){
            this.hasSocialLogin();
        },
        methods: {
            login() {
                var redirect = this.$auth.redirect();
                var self = this;
                this.$auth.login({
                    data:{
                        email:self.user.email,
                        password:self.user.password
                    },
                    success: function() {
                        const page = this.$auth.user().roles == -1
                                ? self.redirectToDashboard
                                : self.redirectToProfile;
                        let redirectTo = redirect && redirect.from ? redirect.from.name : page;
                        this.$router.push({name: redirectTo})
                            .catch(error => { console.log(error) });
                    },
                    error: function(error) {
                        self.error = true
                        self.isSocial = false;
                        self.message = error.response.data.message
                    },
                    rememberMe: true,
                    fetchUser: true
                })
            },
            AuthProvider:function(provider){
                this.isProcessing = true;
                this.error = {};
                this.axios.get(`auth/social/${provider}`)
                    .then((response) => {
                        if(response.data.error){
                            this.error = err.response.data.error;
                        } else if(response.data.redirectUrl){
                            window.location.href = response.data.redirectUrl;
                        }
                    })
                    .catch((err) => {
                        if(err.response.data.error){
                            this.error = err.response.data.error;
                        }
                        this.isProcessing = false;
                    });
                this.isProcessing = false;
            },
            hasSocialLogin: function () {
                let token = this.$route.params.token;
                if( token ){
                    var self = this;
                    this.isSocial = true;
                    this.axios.get(`social-user/${token}`)
                        .then((response) => {
                            let socialUser = response.data.data;
                            if( socialUser ){
                                self.user.email = socialUser.user.email;
                                self.user.password = socialUser.password;
                                self.login();
                            }
                        })
                        .catch((err) => {
                            console.log(err.response.data.error);
                        });
                }
            }
        },
    };
</script>

<style scoped>
    .btn-floating {
        position: relative;
        z-index: 1;
        display: inline-block;
        padding: 0;
        margin: 10px;
        overflow: hidden;
        vertical-align: middle;
        cursor: pointer;
        border-radius: 50%;
        -webkit-box-shadow: 0 5px 11px 0 rgba(0,0,0,0.18), 0 4px 15px 0 rgba(0,0,0,0.15);
        box-shadow: 0 5px 11px 0 rgba(0,0,0,0.18), 0 4px 15px 0 rgba(0,0,0,0.15);
        -webkit-transition: all .2s ease-in-out;
        transition: all .2s ease-in-out;
        width: 47px;
        height: 47px;
    }
    .btn-floating.btn-lg {
        width: 61.1px;
        height: 61.1px;
        font-size: 1.25rem;
        line-height: 1.5;
    }
    .btn-fb {
        color: #fff;
        background-color: #3b5998 !important;
    }
    .btn-floating i {
        display: inline-block;
        width: inherit;
        color: #fff;
        text-align: center;
        font-size: 1.625rem;
        line-height: 61.1px;
        font-family: "Font Awesome 5 Brands";
    }

    .btn-tw {
        color: #fff;
        background-color: #55acee !important;
    }
    .btn-gplus {
        color: #fff;
        background-color: #dd4b39 !important;
    }
    .btn-git {
        color: #fff;
        background-color: #333 !important;
    }
</style>
