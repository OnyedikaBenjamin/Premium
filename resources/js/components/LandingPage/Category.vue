<template>
  <section class="our-category  padding-top padding-bot" data-iq-gsap="onStart" data-iq-position-y="70" data-iq-rotate="0" data-iq-trigger="scroll" data-iq-ease="power.out" data-iq-opacity="0">
    <div class="container">
        <div  class="category-box">
          <div class="header-title d-flex justify-content-between align-items-center">
            <h3 class="title">{{__('messages.category')}}</h3>
            <router-link :to="{ name: 'category' }" class="link-btn-box"
              ><span>{{__('messages.see_all')}}</span>
            </router-link>
          </div>
          
          <ul v-if="category.length > 0" class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-6 list-inline mb-0">
            <li class="col mar-bot-res" v-for="(cat, index) in category" :key="index">
              <router-link
                :to="{
                  name: 'category-detail',
                  params: { category_id: cat.id },
                }"
              >
                <div :class="`card text-center bg-transparent circle-clip-effect mb-0`" >
                    <div :class="`card-body service-card `">
                        <img :src="cat.category_image" alt="image" class="img-fluid">
                        <h6 class="categories-name">
                            {{cat.name}}  
                        </h6>
                    </div>
                </div>
              </router-link>
            </li>
          </ul>

          <div v-else-if="category.length == 0" class="text-center">
                        <p>{{__('messages.data_not_found')}}</p>
                    </div>

          <div v-else class="row">
            <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
        </div>
        </div>
    </div>
  </section>
</template>
<script>
import { mapGetters } from "vuex";
export default {
    name:'Category',
    data() {
        return {
            baseUrl:window.baseUrl
        };
    },
    computed: {
        ...mapGetters(["category"]),
    },
}
</script>
