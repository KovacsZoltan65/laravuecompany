<script setup>
    import { Head, useForm, Link } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import InputError from '../../Components/InputError.vue';
    import InputLabel from '../../Components/InputLabel.vue';
    import PrimaryButton from '../../Components/PrimaryButton.vue';
    import TextInput from '../../Components/TextInput.vue';

    const form = useForm({
        name: ''
    });
</script>

<template>
    <Head title="Create Department" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Department</h2>
        </template>
        <template #main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="p-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form @submit.prevent="$event => form.post(route('departments.store'))" 
                            class="mt-6 space-y-6 max-w-xl">
                            <InputLabel for="name" :value="form.name">Name</InputLabel>
                            <TextInput id="name" v-model="form.name" 
                                    class="mt-1 block w-full" 
                                    type="text" autofocus required></TextInput>
                            <InputError :message="form.errors.name" 
                                        class="mt-2"></InputError>
                            
                            <PrimaryButton :disabled="form.processing">
                                <i class="fa-solid fa-save"></i> Save
                            </PrimaryButton>

                            <Link :href="route('departments.index')"
                                :class="'px-4 py-2 bg-yellow-400 text-white border rounded-md font-semibold text-xs'">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </Link>

                        </form>
                    </div>
                </div>
            </div>
        </template>
    </AuthenticatedLayout>
</template>