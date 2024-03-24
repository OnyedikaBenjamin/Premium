<template>
<div>
  <breadcrumb :homeName="this.$route.meta.label" :sectionName="'Provider'" />
  <section class="our-provider our-provider-list editors mar-top mar-bot"  data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
    <div class="container">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-4 list-inline">
         <div v-for="(provider, index) in providers" :key="index" class="col">
            <router-link
                  :to="{
                    name: 'provider-service',
                    params: { provider_id: provider.id },
                  }"
            >
            <div class="provider-box">
              <div class="img-box">
                  <img :src="provider.profile_image"  class="team-img" alt=""> 
                  <div class="certi-box">
                      <img :src="baseUrl + '/images/frontend/certi.png'" alt=""> 
                  </div>
              </div>
              <div class="provider-box-content">
                  <h5>{{provider.display_name}}</h5>
                  <span class="primary-color">{{provider.providertype}}</span>
                  <div>
                    <rating :readonly = true :showrating ="false" :ratingvalue="provider.providers_service_rating" />
                </div>
              </div>
            </div>
            </router-link>
          </div>
      </div>
    </div>
  </section>
</div>
</template>
<script>
import {get} from '../../request'
export default {
  name: "OurExpert",
  data() {
    return {
      providers: [],
      baseUrl : window.baseUrl
    };
  },
  mounted() {
    this.getProvider();
  },
  methods: {
    getProvider() {
      get("user-list", {
          params: {
            user_type: "provider",
            per_page: "all"
          },
        })
        .then((response) => {
          this.providers = response.data.data;
        });
    },
  },
};
</script>
