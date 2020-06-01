<template>
    <div v-if="!$auth.ready()" class="text-center">
        <img src="https://thumbs.gfycat.com/SimilarPlumpBarasingha-small.gif" alt="">
    </div>
    <div v-else="$auth.ready()">
        <component :is = "loadComponent"></component>
    </div>
</template>
<script>
    import AdminMenu from './components/admin/Menu.vue';
    import UserMenu from './components/user/Menu.vue';
    import UnauthorizedMenu from './components/unauthorized/Menu.vue';

    export default {
        name: "App",
        methods: {
        },
        computed:{
          loadComponent(){
              if(!this.$auth.check() ){
                  return 'UnauthorizedMenu';
              }
              if( this.$auth.check('0') ){
                  return 'UserMenu';
              }
              if( this.$auth.check('-1') ){
                  return 'AdminMenu';
              }
          }
        },
        components: {
            AdminMenu,
            UserMenu,
            UnauthorizedMenu
        },
    }

</script>

<style scoped>

</style>
