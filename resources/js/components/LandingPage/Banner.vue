<template>
<section class="main-banner">
    <div v-if="slider" class="swiper-container mainBanner">
        <div class="swiper-wrapper">
            <div v-for="(sliderdata,index) in slider" :key="index" class="swiper-slide item-slide"   v-bind:style="{ backgroundImage: 'url(' + sliderdata.slider_image  + ')' }">
                <div class="inner-content">
                    <h2 class="slider-title">{{sliderdata.title}}</h2>
                    <p class="short-text">{{sliderdata.description}}</p>
                    <router-link class=" btn btn-primary mt-3 text-white" :to="{name: 'service-detail',params: { service_id: sliderdata.type_id }}">{{__('messages.book_now')}}</router-link>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>
</template>
<script>
import { mapGetters } from "vuex";
import Swiper, { Navigation, Pagination, Parallax, Autoplay } from 'swiper'
Swiper.use([Navigation, Pagination, Parallax, Autoplay])

export default {
    name:'Banner',
    data(){
        return{
            defualt: window.baseUrl + '/images/default.png'
        }
    },
    mounted(){
        new Swiper(".mainBanner", {
        autoplay:{delay: 2000},
        loop: true,
        slidesPerView: 1,
        spaceBetween: 10,
        observer: true,  
        observeParents: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            320: { slidesPerView: 1 },
            550: { slidesPerView: 1 },
            991: { slidesPerView: 1},
            1400: { slidesPerView: 1 },
            1500: { slidesPerView: 1 },
            1920: { slidesPerView: 1},
            },
        });
        
    },
    computed: {
        ...mapGetters(["slider"]),
    },
}
</script>
