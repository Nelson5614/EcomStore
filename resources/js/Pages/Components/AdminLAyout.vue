<template>
  <!-- Navbar -->
  <Navbar />
  <!-- EndNavbar -->

  <!-- Sidebar -->
  <Sidebar />
  <!-- EndSidebar -->

  <!-- MainContent -->
  <main class="p-4 md:ml-64 h-auto pt-20">
    <slot />
  </main>
  <!-- EndMainContent -->
</template>

<script setup>
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";

// initialize components based on data attribute selectors
onMounted(() => {
  initFlowbite();
});
</script>
