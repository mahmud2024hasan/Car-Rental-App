<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

// Default avatars
const defaultAvatars = {
    male: '/male-profile-avatar.png',
    female: '/female-profile-avatar.png',
};

const testimonials = [
    {
        name: 'Rahima Akter',
        role: 'Freelance Photographer',
        gender: 'female',
        image: '',
        review: 'I booked a car for a weekend trip and the experience was fantastic. Smooth booking, clean car, and professional service.',
    },
    {
        name: 'Tanvir Hasan',
        role: 'Marketing Executive',
        gender: 'male',
        image: '',
        review: 'RentyRide made my business travel easy. The car was delivered on time and in excellent condition. Will rent again!',
    },
    {
        name: 'Shaila Noor',
        role: 'University Lecturer',
        gender: 'female',
        image: '',
        review: 'The entire rental process was seamless. Their support team answered all my questions. Highly recommended!',
    },
    {
        name: 'Rezaul Karim',
        role: 'Software Engineer',
        gender: 'male',
        image: '',
        review: 'The prices are fair, the cars are well-maintained, and the system is easy to use. Five stars from me!',
    },
    {
        name: 'Farhan Khan',
        role: 'Travel Blogger',
        gender: 'male',
        image: '',
        review: 'I’ve used many rental services before, but RentyRide stands out with its quality service and smooth user experience.',
    },
    {
        name: 'Neha Chowdhury',
        role: 'Bank Officer',
        gender: 'female',
        image: '',
        review: 'Booked a car for an official trip and was impressed with the punctuality and cleanliness. Great value for money!',
    },
    {
        name: 'Nusrat Jahan',
        role: 'Fashion Designer',
        gender: 'female',
        image: '',
        review: 'Loved the easy-to-use website and fast service. Found exactly the car I needed. Definitely using RentyRide again.',
    },
    {
        name: 'Mehedi Hasan',
        role: 'Entrepreneur',
        gender: 'male',
        image: '',
        review: 'Reliable and professional! The entire booking process took less than five minutes. Truly satisfied with the service.',
    },
    {
        name: 'Tania Rahman',
        role: 'School Teacher',
        gender: 'female',
        image: '',
        review: 'It was my first time renting a car online and I was nervous, but RentyRide made everything smooth and stress-free.',
    },
    {
        name: 'Asif Mahmud',
        role: 'Content Creator',
        gender: 'male',
        image: '',
        review: 'Needed a car for a last-minute shoot and RentyRide came through. Friendly support and top-notch service!',
    },
];

const currentIndex = ref(0);
let interval = null;

function next() {
    currentIndex.value = (currentIndex.value + 1) % (testimonials.length - 2);
}

function prev() {
    currentIndex.value = currentIndex.value === 0 ? testimonials.length - 3 : currentIndex.value - 1;
}

onMounted(() => {
    interval = setInterval(() => {
        next();
    }, 5000);
});

onBeforeUnmount(() => {
    clearInterval(interval);
});

// Helper to get the appropriate avatar
function getAvatar(t) {
    return t.image || defaultAvatars[t.gender] || defaultAvatars.male;
}
</script>

<template>
    <section class="overflow-hidden bg-white py-16">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">What Our Customers Say</h2>

            <div class="relative">
                <!-- Slider -->
                <div class="overflow-hidden">
                    <div
                        class="mb-6 flex transition-transform duration-700 ease-in-out"
                        :style="{ transform: `translateX(-${currentIndex * (100 / 3)}%)` }"
                        style="width: calc(100% * 5 / 3)"
                    >
                        <div v-for="(t, index) in testimonials" :key="index" class="w-full shrink-0 px-2 md:w-1/5">
                            <div class="flex h-full overflow-hidden rounded-xl bg-white shadow-lg transition hover:shadow-xl">
                                <!-- Profile Section -->
                                <div class="flex w-1/3 flex-col items-center justify-center bg-blue-100 p-4">
                                    <img :src="getAvatar(t)" alt="Profile" class="mb-3 h-20 w-20 rounded-full object-cover" />
                                    <h4 class="text-center text-lg font-semibold text-blue-800">{{ t.name }}</h4>
                                    <p class="text-center text-sm text-blue-600">{{ t.role }}</p>
                                </div>
                                <!-- Review Section -->
                                <div class="flex w-2/3 items-center p-6">
                                    <p class="text-gray-700 italic">“{{ t.review }}”</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <button
                    @click="prev"
                    class="absolute top-1/2 left-[-4%] z-10 -translate-y-1/2 rounded-full border border-gray-200 bg-blue-100 p-2 shadow-lg hover:bg-white"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button
                    @click="next"
                    class="absolute top-1/2 right-[-4%] z-10 -translate-y-1/2 rounded-full border border-gray-200 bg-blue-100 p-2 shadow hover:bg-gray-100"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>
</template>
