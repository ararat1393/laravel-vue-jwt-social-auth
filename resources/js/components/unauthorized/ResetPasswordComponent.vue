<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Reset</div>

                <div class="card-body">
                    <form method="POST" autocomplete="off" @submit.prevent="reset" >
                        <div class="text-center">
                            <span :class="[error?'text-danger':'text-success']" v-if="message" role="alert"><strong>{{ message }}</strong></span>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" v-model="model.email" autocomplete="email" autofocus>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapMutations} from 'vuex'

    export default {
        name: "ResetPasswordComponent",
        data:function(){
            return {
                model:{
                    email:''
                },
                message:'',
                error : false
            }
        },

        methods:{
            ...mapMutations({
                storeMessage:'storeMessage',
            }),
            reset:function(){
              this.axios.post('password/reset',this.model)
              .then((response)=>{
                  this.error = false;
                  this.message = response.data.message;
              })
              .catch((error)=>{
                  this.message = error.response.data.errors
                  this.error = true;
              })
            }
        },
    }
</script>

<style scoped>

</style>
