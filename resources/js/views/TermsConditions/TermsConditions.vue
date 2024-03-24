<template>
    <div>
        <breadcrumb :sectionName="this.$route.meta.label" :homeName="this.$route.meta.homeName" />
        <div class="container mar-top mar-bot terms-content">        
            <div v-if="term_conditions && term_conditions.value != null "  v-html="term_conditions.value">
            </div>  
            <div v-else>
                <img :src="baseUrl+'/images/frontend/not_found.gif'"  class="datanotfound" />
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
            baseUrl:window.baseUrl
        }
    },
    computed: {
        ...mapGetters(['term_conditions',]),
    }
}
</script>