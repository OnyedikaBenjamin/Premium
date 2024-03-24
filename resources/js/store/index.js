import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import router from '../router'
import {post,get} from '../request'

Vue.use(Vuex)
export default new Vuex.Store({
    state:{
        user: null,
        configurations:[],
        currencyposition: 'right',
        currencysymbol: '$',
        currencyCode: '',
        discounttype:'%',
        generalsetting:[],
        appsetting:{},
        privacy_policy:{},
        term_conditions:{},
        help_support:{},
        refund_policy:{},
        slider:{},
        category:{},
        service:{},
        provider:{},
        discountservice:{},
        customerreview:{},
        topratedservice:{},
        featuredservice:{},
        appdownload:[],
        allcategory:[],
        allprovider:[],
        googlemapkey:'',
        latitude:'',
        longtitude:'',
    },
    mutations:{
        setUserData(state, userData) {
            if(userData.data.user_type == 'user'){
                state.user = userData.data
                state.auth = true
                localStorage.setItem('user', JSON.stringify(userData))
                axios.defaults.headers.common.Authorization = `Bearer ${userData.data.api_token}`
            }
        },
        clearUserData() {
            localStorage.removeItem('user')
            router.push({ name: 'frontend-home' })
            location.reload()
        },
        setDashboardData(state, data){
            state.configurations =  data.configurations
            state.generalsetting = data.generalsetting
            state.appsetting = data.app_setting
            state.privacy_policy = data.privacy_policy
            state.term_conditions = data.term_conditions
            state.help_support = data.help_support
            state.refund_policy = data.refund_policy
            state.featuredservice = data.featured_service
            state.slider = data.slider
            state.category = data.category
            state.service = data.service
            state.provider = data.provider
            state.discountservice = data.discount_service
            state.customerreview = data.customer_review
            state.topratedservice = data.top_rated_service
            state.appdownload = data.app_download
            data.configurations.filter(function(elem){
                if(elem.key === 'CURRENCY_POSITION'){
                    state.currencyposition  = elem.value
                }
                if(elem.key === 'CURRENCY_COUNTRY_ID'){
                    state.currencysymbol  = elem.country.symbol
                    state.currencyCode  = elem.country.currency_code
                }
                if(elem.key === "MAPKEY_GOOGLE_MAP_KEY"){
                    state.googlemapkey  = elem.value
                }
                if(elem.key === "MAPKEY_LATITUDE"){
                    state.latitude  = elem.value
                }
                if(elem.key === "MAPKEY_LONGTITUDE"){
                    state.longtitude  = elem.value
                }
            });
            
        },
        setcategoryData(state,data){
            state.allcategory =  data.data
        },
        setproviderData(state,data){
            state.allprovider =  data.data
        }
    },
    actions:{
        login({ commit }, credentials) {
            return post('login', credentials)
                .then(({ data }) => {
                    commit('setUserData', data)
                })
        },
        register({ commit }, data) {
            data.loginfrom = 'vue-app';
            return post('register', data)
                .then(({ data }) => {
                    commit('setUserData', data)
                })
        },
        logout({ commit }) {
            commit('clearUserData')
        },
        dashboardData({commit}){
            var get_location_lat = localStorage.getItem('loction_current_lat')
            var get_location_long = localStorage.getItem('loction_current_long')
            var params= ''
            if(get_location_lat  !='' && get_location_long != '' ){
                var  latitude = get_location_lat
                var  longitude = get_location_long
                var params= {
                    latitude:latitude,
                    longitude:longitude,
                }
            }
            return get('dashboard-detail',{
                params: params
            })
                .then(({ data }) => {
                    commit('setDashboardData', data)
            })
        },
        categoryData({commit}){
             get('category-list',{
                params: {
                  per_page: "all"
                }
              })
            .then(({ data }) => {
              commit('setcategoryData', data)
            })
          },
        providerData({commit}){
            get("user-list", {
              params: {
                user_type: "provider",
                per_page: "all"
              },
            })
            .then(({ data }) => {
              commit('setproviderData', data)
            })
        }
    },
    getters:{
        isLogged: state => !!state.user,
        userData: state => state.user,
        configurations: state=> state.configurations,
        currencyPostion:state => state.currencyposition,
        currencySymobl:state => state.currencysymbol,
        currencyCode:state=> state.currencyCode,
        discountType:state=> state.discounttype,
        generalsetting: state => state.generalsetting,
        appsetting:state => state.appsetting,
        privacy_policy:state=>state.privacy_policy,
        term_conditions:state=>state.term_conditions,
        help_support:state=>state.help_support,
        refund_policy:state=>state.refund_policy,
        slider:state=>state.slider,
        category:state=>state.category,
        service:state=>state.service,
        discountservice:state=>state.discountservice,
        customerreview:state=>state.customerreview,
        topratedservice:state=>state.topratedservice,
        featuredservice:state=>state.featuredservice,
        provider:state=>state.provider,
        allprovider:state=>state.allprovider,
        allcategory:state=>state.allcategory,
        googleMapKey:state => state.googlemapkey,
        latitude:state => state.latitude,
        longtitude:state => state.longtitude,
        appdownload:state => state.appdownload,
        
    }
});
