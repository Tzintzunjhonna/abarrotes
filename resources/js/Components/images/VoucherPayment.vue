<template>
    <span>
        <img src="/images/sales/pago_vale.png" alt="" width="" class="avatar-lg">
    </span>
    <span class="custom-option-title">{{ title }}</span>
    <small> {{ subtitle }} </small>
</template>
<script setup>

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        required: true,
    }
});
</script>