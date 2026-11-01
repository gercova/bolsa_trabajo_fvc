<script setup>
import ErrorPage from '@/Pages/Error.vue';

defineProps({
    status: {
        type: [Number, String],
        default: 404,
    },
    message: {
        type: String,
        default: '',
    },
    description: {
        type: String,
        default: '',
    },
    enterprise: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <ErrorPage v-bind="$props" />
</template>
