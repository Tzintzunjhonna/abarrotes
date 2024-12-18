<template>
    <div class="modal fade" id="modalCollectingMoneyView" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mb-6">
                        <h4 class="address-title mb-2 text-primary">Cobrar venta</h4>
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
                        <div class="p-0 mb-6 mt-2">
                            <div class="card-body d-flex flex-column flex-md-row justify-content-between p-0 pt-6">
                                <div
                                    class="card-body d-flex align-items-md-center flex-column text-md-center mb-6 py-6">
                                    <template v-if="type_payment == 1">
                                        <span class="card-title mb-4 px-md-12 h4">
                                            Realizará el cobró en
                                            <span class="text-primary text-nowrap">efectivo</span>.
                                        </span>
                                        <span class="card-title mb-4 px-md-12 h4">
                                            ${{ amountTotal }}
                                        </span>
                                        <div class="mb-2 col-md-4">
                                            <label for="paid_with" class="form-label">Pagó con:</label>
                                            <input v-model="form.paid_with" type="number" class="form-control"
                                                id="paid_with" placeholder="Montó de pago" step="0.001"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="10" @input="handleInputPaidWith">

                                            <label for="his_change" class="form-label">Su cambio es:</label>

                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1"
                                                    :class="class_his_change.class">
                                                    <i :class="class_his_change.icon"></i>
                                                </span>
                                                <input v-model="form.his_change" type="number" class="form-control"
                                                    :class="class_his_change.class" id="his_change" placeholder="Cambio"
                                                    disabled
                                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                    maxlength="10">
                                            </div>
                                        </div>
                                    </template>
                                    <template v-if="type_payment == 2">
                                        <span class="card-title mb-4 px-md-12 h4">
                                            Realizará el cobró en
                                            <span class="text-primary text-nowrap">pasarela de pago</span>.
                                        </span>
                                        <p class="mb-4">
                                            Recuerde agregar el número de referencia de la transacción para su registro.
                                        </p>
                                        <div class="mb-2 col-md-4">
                                            <label for="card_payment_reference" class="form-label">Referencia:</label>
                                            <input v-model="form.card_payment_reference" type="text"
                                                class="form-control" id="card_payment_reference"
                                                placeholder="Referencia de pasera de pago" step="0.001" maxlength="40">
                                        </div>
                                    </template>
                                    <template v-if="type_payment == 3">
                                        <span class="card-title mb-4 px-md-12 h4">
                                            Realizará el cobró con
                                            <span class="text-primary text-nowrap">vale de despensa</span>.
                                        </span>
                                        <p class="mb-4">
                                            Recuerde agregar el número de referencia y/o el número de vale para un
                                            correcto control de sus ventas.
                                        </p>
                                        <div class="mb-2 col-md-4">
                                            <label for="voucher_payment_reference"
                                                class="form-label">Referencia:</label>
                                            <input v-model="form.voucher_payment_reference" type="text"
                                                class="form-control" id="voucher_payment_reference"
                                                placeholder="Referencia de vale de despensa" maxlength="40">
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <SteppersSale :cat_customer="props.cat_customer" />
                        </div>
                        <div class="modal-footer">

                            <button class="btn btn-outline-success" type="submit"
                                title="Agregar producto a lista de venta">
                                <i class="mdi mdi-cash-100"></i>
                                Cobrar
                            </button>
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"
                                title="Cerrar ventana emergente" @click="resetForm">
                                Cerrar
                            </button>
                        </div>
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
        default: 0
    },
    method: {
        type: String
    },
    cat_customer: {
        type: Array,
        required: true,
    },
});

const class_his_change = ref({
    class: '',
    icon: '',
});
const type_payment = ref(null);

const form = ref({
    paid_with : '',
    his_change : '',
    card_payment_reference : '',
    voucher_payment_reference : '',
});


// FUNCTIONS ----------------------------


function submitForm() {
    btnAction({ action: props.method, value: true })
}

function btnAction(value) {
    emit('btnAction', value)
}

function handleInputPaidWith() {
    class_his_change.value.class = '';
    class_his_change.value.icon = 'mdi mdi-cash-check';

    let his_change = props.amountTotal - form.value.paid_with;
    form.value.his_change = his_change;
    
    if (his_change < 0){
        class_his_change.value.class = 'border-danger';
        class_his_change.value.icon = 'mdi mdi-cash-remove';
    }
}

</script>