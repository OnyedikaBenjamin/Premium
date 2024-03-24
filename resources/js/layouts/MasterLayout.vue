<template>
    <div>
        <div class="loading">
            <loader/>
        </div>
        <main class="main-content">
            <div class="position-relative">
                <header>
                   <Header/>
                </header>
            </div>
            <div class="conatiner-fluid content-inner p-0">
                <router-view></router-view>
            </div>
            <Footer/>
        </main>
    </div>
</template>
<script>
import Loader from "../components/Partials/Loader";
import Header from "../components/Partials/Header";
import Footer from '../components/Partials/Footer';
export  default {
    name:'MasterLayout',
    components: {Header, Loader,Footer}
}
</script>

