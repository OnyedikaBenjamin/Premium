<template>
<section class="main-breadcrumb light-background">
    <div class="container">
        <h3 class="text-center mb-0 title">{{homeName}}</h3>
        <nav class="d-flex justify-content-center align-items-center">
            <ol class="breadcrumb align-items-center p-0 justify-content-center text-center">
                <li class="breadcrumb-item">
                    <router-link  :to="{ name: 'frontend-home' }"  class="pe-3">{{__('messages.home')}}</router-link>
                </li>
                <li>
                <span class="sepretor"></span>
                </li>
                <li class="breadcrumb-item ps-3" aria-current="page">{{sectionName}}</li>
            </ol>
        </nav>
        <div class="pattern-image">
         <img :src="baseUrl+'/images/frontend/breadcrumb-bg.png'">
        </div>
    </div>
</section>
</template>
<script>
export default {
    name:'BreadCrumb',
    data(){
      return{
          baseUrl:window.baseUrl,
      }
    },
    props:{
      sectionName:{ type: String},
      homeName:{ type: String},
    },
}
</script>

