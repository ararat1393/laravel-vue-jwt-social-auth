
# Laravel JWt AUTH

## Installation

## Setup Vue Js
   Vue Js is already part of a Laravel application. You just need to execute the following command in your terminal and install the required dependencies.
   
```bash
npm install
```
After executing this command, you will see the node_modules folder in your Laravel application. This folder contains all the necessary node modules. You can read here the full process of Vue Js setup.

Then open your “resource/views/welcome.blade.php” file and replace the file content.
```html
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- Fonts -->
  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div id="app">
    <index></index>
  </div>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
```

## Create Default Component
   Create a default component at “resources/js/App.vue”. Open Index.vue and add the following code snippet into the file. It’s like a default component, which is loaded first then other components can be added in the content div as per the app functionality.

```vue
<template>
    <div id="main">
        <header id="header">
            <h1>Laravel Vue SPA with JWT Authentication</h1>
        </header>
        <div id="content">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
  export default {
    data() {
      return {
        //
      }
    },
    components: {
        //
    }
  }
</script>
```
## Setup Vue Js Packages for Authentication
   Now, the basic Vue Js setup is ready with our Laravel application. Time to install the necessary package “websanova/vue-auth” and some of its dependencies such as “vue-router” , “vue-axios”, “axios“, and “es6-promise” for our authentication with Laravel JWT.
   
   You can use the following command to install all these packages.
```bash
npm i @websanova/vue-auth vue-router vue-axios axios es6-promise
```
After successful installation, you need to create an auth configuration file at “resources/js/config/auth.js” and add the following code.
```js
import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'
/**
 * Authentication configuration, some of the options can be override in method calls
 */
const auth = {
    auth: bearer,
    http: axios,
    router: router,
    tokenDefaultName: 'laravel-jwt-auth',
    tokenStore: ['localStorage'],
    rolesVar: 'role',

    // API endpoints used in Vue Auth.
    registerData: {
        url: 'auth/register',
        method: 'POST',
        redirect: '/login'
    },
    loginData: {
        url: 'auth/login',
        method: 'POST',
        redirect: '',
        fetchUser: true,
        staySignedIn: true,
    },
    logoutData: {
        url: 'auth/logout',
        method: 'POST',
        redirect: '/',
        makeRequest: true
    },
    fetchData: {
        url: 'auth/user',
        method: 'GET',
        enabled: true
    },
    refreshData: {
        url: 'auth/refresh',
        method: 'GET',
        enabled: true,
        interval: 30
    }
}
export default auth

```
## Create Route File
   Create a file `./config/routes.js` under the resources directory and add the following code.
```js
import VueRouter from 'vue-router';

import Home from '../components/unauthorized/HomeComponent.vue';
import Login from '../components/unauthorized/LoginComponent.vue';
import Register from '../components/unauthorized/RegisterComponent.vue';
import Users from '../components/lists/Users.vue';
import ViewUser from "../components/pages/user/view";
import EditUser from "../components/pages/user/edit";
import CreateUser from "../components/pages/user/create";

const routes = [
    {
        name: 'Home', path: '/', component: Home,
        beforeEnter: (to, from, next) => {
            if( window.Vue.auth.check()){
                let path = window.Vue.auth.check('-1') ? '/dashboard' : '/profile';
                return next(path)
            }
            return next();
        },
    },

    { name: 'login', path: '/login', component: Login, meta:{ auth:false} },
    // { name: 'login', path: '/login/:token/',component: Login, meta:{ auth:false } },

    { name: 'register', path: '/register', component: Register, meta:{ auth:false } },

    // ADMIN ROUTES
    { name: 'Dashboard' , path: '/dashboard',
        meta: {
            auth: {roles: ['-1'], redirect: {name: 'login'}, forbiddenRedirect: '/403'}
        }
    },

    { name: 'Profile', path: '/profile',
        meta: {
            auth: {
                roles: ['0','1'],
                forbiddenRedirect: '/dashboard'
            },
        }
    },

    { name: 'Users', path: '/users', component: Users,
        meta: {
            auth: {
                roles: ['-1'],
                forbiddenRedirect: '/403'
            },
        }
    },

    { name: 'user.view',  path: '/users/view/:id',component: ViewUser, meta:{ auth:true } },
    { name: 'user.edit',  path: '/users/edit/:id',component: EditUser, meta:{ auth:true } },
    { name: 'user.create',  path: '/users/create',component: CreateUser, meta:{ auth:true } },
];
const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
});

router.beforeEach((to, from, next) => {
    if (!to.matched.length) {
        next(from.path);
    } else {
        next();
    }
});
export default router

```
In this file, we will be defined as the required routes. The “meta” parameter is used to define the access rules for each route.

Each of the routes contains path, name, component and meta parameter.

“path” is a route path, which accesses in our application.
“name” is a route name, we use route name to call the specific route.
“component” is loaded at a time for a route called.
“meta” is used to define the access rules for each route.
auth: undefined, it’s a public route.
auth: true, it’s available only for the authenticated users and auth: false, is available only for the unauthenticated users.



## Update app.js
   Open app.js located at “resources/js” and update the following code snippet.
```js
require('./bootstrap');
require('./helper/functions');

import Vue from 'vue'
import App from './App.vue';
import axios from 'axios'
import VueAxios from 'vue-axios';
import VueAuth from '@websanova/vue-auth'
import VueRouter from 'vue-router'

import auth from './config/auth'
import router from './config/routes'
import vuetify from './config/vuetify' // path to vuetify export

// Set Vue globally
window.Vue = Vue

// Set Vue router
Vue.router = router
Vue.use(VueRouter)

Vue.use(VueAxios, axios);
axios.defaults.baseURL = process.env.MIX_BASE_URL;

Vue.use(VueAuth, auth)

const app = new Vue({
    el: '#app',
    router,
    vuetify,
    render: h => h(App),
});

```

## Create Components
Now, time to create required components such as home, register, login, dashboard, and etc as given below.

## Login Component
```vue
<template>
    <div class="row justify-content-center">
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
                                <a class="btn btn-link" href="">Forgot Your Password</a>
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
                    password: '',
                },
                error:false,
                message:false,
                success: false,
                redirectToProfile:'Profile',
                redirectToDashboard :'Dashboard',
                isProcessing: false,
            };
        },
        methods: {
            login() {
                var redirect = this.$auth.redirect();
                var self = this;
                this.$auth.login({
                    data: {
                        email: self.user.email,
                        password: self.user.password
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

```
## Register Component
```vue
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

```
## Update Laravel “.env”
As you notify, I’m using “MIX_APP_URL” variable for Axios base URL. But before using this you need to add this variable in your “Laravel .env” file.
```bash
// VARIABLE USE IN VUR APP

MIX_APP_URL="${APP_URL}"

// Laravel Default APP_URL VARIABLE

APP_URL=http://127.0.0.1:8000
```

## Test Laravel Vue App
Open the first terminal and execute the following artisan command.

```bash
php artisan serve --port=9090
```
In the second terminal, we need to execute the following NPM command.

```bash
npm run watch
```
The above mention “npm run watch” command will detect all the file changes and compile those file immediately. You just need to refresh the browser.

Let’s time to check the working of our Laravel Vue application.
