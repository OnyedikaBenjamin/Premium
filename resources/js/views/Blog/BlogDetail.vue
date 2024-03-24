<template>
    <div>
        <breadcrumb :sectionName="this.$route.params.blog_name" :homeName="this.$route.meta.homeName" />
        <section class="category-detail mar-top mar-bot" data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
             <div class="container">
               <div class="row justify-content-center">
                    <div class="col-lg-6" v-if="blog.description">
                        <div class="category-info">
                            <h4 class="cat-title">{{__('messages.description')}}</h4>
                            <div class="cat-desc">
                                <p>{{blog.description}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-3 mt-lg-0">
                        <div class="our-service">
                            <div v-if="blog.attchments.length >0"   class="service-gallery service-top-space">
                                <div class="gallery-box">
                                    <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2">
                                        <div  v-for="(blogimage,index) in blog.attchments" :key="index" class="col">
                                            <div class="card iq-file-manager mb-lg-0">
                                                <div class="card-body p-0">
                                                    <a data-fslightbox="gallery" :href="blogimage">
                                                        <img :src="blogimage" class="img-fluid rounded" alt="">
                                                    </a>                           
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                             
                            </div>
                        </div>
                    </div>
                  </div>
             </div>
        </section>
    </div>
</template>
<script>
import {post} from '../../request'
export default {
    name:'BlogDetail',
    data(){
        return{
            blog: {},
            baseUrl:window.baseUrl,
        }
    },
    mounted(){
        console.log('in');
        this.blogDetail();
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
        blogDetail(){
            post("blog-detail", {
                blog_id: this.$route.params.blog_id,
                customer_id: this.$store.state.user ? this.$store.state.user.id : ''

            })
            .then((response) => {
                this.blog = response.data.blog_detail;
                console.log( this.blog);

            });
        }
    }
}
</script>