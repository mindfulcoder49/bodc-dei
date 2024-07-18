<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const user = usePage().props.auth.user;

const form = useForm({
    role: user.role,
});

const updateUserRole = () => {
    form.put(route('role.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.recentlySuccessful = true; // Trigger a state change to show success message
            setTimeout(() => form.recentlySuccessful = false, 2500); // Reset after 2.5 seconds
        },
        onError: () => {
            if (form.errors.role) {
                form.reset('role');
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Update User Role</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your User Role
            </p>
        </header>

        <form @submit.prevent="updateUserRole" class="mt-6 space-y-6">
            <div>
                <InputLabel for="role" value="User Role" />

                <TextInput
                    id="role"
                    ref="roleInput"
                    v-model="form.role"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="role"
                />

                <InputError :message="form.errors.role" class="mt-2" />
            </div>


            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
