<template>
    <aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-dark sidenav-active-rounded">
        <div class="brand-sidebar">
            <h1 class="logo-wrapper">
                <a class="brand-logo darken-1" href="/admin">
                    <img src="/app-assets/images/logo/materialize-logo.png" alt="materialize logo"/>
                    <span class="logo-text hide-on-med-and-down" style="font-family: initial;">Home</span>
                </a>
                <a class="navbar-toggler" href="#" onclick="window.history.back();return false;"><i class="fa fa-reply"></i></a>
            </h1>
        </div>
        <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="accordion">
            <li class="bold" v-for="(route, key) in routes" v-bind:key="key" :rule='route.role'>
                <router-link :to="{ name : route.name }" :key="key" class="waves-effect waves-cyan">
                    <i class="fa" :class="route.icon"></i><span class="menu-title" data-i18n="">{{route.name}}</span>
                </router-link>
            </li>
        </ul>
        <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
    </aside>
</template>

<script>
    export default {
        props:{
          routes:{
              type:Array,
              required:true
          }
        },
        name: "LeftSideBar"
    }
</script>

<style scoped>

</style>
