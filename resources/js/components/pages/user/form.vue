<template>
    <div class="container-view">
        <div class="row flex-lg-nowrap">
            <div class="col">
                <div class="row">
                    <div class="col mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="e-profile">
                                    <div class="row">
                                        <div class="col-12 col-sm-auto mb-3">
                                            <div class="mx-auto" style="width: 140px;">
                                                <div :style=" error && errors.cover_photo ? 'border: 1px solid red;' : 'border: none;' " class="d-flex justify-content-center align-items-center rounded" style="height: 140px; background-color: rgb(233, 236, 239);">
                                                    <span v-show="photoHide && model.newRecord" class="text-center" style="color: rgb(166, 168, 170); font: bold 8pt Arial;">140x140</span>
                                                    <img id="uploadPreview" v-show="!photoHide || !model.newRecord" :src="model.cover_photo" style="width: 100%;height: 100%;object-fit: fill">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                                            <div class="text-center text-sm-left mb-2 mb-sm-0">
                                                <div class="mt-2">
                                                    <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">{{ getName }}</h4>
                                                    <p class="mb-0">{{ getEmail }}</p>
                                                    <div class="mt-2">
                                                        <input @change="previewImage" ref="file" type="file" v-show="false" accept="image/*">
                                                        <button @click="$refs.file.click()" class="btn btn-primary" type="button">
                                                            <i class="fa fa-fw fa-camera"></i>
                                                            <span>Choose Photo</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center text-sm-right">
                                                <span class="badge badge-secondary">{{ getRole }}</span>
                                                <div v-if="!model.newRecord" class="text-muted"><small>Joined {{ getJoinedDate }}</small></div>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a href="" class="active nav-link">Settings</a></li>
                                    </ul>
                                    <div class="tab-content pt-3">
                                        <div class="tab-pane active">
                                            <form class="form" method="POST" autocomplete="off" >
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Name</label>
                                                                    <input id="name" type="text" :class="['form-control', { 'is-invalid': error && errors.name }]" v-model="model.name" autocomplete="name">
                                                                    <span class="invalid-feedback" role="alert" v-if="error && errors.name"><strong>{{ errors.name }}</strong></span>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Surname</label>
                                                                    <input id="surname" type="text" :class="['form-control', { 'is-invalid': error && errors.surname }]" v-model="model.surname" autocomplete="surname">
                                                                    <span class="invalid-feedback" role="alert" v-if="error && errors.surname"><strong>{{ errors.surname }}</strong></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input id="email" type="text" :class="['form-control', { 'is-invalid': error && errors.email }]" v-model="model.email" autocomplete="email">
                                                                    <span class="invalid-feedback" role="alert" v-if="error && errors.email"><strong>{{ errors.email }}</strong></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Phone</label>
                                                                    <input id="phone" type="text" :class="['form-control', { 'is-invalid': error && errors.phone }]" v-model="model.phone" autocomplete="phone">
                                                                    <span class="invalid-feedback" role="alert" v-if="error && errors.phone"><strong>{{ errors.phone }}</strong></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div v-show="model.newRecord" class="col-12 col-sm-6 mb-3">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <label>Password</label>
                                                                    <input id="password" type="text" :class="['form-control', { 'is-invalid': error && errors.password }]" v-model="model.password" autocomplete="password">
                                                                    <span class="invalid-feedback" role="alert" v-if="error && errors.password"><strong>{{ errors.password }}</strong></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-5 offset-sm-1 mb-3">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <label>Status</label>
                                                                <div class="custom-controls-stacked px-2">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="radio" :checked="!model.status || model.status == 1" class="custom-control-input" id="user-status-approved" value="1" name="status" @input="model.status = $event.target.value">
                                                                        <label class="custom-control-label" for="user-status-approved">Approved</label>
                                                                    </div>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="radio" :checked="model.status == 0" class="custom-control-input" id="user-status-pending" value="0" name="status" @input="model.status = $event.target.value">
                                                                        <label class="custom-control-label" for="user-status-pending">Pending</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <label>Gender</label>
                                                                <div class="custom-controls-stacked px-2">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="radio" :checked="!model.gender || model.gender == 1" class="custom-control-input" id="user-gender-male" value="1" name="gender" @input="model.gender = $event.target.value">
                                                                        <label class="custom-control-label" for="user-gender-male">Male</label>
                                                                    </div>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="radio" :checked="model.gender == 0" class="custom-control-input" id="user-gender-female" value="0" name="gender" @input="model.gender = $event.target.value">
                                                                        <label class="custom-control-label" for="user-gender-female">Female</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>Role</label>
                                                            <select class="form-inline" v-model="model.role" >
                                                                <option v-for="role in roles" :value="role.id">
                                                                    {{ role.name }}
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col d-flex justify-content-end">
                                                        <button v-if="model.newRecord" class="btn btn-primary" @click="$emit('create',model)" type="button">Create</button>
                                                        <button v-else class="btn btn-primary" type="button" @click="$emit('update',model)">Update</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters, mapMutations} from "vuex";

    export default {
        name: "userForm",
        props : {
            model:{
                required:true,
                type:Object,
            },
            error:{
                type:Boolean
            },
            errors:{
                type:Object
            }
        },
        data(){
            return {
                photoHide:true,
                roles: [
                    { name: 'User', id: '0' , selected:true},
                    { name: 'Admin', id: '1' },
                    { name: 'Super Admin', id: '-1' }
                ]
            }
        },
        methods:{
            ...mapActions(['fetchUsers']),
            ...mapMutations(['createUser']),

            previewImage( event ) {
                var oFReader = new FileReader();
                oFReader.readAsDataURL(event.target.files[0]);
                this.photoHide = false;
                var currentObj = this;
                oFReader.onload = function (oFREvent) {
                    currentObj.model.cover_photo = oFREvent.target.result;
                    document.getElementById("uploadPreview").src = oFREvent.target.result;
                };
            },
        },

        async mounted(){
            this.fetchUsers;
        },

        computed:{
            ...mapGetters(['usersCount']),

            getRole:{
                get:function () {
                    var role = this.roles.find(role => role.id === this.model.role);
                    return role ? role.name : null ;
                }
            },
            getName:{
                get:function () {
                    return this.model.name ? this.model.name : 'Jone Doe';
                }
            },
            getEmail:{
                get:function () {
                    return this.model.email ? this.model.email : 'example@gmail.com';
                }
            },
            getJoinedDate:{
                get: function () {
                    return new Date(this.model.created_at).toLocaleDateString('en-GB', {
                        day : 'numeric',
                        month : 'short',
                        year : 'numeric'
                    }).split(' ').join(' ');
                }
            },
        }
    }
</script>

<style scoped>
    .select-wrapper select {
        display: none;
    }
</style>
