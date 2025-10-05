<template>
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-sm text-gray-400">
                    © {{ new Date().getFullYear() }} TlaliFurn. All rights reserved.
                </p>
                <div class="mt-4 flex justify-center space-x-6">
                    <Link href="/privacy" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">
                        Privacy
                    </Link>
                    <Link href="/terms" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">
                        Terms
                    </Link>
                    <Link href="/contact" class="text-gray-400 hover:text-white transition-colors duration-200 text-sm">
                        Contact
                    </Link>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
