<template>
    <nav class="nav navbar theme navbar-expand-xl bg-white iq-navbar navbar-scroll" aria-label="Main navigation">
        <div class="container-fluid p-0">
            <div class="header-left d-flex align-itens-center">
                <a href="#" class="navbar-brand">
                    <router-link :to="{ name: 'frontend-home' }">
                        <img v-if="generalsetting.site_logo" :src="generalsetting.site_logo" class="img-fluid logo" />
                        <img v-else :src="baseUrl + '/images/logo.svg'" class="img-fluid logo" />
                    </router-link>
                </a>
                <b-collapse id="navbarSupportedContent" is-nav>
                    <ul
                        class="top-menu navbar-nav ms-auto navbar-list mb-2 mb-lg-0"
                    >
                        <li class="nav-item">
                            <router-link :to="{ name: 'frontend-home' }"
                                         :class="(currentRouteName === 'frontend-home' ? activeRouteClass + ' nav-link' : 'nav-link' )"
                            >{{__('messages.home')}}</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'category' }"   :class="(currentRouteName === 'category' ? activeRouteClass + ' nav-link' : 'nav-link' )"
                            >{{__('messages.category')}}</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'service' }"   :class="(currentRouteName === 'service' || currentRouteName === 'service-detail' || currentRouteName === 'category-service'  || currentRouteName === 'provider-service' ? activeRouteClass + ' nav-link' : 'nav-link' )"
                            >{{__('messages.service')}}</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'blog' }"   :class="(currentRouteName === 'blog' ? activeRouteClass + ' nav-link' : 'nav-link' )"
                            >{{__('messages.blog')}}</router-link
                            >
                        </li>
                        <li  v-if="isLogged" class="nav-item"> 
                            <router-link :to="{ name: 'booking' }"   :class="(currentRouteName === 'booking' || currentRouteName === 'booking-detail'  ? activeRouteClass + ' nav-link' : 'nav-link' )"
                            >{{__('messages.booking')}}</router-link
                            >
                        </li>
                    </ul>
                </b-collapse>
            </div>
            <div class="header-right d-flex align-itens-center">
                <div class="location-device-lg" v-b-modal.nearlocation>
                    <div class="header-address">
                          <span class="icon">
                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 8.50051C11.5 7.11924 10.3808 6 9.00051 6C7.61924 6 6.5 7.11924 6.5 8.50051C6.5 9.88076 7.61924 11 9.00051 11C10.3808 11 11.5 9.88076 11.5 8.50051Z" stroke="#51529d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M8.99951 19C7.80104 19 1.5 13.8984 1.5 8.56329C1.5 4.38664 4.8571 1 8.99951 1C13.1419 1 16.5 4.38664 16.5 8.56329C16.5 13.8984 10.198 19 8.99951 19Z" stroke="#51529d" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                          </span>
                        <span class="ps-2">{{ __('messages.search_for_your_area') }}</span>
                    </div>
                </div>
                <div class="location-device-sm position-relative">
                    <div class="header-address" @click="$bvModal.show('nearlocation')">
                          <span class="icon" >
                            <svg width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 8.50051C11.5 7.11924 10.3808 6 9.00051 6C7.61924 6 6.5 7.11924 6.5 8.50051C6.5 9.88076 7.61924 11 9.00051 11C10.3808 11 11.5 9.88076 11.5 8.50051Z" stroke="#1C1F34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                              <path fill-rule="evenodd" clip-rule="evenodd" d="M8.99951 19C7.80104 19 1.5 13.8984 1.5 8.56329C1.5 4.38664 4.8571 1 8.99951 1C13.1419 1 16.5 4.38664 16.5 8.56329C16.5 13.8984 10.198 19 8.99951 19Z" stroke="#1C1F34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                          </span>
                        <div class="location-content">
                            <span>{{ __('messages.search_for_your_area') }}</span>
                        </div>
                    </div>
                </div>
                <div class="input-group search-text search-device-lg" style="width: auto;">
                    <input type="text" :placeholder="__('messages.search')" class="form-control nav-item dropdown" @keyup="getServiceList" v-model="keyword"  data-bs-toggle="dropdown" aria-expanded="false"> 
                    <svg width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="search-icon">
                    <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle> 
                    <path d="M18.0186 18.4851L21.5426 22" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                     <ul :class="`top-width dropdown-menu dropdown-menu-end user-dropdown ${keyword.length > 0 ? 'show' : ''}`" aria-labelledby="dropdownMenuButton1">
                    <li>
                    <div v-if="serviceList.length > 0 && !show" class="searchbox-datalink global-search-result show-data" :key="componentKey">
                        <ul class="iq-post p-3">
                            <div>
                            <li v-for="service in serviceList" :key="service.id" class="mr-0 mb-3 pb-0 d-block">
                                <router-link
                                :to="{
                                    name: 'service-detail',
                                    params: { service_id: service.id },
                                }"
                                >
                                <div class="post-img d-flex align-items-center">
                                <div class="post-img-holder">
                                    <a href="img-height">
                                    <img :src="service.attchments[0] ? service.attchments[0] : baseUrl+'/images/default.png'" alt="">
                                    </a>
                                </div>
                                <div class="post-blog pl-2">
                                    <div class="blog-box">
                                    <a class="nav-link">{{service.name}}</a>
                                    </div>
                                </div>
                                </div>
                                </router-link>
                            </li>
                            </div>
                        </ul>
                    </div>
                    </li>
                    <li>
                    <div  v-if="show && serviceList.length == 0" class="searchbox-datalink global-search-result show-data" >
                        <ul class="iq-post">
                            <div>
                                {{__('messages.record_not_found')}}
                            </div>
                        </ul>
                    </div>
                    </li>
                     </ul>
                </div>
                <div class="d-flex align-items-center">
                    <div class="search-text position-relative search-device-sm cursor-pointer">
                        <i class="fas fa-search s-icon"></i>
                        <div class="search-content">
                            <form>
                                <div class="input-group  mb-0">
                                    <input type="text" placeholder="Search" class="form-control" @keyup="getServiceList" v-model="keyword" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="search-icon">
                                        <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
                                        <path d="M18.0186 18.4851L21.5426 22" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <ul :class="`top-width dropdown-menu dropdown-menu-end user-dropdown ${keyword.length > 0 ? 'show' : ''}`" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                    <div v-if="serviceList.length > 0 && !show" class="searchbox-datalink global-search-result show-data" :key="componentKey">
                                        <ul class="iq-post p-3 scrollable">
                                            <div>
                                            <li v-for="service in serviceList" :key="service.id" class="mr-0 mb-3 pb-0 d-block">
                                                <router-link
                                                :to="{
                                                    name: 'service-detail',
                                                    params: { service_id: service.id },
                                                }"
                                                >
                                                <div class="post-img d-flex align-items-center">
                                                <div class="post-img-holder">
                                                    <a href="img-height">
                                                    <img :src="service.attchments[0] ? service.attchments[0] : baseUrl+'/images/default.png'" alt="">
                                                    </a>
                                                </div>
                                                <div class="post-blog pl-2">
                                                    <div class="blog-box">
                                                    <h6>{{service.name}}</h6>
                                                    </div>
                                                </div>
                                                </div>
                                                </router-link>
                                            </li>
                                            </div>
                                        </ul>
                                    </div>
                                    </li>
                                    <li>
                                    <div  v-if="show && serviceList.length == 0" class="searchbox-datalink global-search-result show-data" >
                                        <ul class="iq-post">
                                            <div>
                                                {{__('messages.record_not_found')}}
                                            </div>
                                        </ul>
                                    </div>
                                    </li>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="user">
                    <button class="user-menu" id="user-menu"><i aria-hidden="true" alt="user" class="far fa-user"></i>
                    </button>
                    <div id="user-menu-dropdown">
                        <ul class="list-unstyled mb-0">
                            <li v-if="!isLogged" v-b-modal="'my-modal1'" class="list-link"><a  href="#">{{__('messages.register')}}</a></li>
                            <li  v-if="!isLogged" @click="login" class="list-link"><a  href="#">{{__('messages.login')}}</a></li>
                            <li v-if="isLogged">
                                <a href="#" class="dropdown-item" @click="getHomePage()"
                                >{{__('messages.dashboard')}}</a
                                >
                            </li>
                            <li v-if="isLogged">
                                <a href="#" class="dropdown-item" @click="logout"
                                >{{__('messages.logout')}}</a
                                >
                            </li>
                        </ul>       
                    </div>
                </div>

                    <button class="navbar-toggler" v-b-toggle.navbarSupportedContent type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                      </span>
                    </button>
                </div>


                <div class="d-flex align-items-center header-buttons header-buttons-desktop ">
                  <button class="btn btn-primary btn-register" v-b-modal="'my-modal1'" v-if="!isLogged">
                      {{__('messages.register')}}
                  </button>
                  <register/>
                  <button v-if="!isLogged" class="btn btn-primary ml-3" @click="login" href="#">
                    {{__('messages.login')}}
                  </button>
                  <login-component ref="openModal" />
                   <ul class="list-inline mb-0 user-profile">
                        <li v-if="isLogged" class="nav-item dropdown">
                            <a
                            href="#"
                            class="nav-link search-toggle p-0"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            @click="openDropdown"
                            >
                            <img
                                :src="userData.profile_image ? userData.profile_image : baseUrl+'/images/user/user.png'"
                                alt="User-Profile"
                                class="img-fluid user-image avatar avatar-rounded"
                            />
                            <span>{{userData.display_name}}</span>
                            </a>
                            <ul
                            :class="`dropdown-menu dropdown-menu-end user-dropdown ${dropdownClass ? 'show' : ''}`"
                            aria-labelledby="dropdownMenuButton1"
                            >
                            <li>
                                <router-link class="dropdown-item" :to="{ name: 'user-favourite' }"
                                ><i class="fas fa-star me-2 fa-sm"></i>{{__('messages.favorite_list')}}</router-link
                                >
                            </li>
                            <li>
                                <a href="#" class="dropdown-item" @click="getHomePage()"
                                ><i class="fas fa-chart-bar me-2 fa-sm"></i>{{__('messages.dashboard')}}</a
                                >
                            </li>
                            <li>
                                <a href="#" class="dropdown-item" @click="logout"
                                ><i class="fas fa-sign-out-alt me-2 fa-sm"></i>{{__('messages.logout')}}</a
                                >
                            </li>
                            </ul>
                        </li>
                    </ul>
                </div>

                

            </div>
        </div>
        <b-modal id="nearlocation"  title="Get Nearest Services" header-close-content=" <button type='button' class='btn-close btn-sm' data-bs-dismiss='modal' aria-label='Close'></button>">
            <form class="review-form">
                <p v-if="showofflocation">{{__('messages.location_off')}}</p>
                <p v-else>{{__('messages.location_on')}}</p>
                <div class="row row-cols-2 pt-4">
                    <div class="col">
                        <button type="submit" class="btn btn-secondary w-100" @click="$bvModal.hide('nearlocation')">{{__('messages.cancel')}}</button>
                    </div>
                    <div v-if="showofflocation" class="col">
                        <button type="button" class="btn btn-primary w-100" @click="removeCurrentLocation" >{{__('messages.offlocation')}}</button>
                    </div>
                    <div v-else class="col">
                        <button type="button" class="btn btn-primary w-100" @click="getServices">{{__('messages.save')}}</button>
                    </div>
                </div>
            </form>  
        </b-modal>
    </nav>
