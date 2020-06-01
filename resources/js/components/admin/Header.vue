<template>
    <header class="page-topbar" id="header">
        <div class="navbar navbar-fixed">
            <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-light">
                <div class="nav-wrapper">
                    <ul class="navbar-list right pr-2">
                        <li class="hide-on-med-and-down">
                            <a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);">
                                <i class="material-icons">settings_overscan</i>
                            </a>
                        </li>
                        <li>
                            <a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown">
                                <i class="material-icons">notifications_none</i>
                            </a>
                        </li>
                        <li>
                            <a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown">
			              		<span class="avatar-status avatar-online" style="vertical-align: unset;">
			              			<img src="/app-assets/images/avatar/avatar-7.png" alt="avatar"><i></i>
			              		</span>
                            </a>
                        </li>
                    </ul>
                    <!-- notifications-dropdown-->
                    <ul class="dropdown-content" id="notifications-dropdown">
                        <li>
                            <h6>NOTIFICATIONS<span data-count='0' class="new badge notif-count">0</span></h6></li>
                        <li class="divider"></li>
                    </ul>
                    <!-- profile-dropdown-->
                    <ul class="dropdown-content" id="profile-dropdown">
                        <li>
                            <a class="grey-text text-darken-1" href="javascript:void(0)" @click="logout()">
                                <i class="material-icons">keyboard_tab</i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
                <nav class="display-none search-sm">
                    <div class="nav-wrapper">
                        <form>
                            <div class="input-field">
                                <input class="search-box-sm" type="search" required="">
                                <label class="label-icon" for="search"><i class="material-icons search-sm-icon">search</i></label><i class="material-icons search-sm-close">close</i>
                            </div>
                        </form>
                    </div>
                </nav>
            </nav>
        </div>
    </header>
</template>

<script>
    export default {
        props:{

        },
        name: "Header",
        methods:{
            logout(){
                this.$auth.logout();
            }
        },
    }
</script>

<style scoped>

</style>
