<template>
    <div>
        <h1>{{count}}</h1>
        <div class="pb-1">
            <router-link :to="{name: 'user.create'}" class="btn btn-success">Create</router-link>
        </div>
        <UsersTable
            :items="users"
            :headers="headers"
            :search="search"
            @deleteUser = "deleteUser">
        </UsersTable>
    </div>
</template>

<script>
    import UsersTable from "../../tables/UsersTable";

    import { mapGetters , mapActions , mapMutations } from 'vuex';

    export default {
        name: "Users",
        components:{
            UsersTable,
        },
        data () {
            return {
                search: '',
                headers: [
                    { text: 'Name', align: 'left', sortable: false, value: 'name' },
                    { text: 'Surname', value: 'surname' },
                    { text: 'Email', value: 'email' },
                    { text: 'Phone', value: 'phone' },
                    { text: 'Cover Photo' , value : 'cover_photo'},
                    { text: 'Status', value: 'status' },
                    { text: 'Gender', value: 'gender' },
                    { text:'Actions', value:'action',align: 'center'}
                ],
            }
        },
        computed: {
            ...mapGetters({
                users:'allUsers',
                count:'usersCount',
            })
        },
        methods:{
            ...mapActions({
                fetchUsers :'fetchUsers',
                deleteUser :'deleteUser'
            }),
        },
        async mounted() {
            this.fetchUsers();
        },
    }
</script>

<style scoped>

</style>
