<template>
<div>
    <breadcrumb :sectionName="this.$route.meta.label" :homeName="this.$route.meta.homeName" />
    <div class="container mar-top mar-bot">
        <div class="row" >
            <div class="col-lg-3">
                <div class="booking-sidebar">
                    <h5 class="mb-3">{{__('messages.filter')}}</h5>
                    <div class="search-text sidebar-search">
                        <form>
                            <div class="input-group mb-0">
                                <input type="text" placeholder="Search" class="form-control"  @keyup="getBookingList" v-model="filterData.keyword"> 
                                <div class="search-icon">
                                    <svg width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle> 
                                    <path d="M18.0186 18.4851L21.5426 22" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>                                
                            </div>                
                        </form>
                    </div> 
                    <div class="separator"></div> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="status" class="form-control-label mb-3">{{__('messages.filter_by_status')}}</label>
                                <multi-select
                                    :options="bookingStatus"
                                    :multiple="false"
                                    :searchable="false"
                                    :show-labels="false"
                                    @input="handleFilterChange"
                                    placeholder="Select one"
                                    id="status" 
                                    v-model="filterData.status"
                                    label="label" 
                                    track-by="id" 
                            ></multi-select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 mt-lg-0 mt-5">
                <div v-if="loading" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                    <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
                </div> 
                <div v-else-if="bookingList.length > 0">
                    <div v-for="(data, index) in bookingList" :key="index" class="booking-wrapper">
                        <div  class="row" >
                            <div class="col-md-5">
                                <div class="book-img-box">
                                    <img  @click="$bvModal.show('booking-detail'+index)" :src="data.service_attchments[0] ? data.service_attchments[0] : baseUrl+'/images/default.png'" alt="image" class="img-fluid cursor-pointer" data-bs-toggle="modal" data-bs-target="#booking-detail"> 
                                    <span class="badge badge-1 rounded-pill bg-primary price-box">{{ data.price_formate }}</span>
                                </div>
                            </div>
                            <div class="col-md-7 mt-md-0 mt-4">
                                <div class="booking-content">
                                    <div class="ratting-value"> 
                                            <rating :readonly = true :showrating ="false" :ratingvalue="data.total_rating" />
                                    </div>
                                <h5 class="b-title cursor-pointer mt-2" data-bs-toggle="modal" data-bs-target="#booking-detail" @click="$bvModal.show('booking-detail'+index)">#{{ data.id }} {{ data.service_name }}</h5>
                                <div class="label-data">
                                    <h6 class="text-uppercase label-text">{{__('messages.booking_info')}}</h6>
                                </div>
                                    <p class="mb-2"><span class="hightlight-text">{{__('messages.location')}}:</span> <span>{{ data.address ? data.address : '-'  }}</span></p>
                                    <div class="row">
                                        <div class="col-lg-6">                                       
                                            <p class="mb-2"><span class="hightlight-text">{{__('messages.status')}}:</span><span>{{ data.status }}</span></p>  
                                            <p class="mb-0"><span class="hightlight-text">{{__('messages.provider')}}: </span> <span>{{data.provider_name}}</span></p>  
                                        </div>
                                    </div>
                                    <div class="separator"></div>
                                    <div class="label-data">
                                    <h6 class="text-uppercase label-text">{{__('messages.payment_info')}} </h6>
                                </div>
                                <div class="row">                               
                                        <div class="col-lg-6">
                                            <p class="mb-2"><span class="hightlight-text">{{__('messages.payment_status')}}: </span><span>{{data.payment_status ? data.payment_method : __('messages.pending')}}</span></p>  
                                            <p class="mb-0"><span class="hightlight-text">{{__('messages.payment_method')}}: </span><span>{{data.payment_method ? data.payment_method : __('messages.cash')}}</span></p>  
                                        </div>
                                    </div> 
                                    <div v-if="data.payment_id !== null && data.status == 'completed'" class="booking-btn-link" data-bs-toggle="modal" data-bs-target="#addreview" @click="giveRate(data)"> {{__('messages.rate_now')}} </div>                           
                                </div>
                            </div>
                        </div>
                        <b-modal :id="'booking-detail'+index"  modal-class="modal fade booking-detail" :title="'#'+data.id" header-close-content=" <button type='button' class='btn-close btn-sm' data-bs-dismiss='modal' aria-label='Close'></button>">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12">
                                    <div class="booking-image">
                                        <img :src="data.service_attchments[0] ? data.service_attchments[0] : baseUrl+'/images/default.png'" alt="image" class="img-fluid pb-3">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12">
                                    <div class="booking-details">
                                        <div class="booking-title mb-2">
                                            <h4>{{ data.service_name }}</h4>
                                        </div>
                                        <div class="booking-information">
                                            <div class="booking-informatio-lists d-flex align-items-center justify-content-between">
                                                <span class="hightlight-text">Date</span>
                                                <span class="text-end">{{data.date}}</span>
                                            </div>
                                            <div class="booking-informatio-lists d-flex align-items-center justify-content-between">
                                                <span class="hightlight-text">{{__('messages.location')}}</span>
                                                <span class="text-end">{{ data.address ? data.address : '-'  }}</span>
                                            </div>
                                            <div class="booking-informatio-lists d-flex align-items-center justify-content-between">
                                                <span class="hightlight-text">{{__('messages.service_status')}}</span>
                                                <span class="text-end">{{ data.status }}</span>
                                            </div>
                                            <div class="booking-informatio-lists d-flex align-items-center justify-content-between">
                                                <span class="hightlight-text">{{__('messages.provider')}}</span>
                                                <span class="text-end">{{data.provider_name}}</span>
                                            </div>
                                            <div class="booking-informatio-lists d-flex align-items-center justify-content-between">
                                                <span class="hightlight-text">{{__('messages.payment_status')}}</span>
                                                <span class="text-end">{{data.payment_status ? data.payment_method : __('messages.pending')}}</span>
                                            </div>
                                            <div class="booking-informatio-lists d-flex align-items-center justify-content-between">
                                                <span class="hightlight-text">{{__('messages.quantity')}}</span>
                                                <span class="text-end">{{data.quantity?data.quantity:'0'}}</span>
                                            </div>   
                                            <div class="booking-informatio-lists d-flex align-items-center justify-content-between">
                                                <span class="hightlight-text">{{__('messages.sub_total')}}</span>
                                                <span class="text-end">{{data.amount?data.amount:'0'}}</span>
                                            </div>   
                                            <div class="booking-informatio-lists d-flex align-items-center justify-content-between">
                                                <span class="hightlight-text">{{__('messages.extra_charge')}}</span>
                                                <span class="text-end"> {{currencyCode}} {{data.extra_charges_value}}</span>
                                            </div>           
                                        </div>
                                        <div class="bg-primary total-amt d-flex align-items-center justify-content-between p-3">
                                            <span class="hightlight-text text-white">{{__('messages.total_amount')}}</span>
                                            <span class="text-end text-white ">{{currencyCode}} {{ data.total_amount + data.extra_charges_value }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                    </b-modal>
                        <b-modal :id="'addreview'+data.id"   modal-class="modal fade" title="Add Review" header-close-content=" <button type='button' class='btn-close btn-sm' data-bs-dismiss='modal' aria-label='Close'></button>">
                            <form class="review-form">
                                <div class="row">
                                    <div class="col-lg-12">
                                    <div class="ratting-box">
                                        <div class="d-flex align-items-center">
                                            <span class="list-label">{{__('messages.your_rating')}}</span>
                                            <span class="list-value"> 
                                                <div class="d-flex align-items-center ps-2">                               
                                                    <rating @add-service-rating="addServiceRating" :ratingvalue="rating.rating"  />
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback-data" v-if="errorMessage">{{ errorMessage }}</div>
                                    <div class="comment-form-comment">
                                        <textarea id="comment" name="comment" aria-required="true" placeholder="Your Review (Optional)" v-model=" rating.review"></textarea>
                                    </div>
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-12 text-center" >
                                            <button type="submit" class="btn btn-secondary w-100" data-bs-dismiss='modal'>{{__('messages.cancel')}}</button>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 text-center mobile-space">
                                            <button  @click="saveRatingReview" type="button" class="btn btn-primary w-100" data-bs-dismiss='modal'>{{__('messages.submit')}}</button>
                                        </div>
                                    </div>
                            </form>  
                        </b-modal>
                    </div>
                    <infinite-loading @distance="1" @infinite="infiniteHandler"></infinite-loading>
                </div>
                <div v-else class="text-center">
                    <p>{{__('messages.data_not_found')}}</p>
                </div> 
            </div>
            <!-- <div v-else class="col-lg-9 mt-lg-0 mt-5">
                <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
            </div> -->
        </div>
    </div>
</div>
</template>
<script>
import { required } from "vuelidate/lib/validators";
import {get,post} from '../../request'
import { displayMessage, displayError } from "../../messages";
import { mapGetters } from "vuex";
export default {
    name:'Booking',
    data(){
        return{
            loading: true,
            bookingList: [],
            bookingStatus: [],
            filterData:{},
            rating:{},
            baseUrl:window.baseUrl,
            service_id:'',
            booking_id:'',
            per_page: 10,
            page:1,
            errorMessage: false,
        }
    },
    computed: {
        ...mapGetters(["currencyCode"]),
    },
    created() {
        this.getBookingList();
    },
    mounted() {
        this.filterData = this.defaultFilterData();
        this.rating = this.defaultRatingRequest();
        this.getBookingStatus();
    },
    methods:{
        defaultFilterData: function () {
            return {
                status: {},
                keyword: ""
            }
        },
        defaultRatingRequest: function () {
            return {
                id: '',
                booking_id: '',
                service_id:'',
                customer_id: '',
                rating: '',
                review:''
            }
        },
        addServiceRating(rating) {
           this.rating.rating = rating
        },
        getBookingList() {
            this.loading = true;
            get("booking-list", {
                params: {
                    per_page: this.per_page,
                    page:this.page
                },
            }).then((response) => {
                if(this.filterData.keyword){
                    this.bookingList = response.data.data.filter(people =>
                        people.service_name.toLowerCase().includes(this.filterData.keyword.toLowerCase()) 
                    );
                }else{
                    this.bookingList = response.data.data;
                } 
                this.loading = false;  
            });
             this.page = this.page + 1
        },
        infiniteHandler($state) {
                let vm = this;
                    get("booking-list", {
                        params: {
                            per_page: this.per_page,
                            page:this.page,
                        },
                    })
                    .then(response => {
                        const newDataLength = response.data.data.length;
                        if (newDataLength < this.per_page) {
                            $state.complete();  
                        }

                        $.each(response.data.data, function(key, value) {
                                vm.bookingList.push(value);
                        });
                        $state.loaded();
                    });
                this.page = this.page + 1
            },
        getBookingStatus(){
            get("booking-status", {}).then((response) => {
                this.bookingStatus = response.data;
            });
        },
        handleFilterChange() {
            this.loading = true;
            let filterData = Object.assign({}, this.filterData);
            filterData.status = this.filterData.status.value;

            get("booking-list", {
                params: {
                    status: filterData.status, 
                },
            })
            .then((response) => {
                if (filterData.status !== '') {
                    this.bookingList = response.data.data.filter((people) =>
                    people.status.toLowerCase().includes(filterData.status.toLowerCase())
                    );
                } else {
                    this.bookingList = response.data.data;
                }
                this.loading = false;
            });
        },
       
        giveRate(data){
            this.$root.$emit("bv::show::modal", "addreview"+data.id);
            this.service_id = data.service_id
            this.booking_id = data.id
            post("customer-booking-rating", {
                service_id:data.service_id,
                booking_id:data.id,
                customer_id:this.$store.state.user ? this.$store.state.user.id : ''
            }).then((response) => {
               this.editRating(response)
            }); 

        },
        saveRatingReview(){
            if (!this.rating.rating) {
                this.errorMessage = 'Please select a rating';
                return;
            }
            this.errorMessage = false;

            this.rating.customer_id = this.$store.state.user != null ? this.$store.state.user.id : 0
            this.rating.service_id = this.service_id
            this.rating.booking_id = this.booking_id
            post("save-booking-rating",this.rating).then(response => {
               displayMessage(response.data.message)
               this.showTextbox = false
               this.rating = this.defaultRatingRequest()
               this.$root.$emit("bv::hide::modal", "addreview"+response.data.id);
               this.getBookingList();
            });
        },
        editRating(ratingdata){
            this.rating.id=  ratingdata.data.id
            this.rating.booking_id= ratingdata.data.booking_id
            this.rating.service_id= ratingdata.data.service_id
            this.rating.customer_id= ratingdata.data.customer_id
            this.rating.rating= ratingdata.data.rating
            this.rating.review= ratingdata.data.review
        }
    }
}
</script>

