<template>
  <span>
    <a
      href="#"
      v-if="favorited == 1"
      @click.prevent="unFavorite(servicedata)"
    >
       <span class="serv-whishlist">
        <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.6631 0.00013446C12.1944 0.00013446 12.725 0.0750812 13.2294 0.244343C16.3376 1.25486 17.4576 4.66536 16.522 7.64639C15.9915 9.16974 15.1241 10.56 13.9881 11.696C12.362 13.2708 10.5776 14.6686 8.65678 15.8728L8.44626 16L8.22731 15.8644C6.29975 14.6686 4.50524 13.2708 2.86399 11.6876C1.73558 10.5516 0.867375 9.16974 0.328432 7.64639C-0.623139 4.66536 0.496852 1.25486 3.63872 0.226659C3.88293 0.142449 4.13472 0.0835022 4.38735 0.0506603H4.4884C4.72503 0.0161343 4.95997 0.00013446 5.19576 0.00013446H5.28839C5.81891 0.0161343 6.33259 0.108765 6.83027 0.278027H6.87996C6.91364 0.294027 6.9389 0.311711 6.95574 0.327711C7.14185 0.3875 7.31785 0.454867 7.48627 0.547498L7.80626 0.690655C7.88359 0.731894 7.97038 0.794906 8.04538 0.849365C8.09291 0.883869 8.1357 0.91494 8.16837 0.934863C8.18211 0.942971 8.19607 0.951123 8.21015 0.959342C8.28236 1.00149 8.35757 1.0454 8.42099 1.09402C9.35657 0.379079 10.4926 -0.00828652 11.6631 0.00013446ZM13.9031 6.06324C14.2483 6.05398 14.543 5.77693 14.5683 5.4224V5.3222C14.5936 4.14242 13.8786 3.07379 12.7915 2.66116C12.4462 2.54243 12.0673 2.72853 11.941 3.08221C11.8231 3.43589 12.0083 3.82326 12.362 3.94873C12.9018 4.15084 13.2631 4.6822 13.2631 5.27083V5.29693C13.2471 5.48977 13.3052 5.67588 13.4231 5.81903C13.541 5.96219 13.7178 6.04556 13.9031 6.06324Z" fill="#917AEB"/>
        </svg>
      </span>

    </a>
    <a href="#" v-else @click.prevent="favoriteData(servicedata)">
      <span class="serv-whishlist">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none">
          <mask id="mask0_990_812" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="18" height="16">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M0.666626 0H17.5066V16H0.666626V0Z" fill="white"/>
          </mask>
          <g mask="url(#mask0_990_812)">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M2.1668 7.48522C3.32004 11.0641 7.87624 13.9579 9.08705 14.6742C10.302 13.9505 14.8911 11.0247 16.0073 7.4885C16.7402 5.20267 16.0599 2.30723 13.357 1.43835C12.0475 1.01909 10.52 1.27425 9.46543 2.08816C9.24499 2.25718 8.93899 2.26046 8.7169 2.09308C7.59985 1.25538 6.14062 1.01006 4.81053 1.43835C2.11169 2.3064 1.4339 5.20185 2.1668 7.48522M9.08778 16.0001C8.98578 16.0001 8.8846 15.9755 8.79248 15.9254C8.53501 15.7851 2.47023 12.4507 0.992082 7.86103C0.99126 7.86103 0.99126 7.86021 0.99126 7.86021C0.0634052 4.97051 1.09655 1.33911 4.43205 0.26675C5.99822 -0.23866 7.70504 -0.0163126 9.08531 0.852566C10.4228 0.00912204 12.1995 -0.223892 13.7345 0.26675C17.0733 1.34075 18.1097 4.97133 17.1827 7.86021C15.7522 12.3974 9.64301 15.7819 9.3839 15.9238C9.29177 15.9747 9.18978 16.0001 9.08778 16.0001" fill="#917AEB"/>
          </g>
          <path fill-rule="evenodd" clip-rule="evenodd" d="M13.954 6.25614C13.6357 6.25614 13.3659 6.01246 13.3396 5.69084C13.2853 5.01641 12.8329 4.447 12.1888 4.23942C11.8639 4.1344 11.6862 3.78734 11.7907 3.4649C11.8968 3.14163 12.2414 2.96605 12.5672 3.06779C13.6883 3.42962 14.4739 4.41993 14.5701 5.59074C14.5973 5.92959 14.3447 6.2266 14.005 6.25368C13.9877 6.25532 13.9713 6.25614 13.954 6.25614" fill="#917AEB"/>
        </svg>        
      </span> 
    </a>
    <login-component ref="openModal"/>
  </span>
</template>

<script>
import {post} from '../../request'
import { displayMessage } from "../../messages";
export default {
  props: ["servicedata", "favorited"],

  data: function () {
    return {
      favorite: {
        id: "",
        service_id: "",
        user_id: "",
        is_favourite: "",
      },
    };
  },

  mounted() {
    this.isFavorited = this.favorite.is_favourite ? true : false;
  },
  components:{
  },
  computed: {
    isFavorite() {
      return this.favorite.is_favourite;
    },
  },

  methods: {
    favoriteData(servicedata) {
      if (!this.$store.state.auth) {
        this.$refs.openModal.show();
      }else{
        this.favorite.service_id = servicedata;
        this.favorite.user_id = this.$store.state.user.id;
        post("save-favourite", this.favorite)
          .then((response) => {
            this.favorite.is_favourite = true
            this.$emit('emit-list')
            displayMessage(response.data.message)
          })
          .catch((response) => console.log(response.data));
      }
    },
    unFavorite(servicedata) {
      if (!this.$store.state.auth) {
        this.$refs.openModal.show();
      }else{
        this.favorite.service_id = servicedata;
        this.favorite.user_id = this.$store.state.user.id;
          post("delete-favourite", this.favorite)
          .then((response) => {
            this.favorite.is_favourite = false
            this.$emit('emit-list')
            displayMessage(response.data.message)
          })
          .catch((response) => console.log(response.message));
      }
    }
  },
};
</script>