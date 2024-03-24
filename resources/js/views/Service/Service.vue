<template>
<div>
     <breadcrumb :sectionName="this.$route.meta.label" :homeName="this.$route.meta.homeName" />
     <section class="our-service our-service-lists mar-top mar-bot editors"  data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 category-sidebar">
                    <div class="sidebar-title d-flex align-items-center justify-content-between">
                        <h4>{{__('messages.filter')}}</h4>
                        <div @click="resetFilter">
                            <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.1085 8.94037C18.1085 8.94037 14.9368 4.5 10.1792 4.5C8.07626 4.5 6.05943 5.3354 4.57242 6.82242C3.0854 8.30943 2.25 10.3263 2.25 12.4292C2.25 14.5322 3.0854 16.549 4.57242 18.036C6.05943 19.5231 8.07626 20.3585 10.1792 20.3585C12.9354 20.3585 15.363 18.851 16.7839 16.9429" stroke="#209EA4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19.801 5.65222L19.1194 10.1704L14.6035 9.46938" stroke="#209EA4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg> 
                        </div>                           
                    </div>
               
                    <div class="row">
                        <div class="form-group">
                            <label for="status" class="form-control-label mb-3">{{__('messages.filter_by_category')}}</label>
                            <multi-select
                                    :options="allcategory"
                                    :multiple="false"
                                    :searchable="false"
                                    :close-on-select="false"
                                    :show-labels="false"
                                    @input="handleFilterChange"
                                    placeholder="Select one"
                                    id="status" 
                                    v-model="filterData.category_id"
                                    label="name" 
                                    track-by="id" 
                            ></multi-select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="providers" class="form-control-label mb-3">{{__('messages.filter_by_providers')}}</label>
                            <multi-select
                                    deselect-label=""
                                    select-label=""
                                    @input="handleFilterChange"
                                    tag-placeholder="providers" id="providers" 
                                    v-model="filterData.provider_id"
                                    label="display_name" track-by="id" :options="allprovider"
                            ></multi-select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">                        
                            <div>
                                <h6 class="filter-title">{{__('messages.price')}}</h6>
                            </div>
                            <div class="price-rabge-box">
                                <div class="form-group mt-3 product-range">
                                    <div class="range-slider" ref="slider" id="product-price-range" @click="handleFilterChange"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small id="lower-value">{{currencySymobl}}{{minVal}}</small>
                                    <small id="upper-value">{{currencySymobl}}{{maxVal}}</small>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
               
                </div>
                <div class="col-lg-9">
                    <div v-if="loading" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                      <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
                    </div>
                 <div v-else-if="serviceList.length > 0" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                    
                        <div v-for="(data, index) in serviceList" :key="index" class="col">


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
                                    @service-list="getServiceList"
                                    :categoryName="data.category_name"
                                    :subcategoryName="data.subcategory_name"
                                />
                        </div>
                    </div>
                    <div v-else-if="serviceList.length == 0" class="text-center">
                        <p>{{__('messages.data_not_found')}}</p>
                    </div>
                    <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                      <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
                    </div>
                </div>
            </div>
        </div> 
     </section>
</div>
</template> 
<script>
import { mapGetters } from "vuex";
import noUiSlider from 'nouislider'
import {get} from '../../request'
export default {
    name:'Service',
    data(){
        return{
            loading: true,
            filterData: {},
            componentKey: 0,
            serviceList:[],
            baseUrl:window.baseUrl,
            maxVal:0,
	        minVal:0,
            ratingData:[
            {
            id:1,
            title: '1.0',
            value: 1
            },
            {
            id:2,
            title: '2.0',
            value: 2
            },
            {
            id:3,
            title: '3.0',
            value: 3
            },
            {
            id:4,
            title: '4.0',
            value: 4
            },
            {
            id:5,
            title: '5.0',
            value: 5
            },
        ]
        }
    },
    computed: {
        ...mapGetters(['allcategory','allprovider','currencySymobl'])
    },
    mounted(){
        this.filterData = this.defaultFilterData();
        this.getServiceList();
        this.loading = true;
        setTimeout(() => {
            this.pricesliderinit();
        }, 1000);
    },
    methods:{
        defaultFilterData: function () {
            return {
                category_id: {},
                provider_id:{},
                is_price_min: this.minVal,
                is_price_max: this.maxVal,
                is_rating: [],
                latitude:'',
                longitude:''
            }
        },  
        getServiceList(filterData=''){
            this.loading = true;
            var params= {
                per_page: "all"
            }
            var get_location_lat = localStorage.getItem('loction_current_lat')
            var get_location_long = localStorage.getItem('loction_current_long')
            var  latitude = ''
            var  longitude = ''
            if(get_location_lat  !='' && get_location_long != '' ){
                var  latitude = get_location_lat
                var  longitude = get_location_long
                var params= {
                    per_page: "all",
                    latitude:latitude,
                    longitude:longitude,
                }
            }
            
            if(filterData != '' ){
                var params ={
                    category_id:filterData.category_id.id,
                    provider_id:filterData.provider_id.id,
                    is_price_min:filterData.is_price_min,
                    is_price_max:filterData.is_price_max,
                    latitude:latitude,
                    longitude:longitude,
                    per_page: 'all'
                }
            }
            if(this.$store.state.user){
                 var params ={ 
                    customer_id: this.$store.state.user.id,
                    latitude:latitude,
                    longitude:longitude,
                    category_id:this.filterData.category_id != {} ? this.filterData.category_id.id :'',
                    provider_id:this.filterData.provider_id != {} ? this.filterData.provider_id.id : '',
                    is_price_min:this.filterData.is_price_min > 0 ? this.filterData.is_price_min: '',
                    is_price_max:this.filterData.is_price_max > 0 ? this.filterData.is_price_max: '',
                    per_page: 'all'
                 }
            }
            get("service-list",{
                params: params
            })
            .then((response) => {
                if(response.status === 200){

                    this.serviceList = response.data.data;
                    if(response.data.max !=null  && response.data.min !=null){

                        this.maxVal = response.data.max
                        this.minVal = response.data.min
                    }else{

                        this.maxVal = 0
                        this.minVal = 0

                    }
                  

                }else{
                    this.serviceList = [];
                }
                this.loading = false; 
            });
        },
         
        handleFilterChange(){
            var slidercurrentval=this.$refs.slider.noUiSlider.get()
            console.log(slidercurrentval);
            this.filterData.is_price_min= slidercurrentval[0]
            this.filterData.is_price_max= slidercurrentval[1]
            console.log(this.filterData.is_price_min);
            console.log(this.filterData.is_price_max);
            let filterData = Object.assign({}, this.filterData);
            this.getServiceList(filterData);
        },
        pricesliderinit(){

            noUiSlider.create(this.$refs.slider, {
                start: [this.minVal, this.maxVal],
                connect: true,
                range: {
                    min: this.minVal,
                    max: this.maxVal
                }
            });
        },
        resetFilter(){
           this.filterData.category_id = ''
           this.filterData.provider_id = ''
           this.filterData.is_price_min = ''
           this.filterData.is_price_max = ''
           this.getServiceList(this.filterData)
        }
    }
}
</script>

