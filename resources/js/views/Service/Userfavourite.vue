<template>
    <div>
     <breadcrumb :sectionName="this.$route.meta.label" :homeName="this.$route.meta.homeName" />
     <section class="our-service our-service-lists mar-top mar-bot editors"  data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
        <div class="container">
            <div v-if="loading" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
            </div> 
            <div v-else-if="favoriteList.length > 0" class="row">
                <div class="col-lg-12">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                        <div v-for="(data, index) in favoriteList" :key="index" class="col">
                              <service-list 
                                    :serviceId="data.service_id"
                                    :imageUrl="data.service_attchments[0] ? data.service_attchments[0] : baseUrl+'/images/default.png'"
                                    :is_favourite="data.is_favourite"
                                    :servicePrice="data.price_format"
                                    :serviceName="data.name"
                                    :serviceRating="data.total_rating"
                                    :serviceDescription="data.description"
                                    :serviceProviderImg="data.provider_image"
                                    :serviceProvider="data.provider_name"
                                    :serviceType="data.type"
                                    :categoryName="data.category_name"
                                    :subcategoryName="data.subcategory_name"
                                />
                                
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center">
                <p>{{__('messages.data_not_found')}}</p>
            </div>  
            <!-- <div v-else class="row">
              <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
            </div> -->
        </div> 
     </section>
</div>
</template>
<script>
import {get} from '../../request'
export default {
  name: "UserFavorite",
  data() {
    return {
      loading: true,
      favoriteList: [],
      baseUrl : window.baseUrl
    };
  },
  mounted() {
    this.userFavorite();
  },
  methods: {
    userFavorite() {
      get("user-favourite-service", {
        params:{
          customer_id: this.$store.state.user ? this.$store.state.user.id : 0
        }
      }).then((response) => {
        this.favoriteList = response.data.data;
        console.log(this.favoriteList)
      });
    },
  },
  created() {
      setTimeout(() => {
          this.loading = false;
      }, 2000);
  }
};
</script>