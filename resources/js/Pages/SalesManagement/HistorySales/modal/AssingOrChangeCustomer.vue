<template>
    <div class="modal fade" id="modalAssingOrChangeCustomer" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: calc(50% - 100px);">
            <form class="modal-content" @submit.prevent="submitForm">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Buscar cliente para asignar o cambiar
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 mb-4">
                        <label class="text-input">Cliente (Razón social - RFC)</label>
                        <Multiselect v-model="form.customer" track-by="business_name" label="business_name"
                            placeholder="Selecciona un cliente" :show-labels="false" deselectLabel=" "
                            :block-keys="['Tab', 'Enter']" :options="cat_customer" :searchable="true"
                            :allow-empty="true" :showNoOptions="false">

                            <template v-slot:option="{ option }">
                                <span>{{ option.business_name }} ({{ option.rfc }})</span>
                            </template>

                            <template v-slot:noResult>
                                <span>Opción no encontrada</span>
                            </template>
                        </Multiselect>

                    </div>
                    <div class="col-md-12 col-sm-12 mb-4">
                        <p>
                            Al activar este botón, la venta será facturada y el responsable de facturación
                            deberá emitir la factura correspondiente.
                        </p>
                        <div class="form-check form-switch">
                            <input v-model="form.is_with_invoice" class="form-check-input" type="checkbox"
                                id="switch_is_with_invoice">

                            <label class="form-check-label ml-2" for="switch_is_with_invoice">Facturar
                                venta</label>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">

                    <button class="btn btn-primary" type="submit" title="Agregar producto a lista de venta">
                        <i class="mdi mdi-content-save"></i>
                        Agregar o cambiar cliente a la venta
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"
                        title="Cerrar ventana emergente" @click="resetForm">
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
    customer : null,
    is_with_invoice : false
});



// PROPS
const props = defineProps({
    method: {
        type: String
    },
    cat_customer: {
        type: Array,
        required: true,
    },
    customer_sale: {
        type: Array,
    },
});

// MOUNTED  --------------------------
onMounted(() => {
});

// WATCH  --------------------------

// FUNCIONES --------------------------

function submitForm() {

    if (form.value.customer === null) {
        proxy.alert.apiWarning({
            title: 'Advertencia',
            error: 'No ha seleccionado un cliente para asignar.'
        });
        return;
    }

    btnAction({ action: props.method, value: form.value })
}

function btnAction(value) {
    emit('btnAction', value)
}

function resetForm() {
    form.value.customer = null;
    form.value.is_with_invoice = null;
}


function getData(sale) {
    form.value.customer = sale.customer;
    form.value.is_with_invoice = sale.is_with_invoice == 1 ? true : false;
}

defineExpose({
    getData
});
</script>