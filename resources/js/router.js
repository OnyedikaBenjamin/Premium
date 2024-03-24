import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

import LandingPage from "./views/LandingPage/LandingPage";
import Category from "./views/Category/Category";
import Service from "./views/Service/Service";
import Booking from "./views/Booking/Booking";
import ServiceDetail from "./views/Service/ServiceDetail";
import BookingDetail from "./views/Booking/BookingDetail";
import CategoryDetail from "./views/Category/CategoryDetail";
import Gallery from './views/Service/Gallery'
import BookingWizard from './views/Booking/BookingWizard'
import About from './views/About/About'
import ContactUs from './views/ContactUs/ContactUs'
import PrivacyPolicy from './views/PrivacyPolicy/PrivacyPolicy'
import UserFavourite from './views/Service/Userfavourite'
import TermsConditions from './views/TermsConditions/TermsConditions'
import Provider from './views/provider/Provider.vue'
import ProviderService from './views/provider/ProviderService.vue'
import HelpSupport from './views/HelpSupport/HelpSupport'
import RefundPolicy from './views/RefundPolicy/RefundPolicy'
import Blog from "./views/Blog/Blog";
import BlogDetail from "./views/Blog/BlogDetail";

const routes = [
    { path: '/', name: 'frontend-home', component: LandingPage, meta: { label: 'home' } },
    { path: '/category', name: 'category', component: Category, meta: { label: 'Category List', homeName:'Category' } },
    { path: '/service', name: 'service', component: Service, meta: { label: 'Service List',homeName:'Service' } },
    { path: '/booking', name: 'booking', component: Booking, meta: { label: 'Booking List',homeName:'Booking' } },
    { path: '/userfavourite', name: 'user-favourite', component: UserFavourite, meta: { label: 'User Favourite',homeName:'User Favourite' } },
    { path: '/service-detail/:service_id', name: 'service-detail', component: ServiceDetail, meta: { label: 'Service Detail',homeName:'Service' } },
    { path: '/booking-detail/:booking_id', name: 'booking-detail', component: BookingDetail, meta: { label: 'Booking Detail',homeName:'Booking' } },
    { path: '/category-detail/:category_id', name: 'category-detail', component: CategoryDetail, meta: { label: 'Category Detail',homeName:'Category' } },
    { path: '/gallery', name: 'gallery', component: Gallery, meta: { label: 'Gallery',homeName:'Gallery' } },
    { path: '/bookingwizard/:service_id', name: 'book-service', component: BookingWizard, meta: { label: 'Book Service',homeName:'Service' } },
    { path: '/about-us', name: 'about-us', component: About, meta: { label: 'About Us',homeName:'About us' } },
    { path: '/contact-us', name: 'contact-us', component: ContactUs, meta: { label: 'Contact Us',homeName:'Contact us' } },
    { path: '/privacy-policy', name: 'privacy-policy', component: PrivacyPolicy, meta: { label: 'Privacy Policy',homeName:'Privacy Policy'} },
    { path: '/term-conditions', name: 'term-conditions', component: TermsConditions, meta: { label: 'Term Conditions',homeName:'Term Conditions' } },
    { path: '/provider', name: 'provider', component: Provider, meta: { label: 'Provider List' } },
    { path: '/provider-service/:provider_id', name: 'provider-service', component: ProviderService, meta: { label: 'Provider Service' } },
    { path: '/help-support', name: 'help-support', component: HelpSupport, meta: { label: 'Help & Support',homeName:'Help & Support' } },
    { path: '/refund-cancellation-policy', name: 'refund-cancellation-policy', component: RefundPolicy, meta: { label: 'Refund Policy',homeName:'Refund Policy' } },
    { path: '/blog', name: 'blog', component: Blog, meta: { label: 'Blog List', homeName:'Blog' } },
    { path: '/blog-detail/:blog_id', name: 'blog-detail', component: BlogDetail, meta: { label: 'Blog Detail',homeName:'Blog' } },

];
const router = new VueRouter({
    base: process.env.BASE_URL,
    routes: routes
})
router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem('user')
    window.scrollTo(0, 0);
    if (to.matched.some(record => record.meta.auth) && !loggedIn) {
        next('/login')
        return
    }
    next()
})
export default router
