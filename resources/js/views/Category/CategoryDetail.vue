<template>
    <div>
        <breadcrumb :sectionName="this.$route.params.category_name" :homeName="this.$route.meta.homeName" />
        <section class="category-detail mar-top mar-bot" data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
             <div class="container">
               <div class="row justify-content-center">
                    <div class="col-lg-3" v-if="category.description">
                        <div class="category-info">
                            <h4 class="cat-title">{{__('messages.description')}}</h4>
                            <div class="cat-desc">
                                <p>{{category.description}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 mt-3 mt-lg-0">
                        <div class="our-service">
                            <div v-if="subCategoryList.length > 0" class=" blog-tab col-xl-12">
                                <div class="tab-lists">
                                    <div class="left" @click="slide('left')"><i class="fas fa-chevron-left"></i></div>
                                    <div class="tab-container">
                                        <a @click="getServiceList()" :class="{ 'active': activeSubcategory === '' }" class="cursor-pointer">{{__('messages.all')}}</a>
                                        <a v-for="(subcatgory,i) in subCategoryList" :key="i" @click="getServiceList(subcatgory.id)" :class="{ 'active': activeSubcategory === subcatgory.id }" class="cursor-pointer">{{subcatgory.name}}</a>
                                    </div>
                                    <div class="right"  @click="slide('right')"><i class="fas fa-chevron-right"></i></div>
                                </div>
                            </div>
                            <div v-if="serviceList.length > 0" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
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
             </div>
        </section>
    </div>
</template>
<script>
import { mapGetters } from "vuex";
import {get} from '../../request'
export default {
    name:'CategoryDetail',
    data(){
        return{
            category: {},
            baseUrl:window.baseUrl,
            serviceList:[],
            subCategoryList:[],
            activeSubcategory: null,

        }
    },
    mounted(){
        this.getCategoryData();
        this.getServiceList();
    },
    computed: {
        ...mapGetters(['allcategory'])
    },
    methods:{
         slide(direction){
            var scrollCompleted = 0;
            var container = document.getElementsByClassName('tab-container');
            var slideVar = setInterval(function () {
                if (direction == 'left') {
                    container[0].scrollLeft -= 20;
                } else {
                    container[0].scrollLeft += 20;
                }
                scrollCompleted += 10;
                if (scrollCompleted >= 100) {
                    window.clearInterval(slideVar);
                }
            }, 40);
        },
        defaultCategoryModel(){
            return {
                id: '',
                name:'',
                description: '',
                is_featured:'',
                color:'',
                category_image:'',
                category_extension:'',
                services:'',
                status:''
            }
        },
        getCategoryData() {
            var category_id = this.$route.params.category_id;
            var foundCategory = this.allcategory.find((cat) => cat.id == category_id);
            
            if (foundCategory) {
                this.category = foundCategory;
                this.$route.params.category_name = foundCategory.name;
            }
            
            this.getsubCategoryData(category_id);
        },
        // getCategoryData(){
        //     this.allcategory.find((cat) =>{
        //         if(cat.id == this.$route.params.category_id){
        //             this.category = cat
        //         }
        //     });
        //     this.getsubCategoryData(this.$route.params.category_id)
        // },
        getsubCategoryData(category_id){
            var params= {
                per_page: "all",
                category_id: category_id
            }
            get("subcategory-list",{
                params: params
            })
            .then((response) => {
                if(response.status === 200){
                    this.subCategoryList = response.data.data;
                }else{
                    this.subCategoryList = [];
                }
            });
        },
        getServiceList(subcat=''){
            var params= {
                per_page: "all",
                category_id: this.$route.params.category_id
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
            if(subcat!=''){
                 var params= {
                    per_page: "all",
                    subcategory_id: subcat
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
            this.activeSubcategory = subcat;
        }
    }
}
</script>