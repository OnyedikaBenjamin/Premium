<template>
    <section class="our-provider editors mar-top mar-bot"  data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
        <div class="container">
            <div class="header-title d-flex align-items-center justify-content-between flex-wrap gap-3">
                <h3>{{__('messages.provider')}}</h3>
                <router-link :to="{ name: 'provider' }" class="link-btn-box">{{__('messages.see_all')}}</router-link>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 list-inline">
                <div v-for="(data, index) in provider" :key="index" class="col">
                    <div class="provider-box" v-if="index < 4">
                        <div class="img-box">
                            <router-link :to="{ name: 'provider-service',params: { provider_id: data.id }}">
                            <img :src="data.profile_image"  class="team-img"  alt=""> 
                            </router-link>
                            <div class="certi-box">
                                <img :src="baseUrl + '/images/frontend/certi.png'" alt=""> 
                            </div>
                        </div>
                        <div class="provider-box-content">
                            <router-link :to="{ name: 'provider-service',params: { provider_id: data.id }}">
                                <h5>{{data.display_name}}</h5>
                            </router-link>
                            <span class="provider-desc">{{data.providertype}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import { mapGetters } from "vuex";
export default {
    name:'Provider',
    data(){
      return {
          baseUrl:window.baseUrl
      }
    },
    computed: {
        ...mapGetters(["provider"]),
    },
}
</script>