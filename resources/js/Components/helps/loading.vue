<template>
    <div v-if="isLoading == true" id="loading">
        <img class="img" src="../../../../public/images/abarrotes/logo.png" alt="loading..." width="200" />
        <h2>{{ isLoading }}</h2>
    </div>
</template>

<script setup>
import { ref, defineExpose, reactive} from 'vue';

let isLoading = reactive(false);

const startLoading = () => {
    isLoading = true;
};

const stopLoading = () => {
    isLoading = false;
};

defineExpose({
    startLoading,
    stopLoading,
});
</script>

<style>
#loading {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    background-color: rgba(255, 255, 255, 0.9);
    /* Fondo ligeramente transparente */
    height: 100vh;
    width: 100vw;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    animation: fadeIn 1s ease-out;
}

.img {
    animation: rotate 2s linear infinite;
    /* Animación de rotación */
}

@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

@keyframes fadeIn {
    0% {
        opacity: 0;
    }

    100% {
        opacity: 1;
    }
}
</style>
