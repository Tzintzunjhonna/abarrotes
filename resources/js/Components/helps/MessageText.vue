<template>
    <div class="modal fade" id="modalMessageText" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: calc(50% - 100px);">
            <form class="modal-content" @submit.prevent="submitForm">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ form.title}}
                    </h5>
                </div>
                <div class="modal-body">

                    <transition leave-active-class="transition ease-in duration-1000" leave-from-class="opacity-100"
                        leave-to-class="opacity-0">
                        <div class="text-sm text-gray-600">
                            <slot />
                            {{ form.messaje }}
                        </div>
                    </transition>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"
                        title="Cerrar ventana emergente">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script setup>

// IMPORTS --------------------------
import { getCurrentInstance, onMounted, ref, computed, watch } from "vue";

// VARIABLES --------------------------
const { proxy } = getCurrentInstance();
const emit = defineEmits(['btnAction'])

const form = ref({
    title: 'Mensaje a mostrar',
    messaje: '',
});
// PROPS
const props = defineProps({
});

// MOUNTED  --------------------------
onMounted(() => {
});

// WATCH  --------------------------

// FUNCIONES --------------------------

function getData(title, message) {
    form.value.title = title;
    form.value.messaje = message;
}

defineExpose({
    getData
});
</script>