</template>
<script>
import {mapGetters} from "vuex";
import Register from '../../views/Auth/Register.vue';
import { get } from '../../request';
export  default {
  components: { Register },
    name:'Header',
    data(){
        return{
            dropdownClass: false,
            searchdropdownClass: false,
            baseUrl:window.baseUrl,
            activeRouteClass: 'active',
            keyword:"",
            serviceList:[],
            show:false,
            componentKey: 0,
            currentAddress: 'Search for your area',
            showclass:'',
            showofflocation: false,
            is_location_on: false
        }
    },
    mounted() {
        this.show = false
        // jQuery("#user-menu").click(function(){
        //     jQuery("#user-menu-dropdown").addClass("slow");
        // });
        jQuery(window).on('scroll', function (e) {
            if (jQuery(this).scrollTop() > 10) {
                jQuery('.navbar').addClass('menu-sticky');
            } else {
                jQuery('.navbar').removeClass('menu-sticky');
            }
        });
        jQuery(".search-device-sm .s-icon").on("click", function(e) {
            jQuery(".search-device-sm").toggleClass('search-open');
        });
        jQuery(document).on("click", function(e) {
            
           if(!(jQuery(e.target).is('.search-device-sm .form-control')|| jQuery(e.target).is('.search-device-sm .s-icon') || jQuery(e.target).is('.search-device-sm'))){
                jQuery(".search-device-sm").removeClass('search-open');
           }
    
           if(e.target.parentElement.id=="user-menu"){
            jQuery("#user-menu-dropdown").toggleClass("user-menu-open");
           }else{
            jQuery("#user-menu-dropdown").removeClass("user-menu-open");
           }
        });
        document.addEventListener('click', this.close)
        this.is_location_on =  localStorage.getItem('is_location_on')
    },
    computed:{
        ...mapGetters(["isLogged","userData",'generalsetting']),
        currentRouteName() {
            return this.$route.name;
        }
    },
    methods:{
        openDropdown() {
            this.dropdownClass = !this.dropdownClass;
        },
        close (e) {
            if (!this.$el.contains(e.target)) {
                this.dropdownClass = false
            }
        },
        logout() {
            this.$store.dispatch("logout");
        },
        getHomePage(){
            window.location.href = baseUrl + "/home";
        },
        login(){
            this.$refs.openModal.show();
        },
        forceRerender() {
            this.componentKey += 1;  
        },
        getServices(){
            navigator.geolocation.getCurrentPosition(position => {
                localStorage.setItem('loction_current_lat', position.coords.latitude)
                localStorage.setItem('loction_current_long',position.coords.longitude)
                localStorage.setItem('is_location_on', 'on')
                this.$router.push({name:'service'})
                this.showofflocation = true
            }
          )
            this.$bvModal.hide('nearlocation')
        },
        getServiceList() {
            var params= {
                per_page: "all",
            }
            get('service-list',{
                params: params
            })
                .then((response) => {
                if(this.keyword){
                    this.show = false
                    this.serviceList = response.data.data.filter(service =>
                        service.name.toLowerCase().includes(this.keyword.toLowerCase().split(' ')) 
                    );
                    if(this.serviceList.length == 0){
                        this.serviceList = []
                        this.show = true
                    }
                }else{
                    this.show = true
                    this.forceRerender()
                }
            });
        },
        removeCurrentLocation(){
            window.location.reload()
            var params= {
                per_page: "all",
            }
            get('service-list',{
                params: params
            })
            .then((response) => {
                this.serviceList = response.data.data
                this.forceRerender()
            });
            localStorage.removeItem('loction_current_lat')
            localStorage.removeItem('loction_current_long')
            localStorage.setItem('is_location_on','off')
            this.$bvModal.hide('nearlocation')
        }
    }
}
</script>
<style scoped>
.top-width{
    top: 3rem;
    width: 17rem
}
.img-50{
    max-width: 50%;
}
</style>
