<template>
<div class="service-detail service-main mar-bot">
    <div v-if="loading" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
    </div>
    <div v-else>
        <div class="service-image">
            <img v-if="serviceData.attchment_extension == true" :src="serviceData.attchments.length >0 ? serviceData.attchments[0]  : baseUrl+'/images/default.png'"  alt="image" class="img-fluid">
            <div v-else class="player-container">
                <vue-core-video-player  :src="serviceData.attchments.length >0 ?  serviceData.attchments[0] :baseUrl+'/images/file.png'" :cover="baseUrl+'/images/file.png'" :preload="'auto'"></vue-core-video-player>
            </div>
            <span class="service-fav-item badge badge-1 rounded-pill bg-white">{{__('messages.mark_as_favourite')}}
                <favorite :servicedata="serviceData.id" :favorited="serviceData.is_favourite" @emit-list="serviceDetail"/>
            </span>
        </div>
        <div class="service-heading-part">
            <div class="container">
                <div class="service-heading-box">
                    <div class="service-title">
                        <h3 class="service-title-main">{{serviceData.name}}</h3>
                        <div class="price d-flex align-items-center">
                            <h4 class="primary-color">{{serviceData.price_format}}</h4> <small v-if="serviceData.type =='hourly'">/hr</small> <span v-if="serviceData.discount" class="ps-2 ser-discount">{{serviceData.discount}}% {{__('messages.off')}}</span>
                        </div>
                    </div>
                    <div class="service-meta">
                        <ul class="list-inline d-flex flex-wrap align-items-center mb-0">
                            <li class="d-flex align-items-center flex-wrap gap-2">
                                <span class="list-label heading-color heading-weight">{{__('messages.category')}}:</span><span class="list-value"> 
                                    <div class="d-block d-sm-flex align-items-center text-primary">                               
                                        {{serviceData.category_name}} <span v-if="serviceData.subcategory_name"><span class="subcat_spacing"> > </span><span>{{serviceData.subcategory_name}}</span></span>
                                    </div>                                            
                                </span>
                            </li>
                            <li class="d-flex align-items-center">
                                <span class="list-label heading-color heading-weight">{{__('messages.rating')}}:</span><span class="list-value"> 
                                    <div class="d-flex align-items-center text-primary px-2">                               
                                        <rating :readonly = true :showrating ="false" :ratingvalue="serviceData.total_rating" />
                                    </div>                                            
                                </span>
                            </li>
                        </ul>
                        <div class="book-now-btn">
                            <button class="btn btn-primary"><router-link class="text-white" :to="{name: 'book-service',params: { service_id: serviceData.id}}">{{__('messages.book_now')}}</router-link></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="service-content-part mar-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="service-detail-info">                                
                            <div v-if="serviceData.description" class="service-description">
                                <div class="desc-title">
                                    <h4>{{__('messages.description')}}</h4>                    
                                </div> 
                                <div class="service-details">
                                    <p>{{serviceData.description}}</p>                                  
                                </div>                            
                            </div>
                            <div v-if="serviceData.attchments.length >0" class="service-gallery service-top-space">
                                <div class="header-title-inner-page d-flex align-items-center justify-content-between">
                                    <h4 class="title">{{__('messages.gallery')}}</h4>
                                    <router-link :to="{ name: 'gallery', params: { galleryimg: serviceData.attchments,serviceName:serviceData.name }}"  class="link-btn-box text-primary" ><span>{{__('messages.view_all')}}</span></router-link>
                                </div>   
                                <div class="gallery-box">
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                                        <div  v-for="(serviceimg,index) in serviceData.attchments" :key="index" class="col">
                                            <div class="card iq-file-manager mb-lg-0">
                                                <div class="card-body p-0">
                                                    <a data-fslightbox="gallery" :href="serviceimg">
                                                        <img :src="serviceimg" class="img-fluid rounded" alt="">
                                                    </a>                           
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                             
                            </div>
                            <div v-if="ratingData.length >  0 " class="service-review-data">
                                <div class="header-title-inner-page d-flex align-items-center justify-content-between">
                                    <h4 class="title mt-3">{{__('messages.review')}}</h4>                    
                                </div> 
                                <div class="review-details">
                                    <ul class="list-inline">
                                        <li v-for="(rating,index) in ratingData"  :key="index" class="review-card">
                                            <div class="author-wrapper card-details">
                                                <div class="autho-img">
                                                    <img :src="rating.profile_image" alt="image" class="img-fluid rounded-circle">
                                                </div>
                                                <div class="autho-detail">
                                                    <div class="autho-title">
                                                        <h5>{{rating.customer_name}}</h5>
                                                        <div class="review-date">{{rating.created_at}}</div>
                                                    </div>
                                                    <div class="ratting-value"> 
                                                        <div class="d-flex align-items-center text-primary">                               
                                                        <rating :readonly = true :showrating ="false" :ratingvalue="rating.rating" />
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="review-description mb-0">{{rating.review}}</p>
                                                
                                                </div>                                            
                                            </div>
                                        </li>
                                    </ul>
                                </div>                            
                            </div>
                            <div v-if="relatedService.length > 0" class="service-related-product service-top-space">
                                <div class="header-title-inner-page d-flex align-items-center justify-content-between">
                                    <h4 class="title">{{__('messages.related_service')}}</h4>
                                </div>
                                <div class="relateservice our-service">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-xs-12" v-for="(data, index) in relatedService" :key="index"> 
                                        <service-list 
                                                :serviceId="data.id"
                                                :imageUrl="data.attchments.length >0 ? data.attchments[0] : baseUrl+'/images/default.png'"
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
                        </div>
                    </div>
                    <div class="col-lg-4 mt-lg-0 mt-5 serivce-sidebar">                       
                        <div v-if="serviceData.service_address_mapping.length >  0 " class="card">
                            <div class="card-body">
                                    <div class="sidebar-title">
                                    <h5 class="title">{{__('messages.availability')}}</h5>
                                </div>
                                <div class="service-tag">
                                    <ul class="list-inline mb">
                                        <li v-for="(address, index) in serviceData.service_address_mapping" :key="index">
                                            <a class="active" href="#" @click="getBookingAddress(address.provider_address_id)">{{ address.provider_address_mapping.address }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>                            
                        </div>
                        <div class="card">
                            <div class="card-body">
                                    <div class="sidebar-title">
                                    <h5 class="title">{{__('messages.provider')}}</h5>
                                </div>
                                <div class="provider-info">
                                    <div class="d-sm-flex d-flex">
                                        <div class="provider-user me-3">
                                            <img :src="provider.profile_image" alt="image" class="img-fluid avatar avatar-70 rounded-circle">
                                        </div>
                                        <div class="provider-content mt-3 mt-sm-0 w-100">
                                            <div class="provider-title d-flex align-items-center justify-content-between">
                                                <h6>{{provider.display_name}}</h6>
                                                <div class="certi-box">
                                                    <img :src="baseUrl+'/images/frontend/layouts/icon.png'" alt=""> 
                                                </div>
                                            </div>
                                            
                                            <div class="ratting-value"> 
                                                <div class="d-flex align-items-center">                               
                                                    <rating :readonly = true :showrating ="false" :ratingvalue="provider.providers_service_rating" />
                                                </div>
                                            </div>
                                            
                                            <a v-bind:href="'mailto:'+provider.email" class="btn btn-primary mt-4">{{__('messages.contact')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="card" v-if="serviceData.duration">
                            <div class="card-body">
                                    <div class="sidebar-title">
                                    <h5 class="title">{{__('messages.duration')}}</h5>
                                </div>
                                <div class="duration-details">
                                    <p>{{__('messages.hour_service_desc')}}<strong class="text-primary"> {{serviceData.duration}} {{__('messages.hour')}} </strong></p>
                                </div>
                            </div>                            
                        </div>
                        
                    </div>   
                </div> 
            </div>
        </div>
        <div class="service-book-now">
            <router-link :to="{name:'book-service'}" class="btn btn-primary rounded"> {{__('messages.book_now')}} </router-link>
        </div>
    </div>
    <b-modal id="reviewedit" modal-class="modal fade" title="Review" header-close-content=" <button type='button' class='btn-close btn-sm' data-bs-dismiss='modal' aria-label='Close'></button>">
        <form class="review-form">
                <div class="ratting-box">
                    <div class="d-flex align-items-center">
                        <span class="list-label">{{__('messages.rating')}}</span>
                        <span class="list-value"> 
                            <div class="d-flex align-items-center ps-2">                               
                                <i class="fas fa-star me-1"></i>
                                <i class="fas fa-star me-1"></i>
                                <i class="fas fa-star me-1"></i>
                                <i class="fas fa-star me-1"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </span>
                    </div>
                </div>
                <div class="comment-form-comment">
                    <textarea id="comment" name="comment" aria-required="true" placeholder="Enter your message"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3" data-bs-dismiss='modal'>{{__('messages.update')}}</button>
            </form> 
    </b-modal>
</div>
</template>
<script>
import {post} from '../../request'
import Favorite from '../../components/Global/Favorite.vue'
export default {
  components: { Favorite },
    name:'ServiceDetail',
    data(){
        return{
            loading: true,
            serviceData:{},
            provider:{},
            ratingData:{},
            relatedService:{},
            baseUrl:window.baseUrl
        }
    },
    watch: {
        '$route.params.service_id'(newId, oldId) {
            this.serviceDetail(newId)
        }
    },
    mounted(){
        jQuery(window).on("scroll", function () {
        if (jQuery(this).scrollTop() > 450) {
            jQuery('.service-book-now').fadeIn(1400);
        } else {
            jQuery('.service-book-now').fadeOut(400);
        }
        });
        this.loading = true,
        this.serviceDetail()
    },
    methods:{
        serviceDetail(){
            post("service-detail", {
                service_id: this.$route.params.service_id,
                customer_id: this.$store.state.user ? this.$store.state.user.id : ''
            })
            .then((response) => {
                this.serviceData = response.data.service_detail;
                this.provider = response.data.provider;
                this.ratingData = response.data.rating_data
                this.relatedService = response.data.related_service
            });
            this.loading = false;
        }
    }
}
</script>

