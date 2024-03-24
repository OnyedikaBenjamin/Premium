<template>
<footer class="footer py-3">
    <div class="container custom_container">
      <div class="footer-extra-content">
        <div class="row footer-main-border">
          <div class="col-md-6 col-lg-2 col-xl-2 mb-4">
              <router-link :to="{ name: 'frontend-home' }"  ><img :src="generalsetting.site_logo ? generalsetting.site_logo : baseUrl +'/images/logo.svg'"  class="img-fluid logo" alt="logo"></router-link>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
            <h5 class="text-white product-services">{{__('messages.why_choose_handyman')}}</h5>
            <div class="extra-content">
             <router-link class="extra-page-content" :to="{ name: 'about-us' }"> <label><i class="fas fa-angle-right"></i></label> <span> {{__('messages.about_us')}} </span> </router-link>
              <router-link :to="{ name: 'contact-us' }" class="extra-page-content"> <label><i class="fas fa-angle-right"></i></label> <span> {{__('messages.contact_us')}} </span> </router-link>
              <router-link class="extra-page-content" :to="{ name: 'privacy-policy' }"> <label><i class="fas fa-angle-right"></i></label> <span> {{__('messages.privacy_policy')}} </span> </router-link>
              <router-link class="extra-page-content" :to="{ name: 'term-conditions' }"> <label><i class="fas fa-angle-right"></i></label> <span> {{__('messages.terms_condition')}} </span> </router-link>
              <router-link class="extra-page-content" :to="{ name: 'help-support' }"> <label><i class="fas fa-angle-right"></i></label> <span> {{__('messages.help_support')}} </span> </router-link>
              <router-link class="extra-page-content" :to="{ name: 'refund-cancellation-policy' }"> <label><i class="fas fa-angle-right"></i></label> <span> {{__('messages.refund_cancellation_policy')}} </span> </router-link>
            </div>
          </div>
          <div v-if="service.length > 0" class="col-md-6 col-lg-3 col-xl-4 mb-md-0 mb-2">
            <h5 class="text-white product-services">{{__('messages.handyman_services')}}</h5>
            <div class="row">
             <div class="col-lg-6" v-for="(column,i) in columns" :key="i">
                <div class="extra-content pb-3">
                  <a v-for="(item,index) in column"  :key="index" class="extra-page-content" href="#">
                    <span><router-link :to="{name: 'service-detail',params: { service_id: item.id }}" v-bind:style="{ 'color': '#209EA4' }">{{item.name}}</router-link></span>
                  </a>
                </div>
            </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3 col-xl-3 mb-4">
            <div class="card download-card">
              <div class="card-body">
               
                <h6 class="text-white mb-3">{{__('messages.download_aplication_from')}}</h6>
                <span class="download-text">{{__('messages.download_text')}}</span>
                <div class="d-flex flex-column mt-4">
                  <a  :href="appsetting.play_store_url" target="_blank"><img :src="baseUrl + '/images/frontend/gpay-white.png'"></a>
                  <a  :href="appsetting.app_store_url" target="_blank"><img class="mt-3" :src="baseUrl + '/images/frontend/apple-white.png'"></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="footer-information">
            <div class="footer-social">
              <p class="text-white">{{__('messages.we_are_available')}}</p>
              <div class="d-flex">
                <ul class="list-unstyled social_icon d-flex justify-content-end mb-0">
                  <li v-if="generalsetting.facebook_url">
                    <a :href="generalsetting.facebook_url" class="fb-footer" target="_blank">
                      <i class="fab fa-facebook-f"></i>
                    </a>
                  </li>
                  <li v-if="generalsetting.twitter_url">
                    <a :href="generalsetting.twitter_url" class="twitter-footer" target="_blank" >
                      <i class="fab fa-twitter"></i>
                    </a >
                  </li>
                  <li v-if="generalsetting.linkedin_url">
                    <a :href="generalsetting.linkedin_url" class="link-footer" target="_blank">
                      <i class="fab fa-linkedin-in"></i>
                    </a >
                  </li>
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
            <div class="footer-details">
              <div v-if="generalsetting.inquriy_email" class="footer-sub-line d-flex flex-column">
                <span class="text-white">{{__('messages.inquriy_email')}}</span>
                <span><a class="mailtofooter" v-bind:href="'mailto:'+generalsetting.inquriy_email">{{generalsetting.inquriy_email}}</a></span>
              </div>
              <hr class="hr-vertial">
              <div v-if="generalsetting.helpline_number" class="footer-sub-line d-flex flex-column">
                <span class="text-white">{{__('messages.helpline_number')}}</span>
                <span>{{generalsetting.helpline_number}}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
       <div class="row" v-if="generalsetting.site_copyright">
        <div class="col-lg-12 text-center footer-descriptiom pt-3">
          <p class="mb-0" v-html="processedCopyright"></p>
        </div>
      </div>
    </div>
    <cookie-law theme="dark-lime"></cookie-law>
</footer>
</template>
<script>
import { mapGetters } from "vuex";
import CookieLaw from 'vue-cookie-law'
export default {
    name:'Footer',
    components: { CookieLaw },
    data(){
      return {
        baseUrl:window.baseUrl,
        cols: 2,
        appsetting:{},
        companyName: "IQONIC Design",
        homepageURL: "https://apps.iqonic.design/handyman/#/",
      }
    },

    computed: {
        ...mapGetters(['generalsetting','service','appdownload']),
        processedCopyright() {
            const paragraph = this.generalsetting.site_copyright;
            const linkedText = paragraph.replace(
              this.companyName,
              `<a class="text-white" href="${this.homepageURL}">${this.companyName}</a>`
            );

            return linkedText;
        },
        columns () {
            let columns = []
            let mid = Math.ceil(this.service.length / this.cols)
            for (let col = 0; col < this.cols; col++) {
              if(this.service.length > 0){
                columns.push(this.service.slice(col * mid, col * mid + mid))
              }
            }
            return columns
        }
    },
    mounted(){
      setTimeout(() => {
          console.log(this.appdownload);
        if(this.appdownload !== null){
            this.appsetting = this.appdownload
            this.appsetting.play_store_url = this.appdownload.playstore_url
            this.appsetting.app_store_url= this.appdownload.appstore_url
        }else{
            axios.get(this.baseUrl + "/appdownload.json")
            .then((response) => {
                this.appsetting = response.data
            });
        }
    },20000);
    
    },
}
</script>