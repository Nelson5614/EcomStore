<template>
    <!--header-->
    <Header />
    <!--main content-->
    <div class="bg-white">
        <div
            class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8"
        >
            <slot></slot>
        </div>
    </div>

    <!-- footer -->
    <Footer />
</template>

<script setup>
import Header from './Header.vue';
import Footer from './Footer.vue';
import { onMounted } from "vue";
import { Modal } from "flowbite";

onMounted(() => {
    initFlowbite();
});



</script>
