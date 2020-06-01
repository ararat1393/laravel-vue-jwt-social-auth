<template>
    <div class="main">
        <header id="header">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <router-link :to="{name: 'home'}" class="navbar-brand">Laravel + JWT + Vue JS</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item" v-for="(route, key) in routes" v-bind:key="route.path">
                            <router-link :to="{ name : route.path }" :key="key" class="nav-link">{{route.name}}</router-link>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#" @click.prevent="$auth.logout()">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="content" style="position: fixed;width: 100%">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        props:{
            routes:{
                type:Array,
                required:true
            }
        },
        name: "Header"
    }
</script>

<style scoped>

</style>
