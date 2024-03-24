<template>
<div>
    <breadcrumb :sectionName="this.$route.meta.label" :homeName="this.$route.meta.homeName" />
    <section class="our-category our-category-lists" data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
        <div class="container">
            <div v-if="loading" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
            </div>
            <div v-else-if="category.length > 0"  class="row">
                <div class="mar-top mar-bot category-box">
                    <ul class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 list-inline">
                        <li  v-for="(data, index) in category" :key="index" class="col mb-4">
                                <router-link :to="{name: 'category-detail',params: { category_id: data.id,category_name:data.name }}">
                                <div class="card mb-0 bg-soft-primary circle-clip-effect bg-img undefined">
                                    <div class="card-body service-card undefined">
                                        <img :src="data.category_image" alt="image" class="img-fluid "> 
                                    </div>                                
                                </div>
                                <div class="card-body category-content undefined text-center">
                                    <h5 class="categories-name">
                                        {{ data.name }}
                                    </h5>
                                    <p class="category-desc mb-0">{{ data.description | desc_limit(30) }}</p>
                                </div>
                            </router-link >
                        </li>
                    </ul>
                </div>
            </div>
       
            <div v-else-if="category.length == 0" class="text-center">
                        <p>{{__('messages.data_not_found')}}</p>
                    </div>
            <div v-else class="row">
                <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
            </div>
        </div>
    </section>
</div>
</template>
<script>
    import {get} from '../../request'
export default {
    name:'Category',
    data(){
        return{
            loading: true,
            category: [],
            baseUrl:window.baseUrl,
        }
    },
    mounted(){
        this.getCategoryList();
        this.loading = true;
    },
    filters: {
        desc_limit: function (value, size) {
            if (!value) return '';
            value = value.toString();

            if (value.length <= size) {
                return value;
            }
            return value.substr(0, size) + '...';
        }
    }, 
    methods:{
        getCategoryList(){
            this.loading = true;
            get("category-list", {
                params: {
                per_page: "all",
                },
            }).then((response) => {
                if(response.status === 200){
                    this.category = response.data.data;
                }
                this.loading = false;
            });
        }
    }
}
</script>

