<template>
    <div>
        <breadcrumb :sectionName="this.$route.meta.label" :homeName="this.$route.meta.homeName" />
        <div class="container mar-top mar-bot terms-content"> 
            <div v-if="loading" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-3 list-inline service-card-box">
                <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
            </div>        
            <div v-else-if="refund_policy && refund_policy.value != null "  v-html="refund_policy.value">
            </div>  
            <div v-else class="text-center">
                <p>{{__('messages.data_not_found')}}</p>
            </div>               
        </div>
    </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
    name:'TermsConditions',
    data() {
        return {
            loading: true,
            baseUrl:window.baseUrl
        }
    },
    computed: {
        ...mapGetters(['refund_policy',]),
    },
    created() {
        setTimeout(() => {
            this.loading = false;
        }, 2000);
    }
}
</script>