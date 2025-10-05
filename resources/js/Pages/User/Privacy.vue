<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
</script>

<template>
    <UserLayout>
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
                <p class="text-lg text-gray-600">Your privacy is important to us</p>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 space-y-8">
                <!-- Introduction -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Introduction</h2>
                    <p class="text-gray-600 leading-relaxed">
                        At TlaliFurn, we are committed to protecting your privacy. This Privacy Policy outlines how we collect, use, disclose, and safeguard your personal information when you visit our website or make a purchase from us.
                    </p>
                </section>

                <!-- Information We Collect -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Information We Collect</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Personal Information</h3>
                            <ul class="text-gray-600 space-y-1 list-disc list-inside">
                                <li>Name and contact information</li>
                                <li>Shipping and billing addresses</li>
                                <li>Payment information</li>
                                <li>Email address and phone number</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Technical Information</h3>
                            <ul class="text-gray-600 space-y-1 list-disc list-inside">
                                <li>IP address and browser information</li>
                                <li>Device information</li>
                                <li>Browsing behavior on our website</li>
                                <li>Cookies and similar technologies</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- How We Use Your Information -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">How We Use Your Information</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">Order Processing</h3>
                            <p class="text-gray-600 text-sm">To process and fulfill your orders, including shipping and delivery.</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">Customer Service</h3>
                            <p class="text-gray-600 text-sm">To provide customer support and respond to your inquiries.</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">Marketing</h3>
                            <p class="text-gray-600 text-sm">To send you promotional offers and updates (with your consent).</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">Website Improvement</h3>
                            <p class="text-gray-600 text-sm">To analyze and improve our website and services.</p>
                        </div>
                    </div>
                </section>

                <!-- Data Protection -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Data Protection</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These include:
                    </p>
                    <ul class="text-gray-600 space-y-2 list-disc list-inside">
                        <li>SSL encryption for data transmission</li>
                        <li>Secure payment processing</li>
                        <li>Regular security audits</li>
                        <li>Employee training on data protection</li>
                    </ul>
                </section>

                <!-- Your Rights -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Your Rights</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        You have the following rights regarding your personal information:
                    </p>
                    <ul class="text-gray-600 space-y-2 list-disc list-inside">
                        <li>Access to your personal information</li>
                        <li>Correction of inaccurate information</li>
                        <li>Deletion of your personal information</li>
                        <li>Opt-out of marketing communications</li>
                        <li>Data portability</li>
                    </ul>
                </section>

                <!-- Contact Information -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Us</h2>
                    <p class="text-gray-600 leading-relaxed">
                        If you have any questions about this Privacy Policy or how we handle your personal information, please contact us at:
                    </p>
                    <div class="mt-4 bg-amber-50 rounded-lg p-4">
                        <p class="text-amber-900">
                            <strong>Email:</strong> privacy@tlalifurn.com<br>
                            <strong>Phone:</strong> +266 5888 5888<br>
                            <strong>Address:</strong> Maseru, Lesotho
                        </p>
                    </div>
                </section>

                <!-- Last Updated -->
                <div class="border-t pt-6">
                    <p class="text-sm text-gray-500 text-center">
                        Last updated: September 2024
                    </p>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
