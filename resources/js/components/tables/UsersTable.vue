<template>
    <div>
        <v-app id="inspire">
            <v-card>
                <v-card-title>
                    Users
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="search"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
                </v-card-title>
                <v-data-table
                    :headers="headers"
                    :items="items"
                    :search="search"
                >
                    <template v-slot:item="row">
                        <tr>
                            <td>{{row.item.name}}</td>
                            <td>{{row.item.surname}}</td>
                            <td>{{row.item.email}}</td>
                            <td>{{row.item.phone}}</td>
                            <td>
                                <img :src="row.item.cover_photo" alt="" width="40" height="40">
                            </td>
                            <td :class="statusTextColor(row.item.status)" >{{row.item.status}}</td>
                            <td>{{row.item.gender}}</td>
                            <td class="text-center">
                                <router-link :to="{name:'user.view', params: { id: row.item.id }}">
                                    <i class="fa fa-eye text-success"></i>
                                </router-link>
                                <router-link :to="{name:'user.edit', params: { id: row.item.id }}">
                                    <i class="fa fa-pencil text-premary"></i>
                                </router-link>
                                <a href="javascript:void(0)" @click="deleteClicked(row.item.id)">
                                    <i class="fa fa-trash-o text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    </template>
                    <template v-slot:no-results>
                        <v-alert :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </template>
                </v-data-table>
            </v-card>
        </v-app>
    </div>
</template>

<script>
    export default {
        props:{
            headers:{
                required:true,
                type:Array,
            },
            items:{
                required:true,
                type:Array
            },
            search:{
                type:[String]
            }
        },
        name: "UsersTable",
        methods: {
            deleteClicked(id) {
                this.$emit('deleteUser',id);
            },
            statusTextColor( $text ){
                return $text == 'Pending' ? 'text-danger' : 'text-success';
            }
        },
    }
</script>

<style scoped>

</style>
