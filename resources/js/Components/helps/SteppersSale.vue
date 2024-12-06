<template>
    <div>
        <div id="wizard-create-app" class="bs-stepper vertical mt-2 shadow-none">
            <div class="bs-stepper-header border-0 p-1 pb-0">
                <div class="step active" data-target="#customer">
                    <button type="button" class="step-trigger" aria-selected="true">
                        <span class="bs-stepper-circle"><i class="mdi mdi-account-group"></i></span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title text-uppercase">Cliente</span>
                            <span class="bs-stepper-subtitle">Añade un cliente a la venta</span>
                        </span>
                    </button>
                </div>
                <div class="line"></div>
                <div class="step" data-target="#billing">
                    <button type="button" class="step-trigger" aria-selected="false">
                        <span class="bs-stepper-circle"><i class="mdi mdi-cash"></i></span>
                        <span class="bs-stepper-label">
                            <span class="bs-stepper-title text-uppercase">Facturar</span>
                            <span class="bs-stepper-subtitle">Factura para la venta</span>
                        </span>
                    </button>
                </div>
                <div class="line"></div>
            </div>
            <div class="bs-stepper-content p-1 pb-0">
                <form>
                    <!-- customer -->
                    <div id="customer" class="content pt-4 pt-lg-0 dstepper-block active">
                        <div class="mb-6">
                            <label for="exampleInputEmail1" class="form-label">Application Name</label>
                            <input type="text" class="form-control" id="exampleInputEmail1"
                                placeholder="Application Name">
                        </div>
                        <h5>Category</h5>
                        <ul class="p-0 m-0">
                            <li class="d-flex align-items-start mb-4">
                                <div class="badge bg-label-info p-2 me-3 rounded"><i class="bx bx-file bx-30px"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-1">CRM Application</h6>
                                        <small>Scales with any business</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input name="customer-radio" class="form-check-input" type="radio" value="">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-start mb-4">
                                <div class="badge bg-label-success p-2 me-3 rounded"><i class="bx bx-cart bx-30px"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-1">eCommerce Platforms</h6>
                                        <small>Grow Your Business With App</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input name="customer-radio" class="form-check-input" type="radio" value="">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-start">
                                <div class="badge bg-label-danger p-2 me-3 rounded"><i class="bx bx-laptop bx-30px"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-1">Online Learning platform</h6>
                                        <small>Start learning today</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input name="customer-radio" class="form-check-input" type="radio" value="">
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="col-12 d-flex justify-content-between mt-6">
                            <button class="btn btn-label-secondary btn-prev"> <i
                                    class="bx bx-left-arrow-alt bx-sm me-sm-2 me-0"></i>
                                <span class="align-middle d-sm-inline-block d-none">Previous</span>
                            </button>
                            <button class="btn btn-primary btn-next"> <span
                                    class="align-middle d-sm-inline-block d-none me-sm-2">Next</span> <i
                                    class="bx bx-right-arrow-alt bx-sm"></i></button>
                        </div>
                    </div>

                    <!-- billing -->
                    <div id="billing" class="content pt-4 pt-lg-0 dstepper-block">
                        <h5>Select Framework</h5>
                        <ul class="p-0 m-0">
                            <li class="d-flex align-items-start mb-4">
                                <div class="badge bg-label-info p-2 me-3 rounded"><i class="bx bxl-react bx-30px"></i>
                                </div>
                                <div class="d-flex justify-content-between w-100">
                                    <div class="me-2">
                                        <h6 class="mb-1">React Native</h6>
                                        <small>Create truly native apps</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input name="billing-radio" class="form-check-input" type="radio" value="">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-start mb-4">
                                <div class="badge bg-label-danger p-2 me-3 rounded"><i
                                        class="bx bxl-angular bx-30px"></i></div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-1">Angular</h6>
                                        <small>Most suited for your application</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input name="billing-radio" class="form-check-input" type="radio" value="">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-start mb-4">
                                <div class="badge bg-label-success p-2 me-3 rounded"><i
                                        class="bx bxl-vuejs bx-30px"></i></div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-1">Vue</h6>
                                        <small>JS web billing</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input name="billing-radio" class="form-check-input" type="radio" value="">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-start">
                                <div class="badge bg-label-warning p-2 me-3 rounded"><i
                                        class="bx bxl-html5 bx-30px"></i></div>
                                <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-1">HTML</h6>
                                        <small>Progressive Framework</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input name="billing-radio" class="form-check-input" type="radio" value="">
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <div class="col-12 d-flex justify-content-between mt-6">
                            <button class="btn btn-label-secondary btn-prev"> <i
                                    class="bx bx-left-arrow-alt bx-sm me-sm-2 me-0"></i> <span
                                    class="align-middle d-sm-inline-block d-none">Previous</span>
                            </button>
                            <button class="btn btn-primary btn-next"> <span
                                    class="align-middle d-sm-inline-block d-none me-sm-2">Next</span> <i
                                    class="bx bx-right-arrow-alt bx-sm"></i></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>
<script setup>

// IMPORTS --------------------------
import { getCurrentInstance, onMounted, ref, reactive, watch } from "vue";

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