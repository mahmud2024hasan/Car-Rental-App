<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { TriangleAlert, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';

// Components
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const deleteUser = (e: Event) => {
    e.preventDefault();

    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="space-y-6">
        <div class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10">
            <div class="">
                <h1 class="text-xl font-semibold text-gray-700">Delete Account</h1>
                <p class="text-sm text-gray-500">Delete your account and all of its resources</p>
            </div>
            <div class="flex items-center dark:text-red-100 border border-red-200 rounded-md px-3">
                <TriangleAlert class="h-24 w-24 text-red-500" />
                <div class="ml-2 text-red-500">
                    <p class="font-semibold">Warning!</p>
                    <p class="text-sm">Deleting your account will permanently erase your profile, rental history, and all data. Please consider carefully before proceeding. This cannot be undone.</p>
                </div>
            </div>
            <Dialog>
                <DialogTrigger as-child>
                    <Button variant="destructive" class="w-full cursor-pointer bg-red-200 text-red-600 hover:bg-red-700 hover:text-white text-md"> <Trash2 class="h-5 w-5" /> Delete account</Button>
                </DialogTrigger>
                <DialogContent>
                    <form class="space-y-6" @submit="deleteUser">
                        <DialogHeader class="space-y-3">
                            <DialogTitle>Are you sure you want to delete your account?</DialogTitle>
                            <DialogDescription>
                                Once your account is deleted, all of its resources and data will also be permanently deleted. Please enter your
                                password to confirm you would like to permanently delete your account.
                            </DialogDescription>
                        </DialogHeader>

                        <div class="grid gap-2">
                            <Label for="password" class="sr-only">Password</Label>
                            <Input id="password" type="password" name="password" ref="passwordInput" v-model="form.password" placeholder="Password" />
                            <InputError :message="form.errors.password" />
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary" @click="closeModal" class="cursor-pointer"> Cancel </Button>
                            </DialogClose>

                            <Button variant="destructive" :disabled="form.processing">
                                <button type="submit" class="cursor-pointer">Delete account</button>
                            </Button>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
