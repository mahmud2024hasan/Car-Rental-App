<script setup>
import FlashMessage from '@/components/custom/FlashMessage.vue';
import DeleteUser from '@/components/DeleteUser.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Access page props
const page = usePage();
const user = computed(() => page.props.auth.user);
const profile = computed(() => page.props.profile);

// Refs for image preview
const imagePreview = ref(profile.value?.image ? `/storage/${profile.value.image}` : '/male-profile-avatar.png');
const fileInput = ref(null);

// Handle file input click
const triggerFileInput = () => {
    fileInput.value?.click();
};

// Handle file preview
function handleFileChange(event) {
  const file = event.target.files[0];
  if (!file) return;

  imageForm.image = file;

  // Image preview (for immediate UI feedback)
  const reader = new FileReader();
  reader.onload = e => {
    imagePreview.value = e.target.result;
  };
  reader.readAsDataURL(file);

  // Auto submit after selecting file
  submitImage();
}

// Profile Info Form
const profileForm = useForm({
    name: user.value.name || '',
    mobile: profile.value?.mobile || '',
    address: profile.value?.address || '',
    district: profile.value?.district || '',
    division: profile.value?.division || '',
    postal_code: profile.value?.postal_code || '',
});

// Image Upload Form
const imageForm = useForm({
    image: null,
});

// Password Change Form
const passwordForm = useForm({
    current_password: '',
    new_password: '',
    confirm_password: '',
});

// Submit profile info
const submitProfile = () => {
    profileForm.post(route('customer.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ['auth', 'profile'], preserveScroll: true });
        },
    });
};

// Submit image upload
function submitImage() {
  imageForm.post(route('customer.profile.image.update'), {
    preserveScroll: true,
    onSuccess: () => {
      fileInput.value.value = null;
    },
  });
}

// Submit password update
const submitPassword = () => {
    passwordForm.post(route('customer.profile.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
        },
    });
};

// Breadcrumbs
const breadcrumbs = [{ title: 'My Profile', href: '/customer/profile' }];
</script>

