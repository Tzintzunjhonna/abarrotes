<template>
    <div>
        <h5 class="header-title mb-4">
            <div class="form-check form-switch">
                <input v-model="form.options_sale" class="form-check-input" type="checkbox" id="switch_options">

                <label class="form-check-label ml-2" for="switch_options">
                    Opciones de venta</label>
            </div>
        </h5>

        <div class="row" v-if="form.options_sale == true">
            <div class="col-sm-3">
                <div class="nav flex-column nav-pills nav-pills-tab" id="v-pills-tab" role="tablist"
                    aria-orientation="vertical">
                    <a class="nav-link show mb-1" id="v-pills-home-tab" data-bs-toggle="pill" href="#v-pills-home"
                        role="tab" aria-controls="v-pills-home" aria-selected="false" tabindex="-1"
                        :class="form.options_sale == true ? 'active' : ''">
                        <span class="bs-stepper-title text-uppercase">Cliente</span>
                        <span class="bs-stepper-subtitle">Añade un cliente a la venta</span>
                    </a>
                    <a class="nav-link mb-1" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile"
                        role="tab" aria-controls="v-pills-profile" aria-selected="false" tabindex="-1">
                        <span class="bs-stepper-title text-uppercase">Facturar</span>
                        <span class="bs-stepper-subtitle">Incluir factura para la venta</span>
                    </a>
                </div>
            </div> <!-- end col-->
            <div class="col-sm-9">
                <div class="tab-content pt-0">
                    <div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab"
                        :class="form.options_sale == true ? 'active show' : ''">
                        <p>
                            Selecciona un cliente al que se le asignará la venta.
                        </p>
                        <div class="mb-2 col-md-12">
                            <label for="customer" class="form-label">Cliente</label>
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
                    </div>
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                        aria-labelledby="v-pills-profile-tab">
                        <div class="mb-2 col-md-12">
                            <p>
                                Al activar este botón, la venta será facturada y el responsable de facturación
                                deberá emitir la factura correspondiente.
                            </p>
                            <div class="form-check form-switch">
                                <input v-model="form.is_invoice" class="form-check-input" type="checkbox"
                                    id="switch_is_invoice">

                                <label class="form-check-label ml-2" for="switch_is_invoice">Facturar
                                    venta</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div> <!-- end col-->
        </div> <!-- end row-->

    </div>
</template>
<script setup>

// IMPORTS --------------------------
import { getCurrentInstance, onMounted, ref, reactive, watch } from "vue";

// VARIABLES --------------------------
const emit = defineEmits(['btnAction'])

const props = defineProps({
    method: {
        type: String
    },
    cat_customer: {
        type: Array,
        required: true,
    },
});

const type_payment = ref(null);

const form = ref({
    customer: null,
    is_invoice: false,
    options_sale: false,
});


// FUNCTIONS ----------------------------


function submitForm() {
    btnAction({ action: props.method, value: true })
}

function btnAction(value) {
    emit('btnAction', value)
}

</script>