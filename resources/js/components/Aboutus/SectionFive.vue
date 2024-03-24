<template>
<section :class="ratingList.length == 0 ?'our-customer-slider mar-top is-empty-review':'our-customer-slider mar-top' ">
    <div class="container">
        <div class="swiper-container customer-testimonial">
            <div class="swiper-wrapper">
                <div class="swiper-slide" v-for="(rating,index) in ratingList" :key="index">
                    <div class="row align-items-center row-cols-lg-2 row-cols-md-1 row-cols-1">
                        <div class="col">
                            <h3 class="tesimonial-title title-white">What our customers says</h3>
                            <h5 class="title-white">“{{rating.service_name}}“</h5>
                            <p class="mt-3 t-desc">{{rating.review}}</p>
                            <div class="tesimonial-image">
                                <img :src="rating.profile_image">
                                <div class="tesimonial-text">
                                    <h5 class="title-white">{{rating.customer_name}}</h5>
                                    <ul class="rating-box-wrapper">
                                        <li class="rating-box text-primary">
                                            <rating :readonly = true :showrating ="false" :ratingvalue="rating.rating " />
                                        </li>
                                        <li>
                                            <span>{{rating.created_at}}</span>
                                        </li>
                                    </ul>                                        
                                </div>
                            </div>
                        </div>
                        <div class="col mt-lg-0 mt-5">
                            <img :src="rating.attchments[0]" class="testi-img">
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-button-next c-nav-btn"></div>
            <div class="swiper-button-prev c-nav-btn"></div>
        </div>
    </div>
</section>
</template>
<script>
import Swiper, { Navigation, Pagination, Parallax, Autoplay } from 'swiper'
Swiper.use([Navigation, Pagination, Parallax, Autoplay])

import {get} from '../../request'
export default {
  name: "SectionFive",
  data() {
    return {
        ratingList:[]
    };
  },
  mounted() {
    this.getserviceRating()
    var swiper = new Swiper(".customer-testimonial", {
        autoplay:{delay: 2000},
        loop: true,
        slidesPerView: 1,
        spaceBetween: 15,
        observer: true,  
        observeParents: true,
        loopFillGroupWithBlank: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
  },
  methods:{
    getserviceRating(){
        get("top-rated-service", {})
        .then((response) => {
            if(response.status){
                this.ratingList = response.data.data;
            }
        })
    }
  }
}
</script>