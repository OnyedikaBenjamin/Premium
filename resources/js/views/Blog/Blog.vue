
<template>
<div>
     <breadcrumb :sectionName="this.$route.meta.label" :homeName="this.$route.meta.homeName" />
     <section class="our-service our-service-lists mar-top mar-bot editors"  data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div v-if="loading" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                      <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
                    </div>
                    <div v-else-if="blogList.length > 0" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                        <div v-for="(data, index) in blogList" :key="index" class="col">
                            <router-link :to="{name: 'blog-detail',params: { blog_id: data.id,blog_name:data.title }}">
                            <div class="card">
                                    <div class="iq-image position-relative">
                                        <div class="img">
                                            <img :src="data.attchments[0] ? data.attchments[0] : baseUrl+'/images/default.png'" alt="image" class="img-fluid w-100 "/>
                                        </div>
                                        <span class="badge badge-2 bg-primary"><i class="fa fa-eye" aria-hidden="true"></i> {{ data.total_views }}</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="content-title mb-3">
                                            <h5 class="service-title" data-bs-toggle="tooltip" data-bs-placement="top" :title="data.title">{{ data.title }}</h5>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <div class="user-info d-flex align-items-center">
                                                <img :src="data.author_image" alt="image" class="img-fluid avatar avatar-35 avatar-rounded"/>
                                                <span class="ms-2">{{ data.author_name }}</span>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            </router-link>
                        </div>
                    </div>

                    <div v-else-if="blogList.length == 0" class="text-center">
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
import {get} from '../../request'
export default {
    name:'Service',
    data(){
        return{
            loading: true,
            blogList:[],
            baseUrl:window.baseUrl
        }
    },
    mounted(){
        this.getblogList();
        this.loading = true;
    },
    methods:{
        getblogList(){
            this.loading = true;
            var params= {
                per_page: "all"
            }
            get("blog-list",{
                params: params
            })
            .then((response) => {
                if(response.status === 200){
                    this.blogList = response.data.data;
                }else{
                    this.blogList = [];
                }
                this.loading = false;
            });
        }
    }
}
</script> 

