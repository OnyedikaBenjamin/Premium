<template>
<div>
<breadcrumb  :sectionName="this.$route.params.serviceName" :homeName="this.$route.meta.homeName"/>
    <section class="container gallery mar-top mar-bot" >
        <div class="row">
            <div class="col-lg-12 about-images">
                <div v-if="this.$route.params.galleryimg" class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
                    <div class="col" v-for="(image,index) in this.$route.params.galleryimg" :key="index">
                        <div class="card iq-file-manager">
                            <div class="card-body p-0">
                                <a data-fslightbox="gallery" :href="image">
                                    <img :src="image" class="img-fluid w-100 rounded" alt="">
                                </a>                           
                            </div>
                        </div>
                    </div>
                </div>  
                <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
                    <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
                </div>         
            </div>
        </div>
    </section>
</div>
</template>
<script>
export default {
    name:'Gallery',
     data(){
        return{
            baseUrl:window.baseUrl,
        }
    }
}
</script>