<template>
    <div>
        <breadcrumb :sectionName="this.$route.meta.label" :homeName="this.$route.meta.homeName" />
        <section class="container about-us mar-top mar-bot">
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-info">
                        <h3 class="mb-5">{{__('messages.contact_us_page')}}</h3>
                        <div v-if="generalsetting.helpline_number" class="icon-box-wrapper">
                            <div class="icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="Iconly/Light/Calling">
                                    <g id="Calling">
                                    <path id="Stroke 1" d="M14.3525 2.5C18.0535 2.911 20.9775 5.831 21.3925 9.532" stroke="#209EA4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path id="Stroke 3" d="M14.3525 6.04297C16.1235 6.38697 17.5075 7.77197 17.8525 9.54297" stroke="#209EA4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path id="Stroke 5" fill-rule="evenodd" clip-rule="evenodd" d="M11.0315 12.4724C15.0205 16.4604 15.9254 11.8467 18.4652 14.3848C20.9138 16.8328 22.3222 17.3232 19.2188 20.4247C18.8302 20.737 16.3613 24.4943 7.68446 15.8197C-0.993405 7.144 2.76157 4.67244 3.07394 4.28395C6.18376 1.17385 6.66682 2.58938 9.11539 5.03733C11.6541 7.5765 7.04253 8.48441 11.0315 12.4724Z" stroke="#209EA4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="icon-content">
                                <p class="mb-0">{{__('messages.helpline_number')}}</p>
                                <h6 class="mb-0">{{generalsetting.helpline_number}}</h6>                       
                            </div>
                        </div>
                        <div v-if="generalsetting.inquriy_email"  class="icon-box-wrapper">
                            <div class="icon-box">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="Iconly/Light/Message">
                                <g id="Message">
                                <path id="Stroke 1" d="M17.9024 8.85107L13.4591 12.4641C12.6196 13.1301 11.4384 13.1301 10.5989 12.4641L6.11816 8.85107" stroke="#209EA4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path id="Stroke 3" fill-rule="evenodd" clip-rule="evenodd" d="M16.9089 21C19.9502 21.0084 22 18.5095 22 15.4384V8.57001C22 5.49883 19.9502 3 16.9089 3H7.09114C4.04979 3 2 5.49883 2 8.57001V15.4384C2 18.5095 4.04979 21.0084 7.09114 21H16.9089Z" stroke="#209EA4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                </g>
                                </svg>
                            </div>
                            <div class="icon-content">
                                <p class="mb-0">{{__('messages.inquriy_email')}}</p>
                                <h6 class="mb-0"><a class="mailto" v-bind:href="'mailto:'+generalsetting.inquriy_email">{{generalsetting.inquriy_email}}</a></h6>                       
                            </div>
                        </div>
                        <div class="separator">               
                        </div>
                        <div class="social-box">
                            <p>{{__('messages.we_are_available')}} :</p>
                            <ul class="list-unstyled social_icon d-flex mb-0">
                                <li v-if="generalsetting.facebook_url">
                                    <a :href="generalsetting.facebook_url" class="fb-footer">
                                    <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li v-if="generalsetting.twitter_url">
                                    <a :href="generalsetting.twitter_url" class="twitter-footer">
                                    <i class="fab fa-twitter"></i>
                                    </a>
                                </li>
                                <li v-if="generalsetting.linkedin_url">
                                    <a :href="generalsetting.linkedin_url" class="link-footer">
                                    <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </li>
                                <li>
                                <li v-if="generalsetting.instagram_url">
                                    <a :href="generalsetting.instagram_url" class="insta-footer" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                    </a >
                                </li>
                                 <li v-if="generalsetting.youtube_url">
                                    <a :href="generalsetting.youtube_url" class="youtube-footer" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                    </a >
                                </li>
                            </ul>
                        </div>
                    </div>            
                </div>
                <div class="col-lg-6 mt-lg-0 mt-5" >
                    <div class="contact-form">
                            <div class="row row-cols-md-2  row-cols-1">
                                <div class="col form-group">
                                    <input type="text" class="form-control" id="exampleFormControlInput1" v-model="contact.first_name" :placeholder="__('auth.first_name')">
                                    <div v-if="submit && !$v.contact.first_name.required"
                                        class="invalid-feedback-data">{{__('auth.first_name')}} {{__('messages.is_required')}}
                                    </div>
                                </div>
                                <div class="col form-group">
                                    <input type="text" class="form-control" id="exampleFormControlInput2" v-model="contact.last_name"  :placeholder="__('auth.last_name')">
                                    <div v-if="submit && !$v.contact.last_name.required"
                                        class="invalid-feedback-data">{{__('auth.last_name')}} {{__('messages.is_required')}}
                                    </div>
                                </div>
                            </div>

                            <div class="row row-cols-md-2 row-cols-1">
                                <div class="col form-group">
                                    <input type="text" class="form-control" id="exampleFormControlInput3"  v-model="contact.phone_no" :placeholder="__('messages.contact_number')">
                                </div>
                                <div class="col form-group">
                                    <input type="text" class="form-control" id="exampleFormControlInput4"  v-model="contact.email" :placeholder="__('auth.email')">
                                    <div v-if="submit && !$v.contact.email.required"
                                        class="invalid-feedback-data">{{__('auth.email')}} {{__('messages.is_required')}}
                                    </div>
                                </div>
                            </div>
                            <div class="row row-cols-1">
                                <div class="col form-group">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="8"  v-model="contact.user_message" :placeholder="__('messages.message')"></textarea>
                                    <div v-if="submit && !$v.contact.user_message.required"
                                        class="invalid-feedback-data">{{__('messages.message')}} {{__('messages.is_required')}}
                                    </div>
                                </div>    
                            </div> 
                            <button type="submit" class="btn btn-primary pay" @click="contactUs">{{__('messages.send')}}</button>
                    </div>                
                </div>
            </div>
        </section>
        <section class="map-box mar-top">
             <iframe width="100%" height="450" frameborder="0" style="border:0" :src="'https://www.google.com/maps/embed/v1/place?q=' + latitude + ',' + longtitude + '&amp;key=' +googleMapKey"></iframe>
        </section>
    </div>
</template>
<script>
import { mapGetters } from "vuex";
import {post} from '../../request'
import { displayMessage,displayError } from "../../messages";
import {
  required
} from "vuelidate/lib/validators";
export default {
    name:'ContactUs',
     validations: {
        contact: {
            first_name: {
                required,
            },
            last_name: {
                required,
            },
            email: {
                required,
            },
            user_message: {
                required,
            },
        }
    },
    data() {
        return {
            contact:{},
            submit:false
        }
    },
    mounted(){
      this.contact = this.defaultContactRequest()
      console.log(this.googleMapKey);
    },
    computed: {
        ...mapGetters(['generalsetting','googleMapKey','latitude','longtitude']),
    },
    methods:{
        defaultContactRequest: function () {
            return {
                id: "",
                first_name: "",
                last_name: "",
                phone_no: "",
                email: "",
                user_message: "",
                subject: "Contact",
            };
        },
        contactUs(){
            this.submit = true
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            }
            post("contact-us", this.contact).then((response) => {
                if(response.status == 200){
                    displayMessage(response.data.message);
                    this.contact.first_name = '';
                    this.contact.last_name = '';
                    this.contact.phone_no = '';
                    this.contact.email = '';
                    this.contact.user_message = '';
                }
            });

        }
    }
}
</script>