<template>
    <Head title="Customer Profile" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex w-full flex-col bg-white px-3 text-[#161931]">
            <!-- Content Header -->
            <div class="mt-8 ml-10 border-b border-gray-300">
                <h1 class="text-3xl font-semibold">Profile Settings</h1>
                <p class="text-md mt-1 text-gray-500">Manage your profile and account settings</p>
            </div>

            <!-- Main Content -->
            <main class="mb-10 w-full py-1">
                <div class="">
                    <div class="w-full rounded-lg px-6">
                        <div class="mx-auto grid w-full">
                            <div class="mt-8 grid w-full grid-cols-2 gap-6">
                                <!-- Profile Picture -->
                                <div>
                                    <div class="mr-10 flex items-center">
                                        <div class="m-5">
                                            <img
                                                class="h-40 w-40 rounded-full object-cover p-1 ring-2 ring-gray-300 dark:ring-indigo-500"
                                                :src="imagePreview"
                                                alt="Profile Picture"
                                            />
                                        </div>
                                        <div class="flex-1">
                                            <h1 class="text-2xl font-semibold">{{ user.name }}</h1>
                                            <p class="text-md my-1 text-gray-900">{{ user.email }}</p>
                                            <div class="mt-2 flex items-center">
                                                <button
                                                    @click="triggerFileInput"
                                                    class="text-md w-2/5 cursor-pointer rounded border border-gray-300 bg-blue-100 px-2 py-2 font-medium text-gray-900 hover:bg-blue-700 hover:text-white"
                                                >
                                                    Change Profile Picture
                                                </button>

                                                <!-- Hidden File Input -->
                                                <input ref="fileInput" type="file" accept="image/*" hidden @change="handleFileChange" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Show flash message here -->
                                    <div class="mx-10"><FlashMessage /></div>
                                </div>
                                <!-- Delete Account -->
                                <div class="mx-10">
                                    <DeleteUser />
                                </div>
                            </div>

                            <div class="mt-8 grid w-full grid-cols-2 gap-6">
                                <!-- Profile Information -->
                                <div class="mx-10">
                                    <!-- Content Header -->
                                    <div class="border-b border-gray-300">
                                        <h2 class="text-xl font-semibold text-gray-900">Personal Information</h2>
                                        <p class="mt-1 text-sm text-gray-600">Use a permanent address where you can receive mail.</p>
                                    </div>

                                    <!-- Profile Form -->
                                    <form @submit.prevent="submitProfile">
                                        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                            <div class="sm:col-span-3">
                                                <label for="name" class="text-md block font-medium text-gray-900">Name:</label>
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        v-model="profileForm.name"
                                                        class="text-md block w-full rounded-md bg-white px-3 py-2 text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400"
                                                        placeholder="Your Name"
                                                    />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-3">
                                                <label for="mobile" class="text-md block font-medium text-gray-900">Mobile:</label>
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        v-model="profileForm.mobile"
                                                        class="text-md block w-full rounded-md bg-white px-3 py-2 text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400"
                                                        placeholder="Your Mobile Number"
                                                    />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-6">
                                                <label for="address" class="text-md block font-medium text-gray-900">Street Address:</label>
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        v-model="profileForm.address"
                                                        class="text-md block w-full rounded-md bg-white px-3 py-2 text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400"
                                                        placeholder="Your Street Address"
                                                    />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-2">
                                                <label for="district" class="text-md block font-medium text-gray-900">District/City:</label>
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        v-model="profileForm.district"
                                                        class="text-md block w-full rounded-md bg-white px-3 py-2 text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400"
                                                        placeholder="Your District"
                                                    />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-2">
                                                <label for="division" class="text-md block font-medium text-gray-900">Division/State:</label>
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        v-model="profileForm.division"
                                                        class="text-md block w-full rounded-md bg-white px-3 py-2 text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400"
                                                        placeholder="Your Division"
                                                    />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-2">
                                                <label for="postal_code" class="text-md block font-medium text-gray-900">Postal Code:</label>
                                                <div class="mt-2">
                                                    <input
                                                        type="text"
                                                        v-model="profileForm.postal_code"
                                                        class="text-md block w-full rounded-md bg-white px-3 py-2 text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400"
                                                        placeholder="Your Postal Code"
                                                    />
                                                </div>
                                            </div>
                                            <div class="sm:col-span-6">
                                                <button
                                                    type="submit"
                                                    :disabled="profileForm.processing"
                                                    class="text-md w-full cursor-pointer rounded-lg border border-gray-300 bg-blue-100 px-4 py-2 transition-all duration-200 hover:bg-blue-700 hover:text-white"
                                                >
                                                    <span v-if="profileForm.processing">Updating...</span>
                                                    <span v-else>Update Profile</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!--Change Password Section -->
                                <div class="mx-10">
                                    <!-- Content Header -->
                                    <div class="border-b border-gray-300">
                                        <h2 class="text-xl font-semibold text-gray-900">Change Password</h2>
                                        <p class="mt-1 text-sm text-gray-600">
                                            Your password must have at least 8 characters, include one uppercase letter, and one number.
                                        </p>
                                    </div>

                                    <!-- Change Password Form -->
                                    <form @submit.prevent="submitPassword">
                                        <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                            <div class="sm:col-span-6">
                                                <label for="current_password" class="text-md block font-medium text-gray-900"
                                                    >Current Password:</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="password"
                                                        v-model="passwordForm.current_password"
                                                        class="text-md block w-full rounded-md bg-white px-3 py-2 text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400"
                                                        placeholder="Type your current password"
                                                    />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-6">
                                                <label for="new_password" class="text-md block font-medium text-gray-900">New Password:</label>
                                                <div class="mt-2">
                                                    <input
                                                        type="password"
                                                        v-model="passwordForm.new_password"
                                                        class="text-md block w-full rounded-md bg-white px-3 py-2 text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400"
                                                        placeholder="Type your new password"
                                                    />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-6">
                                                <label for="confirm_password" class="text-md block font-medium text-gray-900"
                                                    >Confirm Password:</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        type="password"
                                                        v-model="passwordForm.confirm_password"
                                                        class="text-md block w-full rounded-md bg-white px-3 py-2 text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400"
                                                        placeholder="Confirm your password"
                                                    />
                                                </div>
                                            </div>

                                            <div class="sm:col-span-6">
                                                <button
                                                    type="submit"
                                                    :disabled="passwordForm.processing"
                                                    class="text-md w-full cursor-pointer rounded-lg border border-gray-300 bg-blue-100 px-4 py-2 transition-all duration-200 hover:bg-blue-700 hover:text-white"
                                                >
                                                    <span v-if="passwordForm.processing">Updating...</span>
                                                    <span v-else>Update Password</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AppLayout>
</template>

<style>
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
}
</style>
