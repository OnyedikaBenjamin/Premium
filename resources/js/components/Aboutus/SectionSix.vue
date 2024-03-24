<template>
  <section v-if="data" class="container mar-top mar-bot">
        <div class="news-letter-box">
            <div class="row row-cols-lg-2 row-cols-1 align-items-center">
                <div class="col">
                    <h3 class="mb-3">{{data.title}}</h3>
                    <p class="mb-0">{{data.text}}</p>
                </div>
                <div class="col mt-lg-0 mt-4">
                    <div class="search-text newsletter">
                        <form>
                            <div class="input-group mb-0">
                                <input type="text" name="email" :placeholder="__('messages.email')" class="form-control"> 
                                <button @click="subscribe" class="subscribe-btn">{{data.buttontext}}</button>                               
                            </div>  
                            <div v-if="$v.email.$error" class="invalid-feedback-data">
                                {{__('auth.email')}} {{__('messages.is_required')}}
                            </div>              
                        </form>
                    </div>
                </div>                
            </div>
        </div>
    </section>
</template>
<script>
import { required } from "vuelidate/lib/validators";
export default {
    name: "SectionSix",
    data() {
            return {
                email: "",
            };
        },
    props:{
        data:{type:Object}
    },
    validations: {
        email: { required },
    },
    methods: {
        async subscribe() {
            this.$v.$touch();

            if (this.$v.$invalid) {
                return;
            }
        }
    }
}
</script>