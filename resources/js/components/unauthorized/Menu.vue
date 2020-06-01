<template>
    <div class="main">
        <Header :routes="routes"></Header>
        <div class="container" >
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import Header from "./Header.vue";
    export default {
        name: "Menu",
        data() {
            return {
                routes:[
                    { name: 'Register', path: 'register' },
                    { name: 'Login', path: 'login'}
                ],
            }
        },
        created() {
            this.addStyles();
            this.addScripts();
        },
        methods:{
            addStyles:function () {

            },
            addScripts:function () {

            }
        },
        components:{
            Header
        }
    }
</script>

<style scoped>

</style>
