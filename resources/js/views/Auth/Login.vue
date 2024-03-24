<template>
    <div>
        <b-modal ref="loginmodal" id="my-modal" title="Login">
            <form action="javascript:void(0)" class="row" method="post">
                <div class="form-group col-12">
                    <label for="email" class="font-weight-bold">{{__('auth.email')}}<span class="text-danger">*</span></label>
                    <input
                        type="text"
                        v-model="email"
                        name="email"
                        id="email"
                        class="form-control"
                        :placeholder="__('auth.email')"
                    />
                    <div v-if="$v.email.$error" class="invalid-feedback-data">
                        {{__('auth.email')}} {{__('messages.is_required')}}
                    </div>
                </div>
                <div class="form-group col-12 login-psw">
                    <label for="password" class="font-weight-bold">{{__('auth.login_password')}}<span class="text-danger">*</span></label>
                     <i v-if="showpassword" class="fa fa-eye text-primary" @click="showlogpass"></i>
                     <i v-else class="fa fa-eye-slash text-primary" @click="showlogpass"></i>
                    <input
                        type="password"
                        v-model="password"
                        name="password"
                        id="logpassword"
                        class="form-control"
                        :placeholder="__('auth.login_password')"
                    />
                    <div v-if="$v.password.$error" class="invalid-feedback-data">
                        {{__('auth.login_password')}} {{__('messages.is_required')}}
                    </div>
                     
                </div>
                <div class="col-12 mb-2 text-center">
                    <button
                        type="submit"
                        class="btn btn-primary btn-block"
                        @click="login"
                    >
                        {{__('auth.login')}}
                    </button>
                </div>
                <div class="col-12 text-center">
                    <label
                        class="d-flex align-items-center justify-content-center flex-column"
                    >{{__('auth.dont_have_account')}}
                        <div v-b-modal="'my-modal1'" @click="closeloginmodal">
                            <a href="#">{{__('auth.signup')}}</a>
                        </div></label
                    >
                </div>
                <div class="col-12 text-center mt-2">
                    <a @click="redirectToLogin" class="btn btn-primary btn-sm float-right">{{__('messages.login_btn_user')}}</a>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { displayMessage, displayError } from '../../messages';
import { mapGetters } from "vuex";
export default {
    name: "Login",
    data() {
        return {
            email: "",
            password: "",
            baseUrl: window.baseUrl,
            showpassword: true
        };
    },
    computed: {
        ...mapGetters(["userData"]),
    },
    validations: {
        email: { required },
        password: { required },
    },
    methods: {
        async login() {
            this.$v.$touch();

            if (this.$v.$invalid) {
                return;
            }
            this.$store
                .dispatch("login", {
                    email: this.email,
                    password: this.password,
                    loginfrom: 'vue-app'
                })
                .then((response) => {
                    this.$router.push({ name: this.$route.name, query: { redirect: this.$route.path } });
                    this.closeloginmodal();
                    displayMessage('You have successfully logged in.')
                })
                .catch((err) => {
                    if(err.response != undefined){
                        displayError(err.response.data.message)
                    }
                });
        },
        closeloginmodal() {
            this.$refs["loginmodal"].hide();
        },
        show() {
            this.$refs["loginmodal"].show();
        },
        redirectToLogin(){
            window.location.href = baseUrl + "/login";
        },
        showlogpass(){
            this.showpassword=!this.showpassword
            var elem= document.querySelector('#logpassword')
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
