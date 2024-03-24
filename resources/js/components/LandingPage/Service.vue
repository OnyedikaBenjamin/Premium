<template>
<section class="our-service our-service-lists mar-bot editors light-background"  data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
    <div class="container">
        <div class="header-title d-flex align-items-center justify-content-between flex-wrap gap-3">
            <h3 class="title">{{__('messages.service')}}</h3>
            <router-link :to="{ name: 'service' }" class="link-btn-box"><span>{{__('messages.see_all')}}</span></router-link>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline">
            <div v-for="(data, index) in service" :key="index"  class="col">
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
      
         <div class="pattern-image">
         <img :src="baseUrl+'/images/frontend/breadcrumb-bg.png'">
        </div>
    </div>
</section>
</template>
<script>
import { mapGetters } from "vuex";
export default {
    name:'Service',
    data(){
        return{
            baseUrl:window.baseUrl
        }
    },
    computed: {
        ...mapGetters(["service"]),
    },
}
</script>
