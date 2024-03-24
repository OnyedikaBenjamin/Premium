<template>
    <div>
        <b-modal id="my-modal2" ref="registermodal" title="Register" @hidden="resetModal">
            <form
                action="javascript:void(0)"
                @submit="register"
                class="row"
                method="post"
            >
                <div class="form-group col-sm-6 col-12">
                    <label for="name" class="font-weight-bold">{{__('auth.first_name')}}<span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="first_name"
                        v-model="user.first_name"
                        id="first_name"
                        :placeholder="__('auth.first_name')"
                        class="form-control"
                    />
                    <div v-if="submit && !$v.user.first_name.required"
                        class="invalid-feedback-data">{{__('auth.first_name')}} {{__('messages.is_required')}}
                    </div>
                </div>
                <div class="form-group col-sm-6 col-12">
                    <label for="last_name" class="font-weight-bold">{{__('auth.last_name')}}<span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="last_name"
                        v-model="user.last_name"
                        id="last_name"
                        :placeholder="__('auth.last_name')"
                        class="form-control"
                    />
                     <div v-if="submit && !$v.user.last_name.required"
                        class="invalid-feedback-data">{{__('auth.last_name')}} {{__('messages.is_required')}}
                    </div>
                </div>
                <div class="form-group col-sm-6 col-12">
                    <label for="username" class="font-weight-bold">{{__('auth.username')}}<span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="username"
                        v-model="user.username"
                        id="username"
                        :placeholder="__('auth.username')"
                        class="form-control"
                    />
                     <div v-if="submit && !$v.user.username.required"
                        class="invalid-feedback-data">{{__('auth.username')}} {{__('messages.is_required')}}
                    </div>
                </div>
                <div class="form-group col-sm-6 col-12">
                    <label for="email" class="font-weight-bold">{{__('auth.email')}}<span class="text-danger">*</span></label>
                    <input
                        type="email"
                        name="email"
                        v-model="user.email"
                        id="email"
                        :placeholder="__('auth.email')"
                        class="form-control"
                    />
                    <div v-if="submit && !$v.user.email.required"
                        class="invalid-feedback-data">{{__('auth.email')}} {{__('messages.is_required')}}
                    </div>
                </div>
                <div class="form-group col-sm-6 col-12 login-psw">
                    <label for="password" class="font-weight-bold">{{__('auth.login_password')}}<span class="text-danger">*</span></label>
                     <i v-if="showpassword" class="fa fa-eye text-primary" @click="showregpass"></i>
                     <i v-else class="fa fa-eye-slash text-primary" @click="showregpass"></i>
                    <input
                        type="password"
                        name="password"
                        v-model="user.password"
                        id="regpassword"
                        :placeholder="__('auth.login_password')"
                        class="form-control"
                    />
                    <div v-if="submit && !$v.user.password.required"
                        class="invalid-feedback-data">{{__('auth.login_password')}} {{__('messages.is_required')}}
                    </div>
                </div>
                <div class="form-group col-sm-6 col-12">
                    <label for="contact_no" class="font-weight-bold">{{__('messages.contact_number')}}<span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="contact_no"
                        v-model="user.contact_number"
                        id="contact_no"
                        :placeholder="__('messages.contact_number')"
                        class="form-control"
                    />
                    <div v-if="submit && !$v.user.contact_number.required"
                        class="invalid-feedback-data">{{__('auth.contact_number')}} {{__('messages.is_required')}}
                    </div>
                   
                </div>

                <div class="form-group col-sm-6 col-12">
                    <label for="user_type" class="font-weight-bold">User Type<span class="text-danger">*</span></label>
                   <select class="form-control" name="user_type" v-model="user.user_type">
                   
                    <option value="provider">Provider</option>
                        <option value="handyman">Handyman</option>
                </select>
                     <div v-if="submit && !$v.user.user_type.required"
                        class="invalid-feedback-data">{{__('auth.user_type')}} {{__('messages.is_required')}}
                    </div>
                </div>

                <div class="form-group col-sm-6 col-12">
                    <label for="designation" class="font-weight-bold">Designation<span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="designation"
                        v-model="user.designation"
                        id="designation"
                        :placeholder="__('messages.designation')"
                        class="form-control"
                    />
                     <div v-if="submit && !$v.user.designation.required"
                        class="invalid-feedback-data">{{__('auth.designation')}} {{__('messages.is_required')}}
                    </div>
                </div>
                
                <div class="col-12 mb-2 text-center">
                    <button
                        type="submit"
                        :disabled="processing"
                        class="btn btn-primary btn-block"
                    >
                        {{ processing ? "Please wait" : __('messages.register') }}
                    </button>
                </div>
                <div class="col-12 text-center">
                  
                    <label
                        class="d-flex align-item-center flex-column justify-content-center"
                    >{{__('auth.already_have_account')}}
                        <div @click="closeregistermodal">
                            <a href="#" @click="register()">{{__('auth.sign_in')}}</a>
                           
                        </div> </label
                    >
                </div>
            </form>
        </b-modal>
        <login-component ref="openModal" />

    </div>
</template>

<script>
import { displayMessage,displayError } from "../../messages";
import {post} from '../../request';
import {
  required
} from "vuelidate/lib/validators";

export default {
    name: "Register",
    validations: {
        user: {
            first_name: {
                required,
            },
            last_name: {
                required,
            },
            username: {
                required,
            },
            email: {
                required,
            },
            password: {
                required,
            },
            user_type: {
                required,
            },
            contact_number: {
                required,
            },
            designation:{
                required,
            },
        }
    },
    data() {
        return {
            user: {
                first_name: "",
                last_name: "",
                username: "",
                email: "",
                contact_no: "",
                password: "",
                user_type:"",
                designation:"",
                status:1
            },
            processing: false,
            showpassword:true,
            submit:false
        };
    },
    methods: {
        async register() {
            this.submit = true
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            }
            this.$store
                .dispatch("register", this.user)
                .then(() => {
                    displayMessage('You have successfully register')
                    this.hidepage();
                })
                .catch((err) => {
                    displayError(err.response.data.message)
                });
        },
        closeregistermodal() {
            this.$refs["registermodal"].hide();
               this.$refs.openModal.show();
        },

        show() {
            this.$refs["registermodal"].show();
        },

        hidepage(){
            this.$refs["registermodal"].hide();
            window.location.href = baseUrl + "/login";

        },
           
        resetModal() {
            this.user.first_name= "";
            this.user.last_name= "";
            this.user.username= "";
            this.user.email= "";
            this.user.contact_no= "";
            this.user.  password= "";
            this.user.designation="";
            this.user.user_type="";
        },
         showregpass(){
            this.showpassword=!this.showpassword
            var elem= document.querySelector('#regpassword')
            const type = elem.getAttribute('type') === 'password' ? 'text' : 'password';
            elem.setAttribute('type', type);
        }
    },
};
</script>
<style scoped>
.form-group {
  position: relative;
}

.form-group > i {
  position: absolute;
  right:2rem;
  top:50px
}

.form-group > input[type="password"]{
  padding-right: 3rem; 
}
</style>
