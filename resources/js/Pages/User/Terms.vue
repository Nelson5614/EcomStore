<script setup>
import UserLayout from "./Layouts/UserLayout.vue";
</script>

<template>
    <UserLayout>
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Terms of Service</h1>
                <p class="text-lg text-gray-600">Please read these terms carefully before using our services</p>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 space-y-8">
                <!-- Introduction -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Introduction</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Welcome to TlaliFurn. These Terms of Service govern your use of our website and services. By accessing or using our services, you agree to be bound by these terms.
                    </p>
                </section>

                <!-- Services -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Services</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        TlaliFurn provides high-quality furniture and home decor products through our online platform. Our services include:
                    </p>
                    <ul class="text-gray-600 space-y-2 list-disc list-inside">
                        <li>Online furniture sales and delivery</li>
                        <li>Product customization services</li>
                        <li>Customer support and consultation</li>
                        <li>Assembly and installation services</li>
                        <li>Warranty and after-sales support</li>
                    </ul>
                </section>

                <!-- Orders and Payment -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Orders and Payment</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Order Process</h3>
                            <p class="text-gray-600 leading-relaxed">
                                All orders are subject to product availability. We reserve the right to refuse or cancel any order for any reason, including but not limited to product availability, errors in pricing, or fraud detection.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Payment Methods</h3>
                            <p class="text-gray-600 leading-relaxed">
                                We accept various payment methods including credit/debit cards, mobile money, and bank transfers. All payments are processed securely through our payment partners.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Pricing</h3>
                            <p class="text-gray-600 leading-relaxed">
                                All prices are listed in Lesotho Loti (LSL) and are inclusive of applicable taxes. We reserve the right to modify prices at any time without prior notice.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Shipping and Delivery -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Shipping and Delivery</h2>
                    <div class="bg-blue-50 rounded-lg p-4 mb-4">
                        <h3 class="font-medium text-blue-900 mb-2">Delivery Areas</h3>
                        <p class="text-blue-800 text-sm">
                            We currently deliver to all areas within Lesotho. Delivery times and fees vary based on location and order size.
                        </p>
                    </div>
                    <ul class="text-gray-600 space-y-2 list-disc list-inside">
                        <li>Standard delivery: 3-7 business days</li>
                        <li>Express delivery: 1-2 business days (available in select areas)</li>
                        <li>Free delivery for orders over LSL 5,000</li>
                        <li>Delivery fees will be calculated at checkout</li>
                    </ul>
                </section>

                <!-- Returns and Refunds -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Returns and Refunds</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Return Policy</h3>
                            <p class="text-gray-600 leading-relaxed">
                                You may return unused items in their original condition within 14 days of delivery. Customized items and final sale items cannot be returned.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Refund Process</h3>
                            <p class="text-gray-600 leading-relaxed">
                                Refunds will be processed within 5-7 business days after we receive and inspect the returned item. Refunds will be issued to the original payment method.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Warranty -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Warranty</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        All our products come with a manufacturer's warranty. Warranty terms vary by product and manufacturer:
                    </p>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">Structural Warranty</h3>
                            <p class="text-gray-600 text-sm">1-5 years on frames and structural components</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">Upholstery Warranty</h3>
                            <p class="text-gray-600 text-sm">1-2 years on fabrics and cushioning</p>
                        </div>
                    </div>
                </section>

                <!-- Limitation of Liability -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Limitation of Liability</h2>
                    <p class="text-gray-600 leading-relaxed">
                        TlaliFurn shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of our products or services, including but not limited to loss of profits, data, or other intangible losses.
                    </p>
                </section>

                <!-- Changes to Terms -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Changes to Terms</h2>
                    <p class="text-gray-600 leading-relaxed">
                        We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on our website. Your continued use of our services constitutes acceptance of the modified terms.
                    </p>
                </section>

                <!-- Contact Information -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Us</h2>
                    <p class="text-gray-600 leading-relaxed">
                        If you have any questions about these Terms of Service, please contact us:
                    </p>
                    <div class="mt-4 bg-amber-50 rounded-lg p-4">
                        <p class="text-amber-900">
                            <strong>Email:</strong> terms@tlalifurn.com<br>
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
