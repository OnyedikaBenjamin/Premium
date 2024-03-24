<template>
    <div>
        <breadcrumb :sectionName="this.$route.meta.label" :homeName="this.$route.meta.homeName" />
        <section-one :data="jsonData.sectionOne"/>
        <section-two  :data="jsonData.sectionTwo"/>
        <section-four :data="jsonData.sectionFour" />
        <section-five :data="jsonData.sectionFive"/>
        <section-six :data="jsonData.sectionSix"/>
    </div>
</template>
<script>
import SectionFive from '../../components/Aboutus/SectionFive.vue'
import SectionFour from '../../components/Aboutus/SectionFour.vue'
import SectionOne from '../../components/Aboutus/SectionOne.vue'
import SectionSix from '../../components/Aboutus/SectionSix.vue'
import SectionTwo from '../../components/Aboutus/SectionTwo.vue'
export default {
    name:'About',
    data(){
        return{
            jsonData:{},
            baseUrl: window.baseUrl
        }
    },
    components: { SectionOne, SectionTwo, SectionFour, SectionFive, SectionSix },
    mounted() {
      axios.get(this.baseUrl + "/about-us.json")
      .then((response) => {
        this.jsonData = response.data;
      });
    },
}
</script>