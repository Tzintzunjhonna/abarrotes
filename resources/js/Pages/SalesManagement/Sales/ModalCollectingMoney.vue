<template>
    <div class="modal fade" id="modalCollectingMoneyView" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mb-6">
                        <h4 class="address-title mb-2">Cobrar venta</h4>
                        <p class="address-subtitle">Selecciona una opcion de cobro para continuar con la venta</p>
                    </div>
                    <form id="modalCollectingMoneyViewForm" class="row g-6 fv-plugins-bootstrap5 fv-plugins-framework"
                        @submit.prevent="submitForm">

                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md mb-md-0 mb-4">
                                        <div class="form-check custom-option custom-option-icon"
                                            :class="type_payment == 1 ? 'checked' : ''">
                                            <label class="form-check-label custom-option-content" for="CashPayment">
                                                <span class="custom-option-body">
                                                    <CashPayment :title="'Efectivo'"
                                                        :subtitle="'Cobra la venta en efectivo.'" />

                                                </span>
                                                <input v-model="type_payment" :value="1" name="CashPayment"
                                                    class="form-check-input" type="radio" id="CashPayment">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md mb-md-0 mb-4">
                                        <div class="form-check custom-option custom-option-icon"
                                            :class="type_payment == 2 ? 'checked' : ''">
                                            <label class="form-check-label custom-option-content" for="CardPayment">
                                                <span class="custom-option-body">
                                                    <CardPayment :title="'Tarjeta de débito o crédito'"
                                                        :subtitle="'Cobra la venta con pasarela de pago.'" />
                                                </span>
                                                <input v-model="type_payment" :value="2" name="CardPayment"
                                                    class="form-check-input" type="radio" id="CardPayment">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md mb-md-0 mb-4">
                                        <div class="form-check custom-option custom-option-icon"
                                            :class="type_payment == 3 ? 'checked' : ''">
                                            <label class="form-check-label custom-option-content" for="VoucherPayment">
                                                <span class="custom-option-body">
                                                    <VoucherPayment :title="'Vales'"
                                                        :subtitle="'Cobra la venta con vales de despensa.'" />
                                                </span>
                                                <input v-model="type_payment" :value="3" name="VoucherPayment"
                                                    class="form-check-input" type="radio" id="VoucherPayment">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- wizard -->
                        <div class="mt-5">
                            <SteppersSale />
                        </div>
                        <input type="hidden">
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

// IMPORTS --------------------------
import { getCurrentInstance, onMounted, ref, reactive, watch } from "vue";
import CashPayment from '@/Components/images/CashPayment.vue';
import CardPayment from '@/Components/images/CardPayment.vue';
import VoucherPayment from '@/Components/images/VoucherPayment.vue';
import SteppersSale from '@/Components/helps/SteppersSale.vue';

// VARIABLES --------------------------
const emit = defineEmits(['btnAction'])

const props = defineProps({
    amountTotal: {
        type: Number,
        required: true,
    },
    method: {
        type: String
    },
});

const type_payment = ref(null);


// FUNCTIONS ----------------------------


function submitForm() {
    btnAction({ action: props.method, value: true })
}

function btnAction(value) {
    emit('btnAction', value)
}

</script>