<template>
    <div>
        <AdminHeader></AdminHeader>
        <LeftSideBar :routes="routes"></LeftSideBar>
        <Section></Section>
        <Footer></Footer>
    </div>
</template>

<script>
    import AdminHeader from './Header.vue';
    import Footer from "./Footer";
    import Section from "./Section";
    import LeftSideBar from "./LeftSideBar";
    export default {
        name: "Menu",
        data(){
            return {
                scripts:[
                    "/app-assets/js/vendors.min.js",
                    "/app-assets/vendors/chartjs/chart.min.js",
                    "/app-assets/js/plugins.js",
                    "/app-assets/js/custom/custom-script.js",
                    "/app-assets/js/scripts/dashboard-ecommerce.js",
                ],
                routes:[
                    { name:'Users' , path:'/dashboard/users' , role:'users' , icon:'fa-user' },
                ],
            }
        },
        components:{
            AdminHeader,Footer,LeftSideBar,Section
        },
        mounted() {
            this.scripts.forEach((src,index)=>{
                let recaptchaScript = document.createElement('script')
                recaptchaScript.setAttribute('src',src)
                document.body.appendChild(recaptchaScript)
            })
        }
    }
</script>

<style scoped>
    @import url('//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    @import url('/app-assets/vendors.min.css');
    @import url('/app-assets/css/themes/vertical-dark-menu-template/materialize.css');
    @import url('/app-assets/css/themes/vertical-dark-menu-template/style.css');
    @import url('/app-assets/css/pages/dashboard.css');
    @import url('/app-assets/css/custom/custom.css');
</style>
