<template>
    <div>
        <b-modal id="my-modal1" ref="registermodal" title="Register" @hidden="resetModal">
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
                    <label for="contact_no" class="font-weight-bold">{{__('messages.contact_number')}}</label>
                    <input
                        type="text"
                        name="contact_no"
                        v-model="user.contact_number"
                        id="contact_no"
                        :placeholder="__('messages.contact_number')"
                        class="form-control"
                    />
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
                            <a href="#">{{__('auth.sign_in')}}</a>
                        </div><div><a href="#" @click="signup" v-b-modal="'my-modal2'">{{__('messages.want_register_provider')}}</a></div></label
                    >
                </div>
            </form>
        </b-modal>
        <login-component ref="openModal" />
        <ProviderRegister ref="openModal1" />
    </div>
</template>

<script>
import { displayMessage,displayError } from '../../messages';
import ProviderRegister from '../../views/Auth/ProviderRegister.vue';
import {
  required
} from "vuelidate/lib/validators";
export default {
    components: { ProviderRegister },
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
                    this.hidepage();
                    displayMessage('You have successfully register')
                })
                .catch((err) => {
                    console.log(err)
                    displayError(err.response.data.message)
                });
        },
        closeregistermodal() {
               this.$refs["registermodal"].hide();
               this.$refs.openModal.show();
        },
        signup(){
            this.$refs["registermodal"].hide();
            this.$refs.openModal1.show();
        },
        hidepage(){
            this.$refs["registermodal"].hide();
        },
        resetModal() {
            this.user.first_name= "";
            this.user.last_name= "";
            this.user.username= "";
            this.user.email= "";
            this.user.contact_no= "";
            this.user.  password= "";
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
