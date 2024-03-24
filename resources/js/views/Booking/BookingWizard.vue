<template>
<div>
   <breadcrumb :sectionName="this.$route.meta.label" :homeName="this.$route.meta.homeName" />
   <div class="container mar-top mar-bot">
   <div class="row serivce-sidebar">
         <div class="col-lg-4 col-md-4 col-xs-12">
            <div class="card  ">
                <div class="card-body">
                <ul class="p-0 row list-inline">
                    <li :class="checkActive(0, 1)" class="col-lg-12 col-md-12 mb-2  active" @click="changeTab(1)" id="account">
                    <a href="javascript:void(0);">
                        <div class="d-flex justify-content-between ">
                            <div class="rounded-circle p-2"> 
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div :class="current == 1? 'p-2 text-dark':'p-2 text-secondary'">
                                <p :class="current == 1? 'h5 text-dark':'h5 text-secondary'">{{__('messages.step_1')}}</p>
                                <p class="mt-2">{{__('messages.step_1_dec')}}</p>
                            </div>
                        </div>
                    </a>
                    </li>
                    <li :class="checkActive(1, 2)" class="col-lg-12 col-md-12 mb-2 " v-if="booking.address" @click="changeTab(2)"  id="personal">
                    <a href="javascript:void(0);">
                       <div class="d-flex justify-content-between">
                        <div class="rounded-circle p-2"> 
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div :class="current == 2? 'p-2 text-dark':'p-2 text-secondary'">
                                <p :class="current == 2? 'h5 text-dark':'h5 text-secondary'">{{__('messages.step_2')}}</p>
                                <p class="mt-2">{{__('messages.step_2_dec')}}</p>

                            </div>
                        </div>
                    </a>
                    </li>
                    <li :class="checkActive(1, 2)" class="col-lg-12 col-md-12 mb-2 " v-else  id="personal">
                    <a href="javascript:void(0);">
                       <div class="d-flex justify-content-between">
                        <div class="rounded-circle p-2"> 
                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div :class="current == 2? 'p-2 text-dark':'p-2 text-secondary'">
                                <p :class="current == 2? 'h5 text-dark':'h5 text-secondary'">{{__('messages.step_2')}}</p>
                                <p class="mt-2">{{__('messages.step_2_dec')}}</p>

                            </div>
                        </div>
                    </a>
                    </li>
                </ul>
                </div>
            </div>
         </div>
        <div class="col-lg-8  col-md-8 col-xs-12">
            <form>
                <fieldset :class="current == 1 ? 'd-block' : 'd-none'">
                    <div class="card ">
                        <div class="card-body m-4">
                            <div class="card-title ">
                                <h4>{{__('messages.schedule_ervice')}}</h4>
                            </div>
                            <div class="row pt-4">
                                <div class="col-md-12">
                                    <div class="form-group has-icon">
                                        <label class="form-label"><b>{{__('messages.date_time')}} : </b></label>
                                        <vc-date-picker   :min-date='new Date()' v-model="booking.date" mode="dateTime" is24hr required="required">
                                            <template v-slot="{ inputValue, inputEvents }">
                                            <input
                                                class="form-control"
                                                :value="inputValue"
                                                v-on="inputEvents"
                                                
                                            />
                                            <i class="fas fa-calendar-alt"></i>
                                            </template>
                                        </vc-date-picker>
                                        
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="form-group has-icon">
                                        <label class="form-label"><b>{{__('messages.your_location')}} : </b></label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"  v-model="booking.address" :class="{ ' is-invalid': $v.address.$error }" :placeholder="__('messages.address')" ></textarea>
                                        <i class="fas fa-map-marker-alt"></i>

                                        <div v-if="!$v.address.required"
                                            class="invalid-feedback">{{__('messages.address_required')}}
                                        </div>
                                    </div>
                                </div>
                                <div v-if="serviceData.type == 'fixed'" class="col-md-12 mt-2">
                                    <div class="form-group quantity">
                                        <label class="form-label"><b>{{__('messages.quantity')}}: </b></label>
                                        <b-input-group>
                                            <b-input-group-prepend>
                                                <b-btn variant="primary" @click="decrement()">-</b-btn>
                                            </b-input-group-prepend>
                                            <b-form-input
                                                type="number"
                                                min="0.00"
                                                :value="booking.quantity"
                                                disabled
                                            ></b-form-input>
                                            <b-input-group-append>
                                                <b-btn variant="primary" @click="increment()">+</b-btn>
                                            </b-input-group-append>
                                        </b-input-group>
                                    </div>
                                </div>
                            </div>
                            <div class=" mt-4 d-flex wizard-form">
                                <router-link :to="{ name:'frontend-home' }"  name="previous" @click="changeTab(1)" class="btn btn-link previous action-button-previous float-end me-1" value="Previous" >{{__('messages.cancel')}}</router-link>
                                <button type="button" name="next" @click="changeTab(2)" class="btn btn-primary next action-button" value="Next"  >{{__('messages.continue')}}</button>
                                
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset :class="current == 2 ? 'd-block' : 'd-none'">
                    <div class="card">
                        <div class="card-body m-4">
                            <div class="card-title ">
                                <h4>{{__('messages.book_service')}}</h4>
                            </div>
                            <div class="payment-info mt-5">
                                <div class="bill-box">
                                    <span class="text-secondary"><b>{{__('messages.price')}}</b></span>
                                    <span class="hightlight-text"> {{ serviceData.price_format }} </span>
                                </div>
                                <div class="bill-box">
                                    <span class="text-secondary"><b>{{__('messages.sub_total')}} </b></span>
                                    <span><span class="hightlight-text">{{ serviceData.price_format }} * {{ booking.quantity }} = <span v-if="currencyPostion === 'left'">{{currencySymobl}}</span>{{ parseFloat(serviceData.price * booking.quantity).toFixed(2) }}</span><span v-if="currencyPostion === 'right'">{{currencySymobl}}</span></span>
                                </div> 
                                <div  class="bill-box">
                                    <span class="text-secondary"><b>{{__('messages.tax')}}</b></span>
                                    <span><span class="text-danger"><span v-if="currencyPostion === 'left'">{{currencySymobl}}</span>{{parseFloat(taxfinal).toFixed(2)}}</span><span v-if="currencyPostion === 'right'">{{currencySymobl}}</span></span>
                                </div>
                                                   
                                <div class="bill-box" >
                                    <span class="text-secondary"><b>{{__('messages.discount')}} ({{serviceData.discount}}{{discountType}} {{__('messages.off')}})</b></span>
                                    <span class="text-success">-<span v-if="currencyPostion === 'left'">{{currencySymobl}}</span>{{parseFloat(discountprice).toFixed(2)}}<span v-if="currencyPostion === 'right'">{{currencySymobl}}</span> </span>
                                </div>    
                                <div class="bill-box" v-if="this.couponData.length > 0" >
                                    <span class="text-secondary"><b>{{__('messages.coupon')}}</b></span>
                                    <b-link v-if="showcoupon" variant="primary">
                                        <span class="text-success">
                                            <span v-if="coupons.type === 'fixed'">
                                                -<span v-if="currencyPostion === 'left'">{{currencySymobl}}</span>{{ coupons.value }}
                                                
                                                <span v-if="currencyPostion === 'right'">{{currencySymobl}}</span>
                                            </span>
                                            <span  v-else>-{{ coupons.value }}</span>
                                        </span>
                                    </b-link>
                                    <span v-else class="text-primary" v-b-modal.coupons> <b>{{__('messages.apply_coupon')}} </b></span>
                                </div> 
                                <div class="separator"></div>
                                <div class="bill-box">
                                    <h5>{{__('messages.total_amount')}}</h5>
                                    <h3 class="primary-color "><span v-if="currencyPostion === 'left'">{{currencySymobl}}</span>{{parseFloat(booking.total_amount).toFixed(2)}}<span v-if="currencyPostion === 'right'">{{currencySymobl}}</span></h3>
                                </div>
                                <div class="mt-5 d-flex wizard-form">
                                    <button type="button" name="next" @click="saveBooking" class="btn btn-primary next action-button float-end" value="Next" >{{__('messages.book_now')}}</button>
                                    <a  name="previous" @click="changeTab(1)" class="btn btn-link previous action-button-previous float-end me-1" value="Previous" >{{__('messages.cancel')}}</a>
                                </div>                               
                            </div>                            
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <b-modal  ref="modal" id="coupons" header-bg-variant="primary" body-bg-variant="#f6f7f9" centered title="coupons" header-text-variant="white" title-class="text-white">
            <div class="row">
                <div class="col-12">
                    <input type="text" class="form-control rounded-1" placeholder="Enter Coupon Code" v-model="coupons.code">
                </div>
                <div class="col-12 mt-3">
                    <div class="card rounded-1">
                        <div class="card-body">
                                <div class="card-title">
                                    <p>{{__('messages.coupon_available')}}</p>
                                </div>
                            <div class="row">
                                <div class="col-12 d-flex justify-content-between overflow-scroll text-primary">
                                    <button v-for="(data, index) in couponData"  :key="index"  @click="getCoupon(data)" class="btn btn-soft-primary border-dotted m-2">{{ data.code }}</button>
                                </div>
                                <div class="col-12 pt-2" v-if="couponDec">
                                    <p>{{coupons.value}} {{coupons.type}} off on minimum purchase of {{serviceData.value}}</p>
                                </div>
                                <div class="col-12"  v-if="couponDec">
                                    <b>Expire Date</b>: {{coupons.date}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 mt-2  text center ">
                    <button :disabled="submit == false" type="button" @click="addCoupon" name="next" class="btn btn-primary w-100" value="Next" >{{__('messages.submit')}}</button>
                </div>
                <div class="col-6 mt-2  text center ">
                    <a  name="previous" @click="$bvModal.hide('coupons')" class="btn btn-link w-100" value="Previous" >{{__('messages.cancel')}}</a>
                </div>
            </div>
        </b-modal>
       <b-modal ref="confirmModal" id="confirm" hide-header >
            <div class="row">
                <div class="col-12 text-center">
                    <svg width="125" height="125" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.9225 21.6744L21.6766 29.4263L41.2115 9.89136" stroke="#625FC6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M29.8106 4.85714C27.9717 4.29972 26.0209 4 24 4C12.9543 4 4 12.9543 4 24C4 35.0457 12.9543 44 24 44C35.0457 44 44 35.0457 44 24C44 21.9621 43.6952 19.9953 43.1287 18.1429" stroke="#625FC6" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="col-12 mt-3 text-center">
                    <h5>{{__('messages.booking_successful')}}</h5>
                </div>
                <div class="col-12 mt-2 text-center ">
                    <button type="button" @click="redirectBooking" name="next" class="btn btn-primary" value="Next" >{{__('messages.done')}}</button>
                </div>
            </div>
      </b-modal>
   </div>
    <login-component ref="openModal" />
   </div>
</div>
</template>
<script>
import { mapGetters } from "vuex";
import {post} from '../../request'
import { displayMessage } from "../../messages";
import { required } from 'vuelidate/lib/validators'
export default {
  name: 'Wizard',
    validations: {
        address: {required},
       
    }, 
  data () {
    return {
      current: 1,
      serviceData:{},
      couponData:{},
      ratingData:{},
      booking:{},
      payment:{},
      taxes:{},
      taxfinal:0,
      couponDec:false,
      showcoupon:false,
      coupons: {
        id: "",
        value: "",
        type: "",
        code: "",
        date:"",
      },
      discountprice:0.0,
      submit:false
    }
  },
  mounted(){
    this.booking = this.defaultBookingRequest();
    this.payment = this.defaultPaymentRequest()
    this.serviceDetail()
  },
  methods: {
    defaultBookingRequest: function () {
        return {
          id: "",
          customer_id: "",
          service_id: "",
          provider_id: "",
          date: new Date(),
          quantity: 1,
          amount: "",
          discount: "",
          coupon_id: "",
          status: "pending",
          booking_address_id: "",
          address: "",
          description: "",
          total_amount: this.serviceData.price,
          tax:''
        };
    },
    defaultPaymentRequest: function () {
        return {
            id: "",
            customer_id: "",
            booking_id: "",
            provider_id: "",
            datetime: new Date(),
            discount: 1,
            total_amount: "",
            payment_type: "cash",
            payment_status: "pending",
            txt_id:""
        };    
    },
    changeTab (tab) {
        this.$v.$touch();
        if(this.booking.address == ''){
            if (this.$v.$invalid) {
                return;
            }
        }
      this.current = tab
      window.scrollTo({ top: 0, behavior: 'smooth' })
      this.dataCalculation()
    },
    checkActive (item, active) {
      let className = ''
      if (this.current > item) {
        className = 'active'
      }
      if (this.current > active) {
        className = className + ' done'
      }
      return className
    },
    increment() {
      this.booking.quantity++;
    },
    decrement() {
      if (this.booking.quantity != 1) {
        this.booking.quantity--;
      }
    },
    addCoupon() {
        this.showcoupon = true;
        this.dataCalculation();
        this.$refs["modal"].hide();
    },
    getCoupon(code) {  
        if(this.coupons.code == ''){
            this.submit = false
        }
        this.submit = true
        this.coupons.id = code.id;
        this.coupons.value = code.discount;
        this.coupons.type = code.discount_type;
        this.coupons.code = code.code;
        this.coupons.date = code.expire_date
        this.couponDec = true
    },
    saveBooking() {
      if (!this.$store.state.auth) {
        this.$refs.openModal.show();
      } else {
        this.dataCalculation();
        this.booking.service_id = this.serviceData.id;
        this.booking.provider_id = this.serviceData.provider_id;
        this.booking.customer_id = this.$store.state.user != null ? this.$store.state.user.id : 0;
        this.booking.amount = this.serviceData.price;
        if(this.taxes != undefined && this.taxes.length > 0){
            this.booking.tax = this.taxes
        }
        this.current = 3;
        this.booking.discount = this.serviceData.discount
        this.booking.coupon_id = this.coupons.code
        post("booking-save", this.booking).then((response) => {
            this.booking.id = response.data.booking_id
            this.$refs.confirmModal.show();
        });
      }
    },
    dataCalculation(){
        var totalAmount = 0.0;
        var discountPrice = 0.0;
        var taxAmount = 0.0;
        var couponDiscountAmount = 0.0;
        var servicePrice = this.serviceData.price
        var qty = this.booking.quantity
        var serviceDiscountPercent = this.serviceData.discount

        if(this.taxes != undefined){
            this.taxes.forEach(element => {
                if (element.type == 'percent') {
                   var tamount = ((servicePrice * qty) * element.value) / 100;
                } else {
                    var tamount = element.value
                }
                taxAmount += tamount
            });
        }
        this.taxfinal = taxAmount
        if (this.couponData != null && this.couponData.length != 0  && this.couponData != undefined) {
            if(this.coupons.type == "fixed"){
                totalAmount = (servicePrice * qty) - this.coupons.value;
                couponDiscountAmount = this.coupons.value;
            }
            else {
                totalAmount = (servicePrice * qty) - (((servicePrice * qty) * this.coupons.value) / 100);
                var calValue = ((servicePrice * qty) * this.coupons.value);

                couponDiscountAmount = calValue / 100;
            }       
        }
        if(this.serviceData.discount != 0){
            totalAmount = (servicePrice * qty) - (((servicePrice * qty) * (serviceDiscountPercent)) / 100);
            discountPrice = servicePrice * qty - totalAmount;
            this.discountprice = discountPrice;
            totalAmount = (servicePrice * qty) - discountPrice - couponDiscountAmount + taxAmount;
        }else {
            totalAmount = (servicePrice * qty) - couponDiscountAmount + taxAmount;
        }
        this.booking.total_amount = totalAmount
      
    },
    serviceDetail(){
        post("service-detail", {
            service_id: this.$route.params.service_id,
        })
        .then((response) => {
            this.serviceData = response.data.service_detail;
            this.couponData = response.data.coupon_data;
            this.ratingData = response.data.rating_data
            this.relatedService = response.data.related_service
            this.taxes = response.data.taxes
            this.dataCalculation()

        });
    },
    redirectBooking(){
        this.$router.push("/booking");

    }
  },
  computed: {
    ...mapGetters(['currencySymobl','discountType','currencyPostion']),
  },
}
</script>