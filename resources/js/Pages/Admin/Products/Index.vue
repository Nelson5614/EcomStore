<template>
  <AdminLayout>
    <h1 class="ml-20 text-2xl font-semibold">Products</h1>
    <ProductList :products="products" :brands="brands" :categories="categories" />
  </AdminLayout>
</template>

<script setup>
import {defineProps} from "vue";
import AdminLayout from "@/Pages/Components/AdminLAyout.vue";
import ProductList from "@/Pages/Components/ProductList.vue";

defineProps({
    products: Array,
    brands: Array,
    categories: Array,
});
</script>
