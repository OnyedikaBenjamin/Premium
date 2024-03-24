<template>
  <div>
    <breadcrumb :sectionName="'Service List'" :homeName="this.$route.meta.label"  />
     <section class="category-detail mar-top mar-bot" data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
             <div class="container">
                  <div class="row">
                    <div class="col-lg-3" v-if="category.description">
                        <div class="category-info">
                            <h4 class="cat-title">{{__('messages.description')}}</h4>
                            <div class="cat-desc">
                                <p>{{category.description}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 ">
                        <div class="our-service">
                            <div v-if="filteredServiceList.length > 0" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                                <div v-for="(data, index) in filteredServiceList" :key="index" class="col">
                                    <service-list 
                                        :serviceId="data.id"
                                        :imageUrl="data.attchments[0] ? data.attchments[0] : baseUrl+'/images/default.png'"
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
                            <div v-else-if="filteredServiceList.length == 0" class="text-center">
                        <p>{{__('messages.data_not_found')}}</p>
                    </div>
                            <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                                <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
                            </div>
                        </div>
                    </div>
                  </div>
             </div>
        </section>
  </div>
</template>
<script>
import { mapGetters } from "vuex";
import {get} from '../../request'
export default {
  name: "providerService",
  data(){
      return{
          category: {},
          baseUrl:window.baseUrl,
          serviceList:[]

      }
  },
  computed: {
    filteredServiceList() {
      return this.serviceList.filter(data => data.provider_id === this.$route.params.provider_id);
    }
  },
  mounted(){
    this.getProviderService()
  },
  methods:{
    getProviderService(){
      var params= {
          per_page: "all",
          provider_id: this.$route.params.provider_id
      }
      var get_location_lat = localStorage.getItem('loction_current_lat')
      var get_location_long = localStorage.getItem('loction_current_long')
      
      if(get_location_lat  !='' && get_location_long != '' ){
          var  latitude = get_location_lat
          var  longitude = get_location_long
          var params= {
              category_id: this.$route.params.category_id,
              latitude:latitude,
              longitude:longitude,
          }
      }
          
        get("service-list",{
            params: params
        })
        .then((response) => {
            if(response.status === 200){
                this.serviceList = response.data.data;
            }else{
                this.serviceList = [];
            }
        });
    }
  }
};
</script>