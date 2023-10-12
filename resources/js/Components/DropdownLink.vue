<script setup>
    import { Link } from '@inertiajs/vue3';

    defineProps({
        href: {
            type: String,
            required: true,
        },
    });
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 
               text-left text-sm text-gray-700 
               hover:bg-gray-100 focus:outline-none focus:bg-gray-100 
               leading-5 transition duration-150 ease-in-out"
    >
        <slot />
    </Link>
</template>
