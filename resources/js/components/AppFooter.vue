<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">

      <!-- Logo and Description -->
      <div>
        <slot name="brand">
          <h2 class="text-2xl font-bold mb-2">RentyRide</h2>
          <p class="text-sm text-gray-400">
            Your trusted platform to rent cars, bikes, and more—easily and affordably.
          </p>
        </slot>
      </div>

      <!-- Quick Links -->
      <div>
        <h3 class="text-lg font-semibold mb-2">Quick Links</h3>
        <ul class="space-y-1 text-sm text-gray-300">
          <slot name="quick-links">
            <li><Link :href="route('frontend.home')" class="hover:text-white">Home</Link></li>
            <li><Link :href="route('frontend.cars')" class="hover:text-white">Browse Cars</Link></li>
            <li><Link :href="route('frontend.about')" class="hover:text-white">About Us</Link></li>
            <li><Link :href="route('frontend.contact')" class="hover:text-white">Contact</Link></li>
          </slot>
        </ul>
      </div>

      <!-- Support -->
      <div>
        <h3 class="text-lg font-semibold mb-2">Support</h3>
        <ul class="space-y-1 text-sm text-gray-300">
          <slot name="support-links">
            <li><Link href="#" class="hover:text-white">FAQs</Link></li>
            <li><Link href="#" class="hover:text-white">Terms of Service</Link></li>
            <li><Link href="#" class="hover:text-white">Privacy Policy</Link></li>
          </slot>
        </ul>
      </div>

      <!-- Contact and Social -->
      <div>
        <h3 class="text-lg font-semibold mb-2">Contact Us</h3>
        <div class="text-sm text-gray-300">
          <slot name="contact">
            📍 123 Main Street, Dhaka, BD <br />
            📞 +880 123 456 789 <br />
            ✉️ support@rentyride.com
          </slot>
        </div>
        <div class="mt-4 flex space-x-4">
          <slot name="social">
            <a href="#" class="hover:text-white"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="hover:text-white"><i class="fab fa-twitter"></i></a>
            <a href="#" class="hover:text-white"><i class="fab fa-instagram"></i></a>
          </slot>
        </div>
      </div>

    </div>

    <!-- Bottom Footer -->
    <div class="border-t border-gray-700 text-center py-4 text-sm text-gray-400">
      <slot name="copyright">
        © 2025 RentyRide. All rights reserved.
      </slot>
    </div>
  </footer>
</template>

