<script setup>
import { reactive, ref } from "vue";

const emit = defineEmits(["btnAction"]);

const props = defineProps({
  amountImport: {
    type: Number,
    required: true,
  },
  amountDiscount: {
    type: Number,
    required: true,
  },
  amountTotal: {
    type: Number,
    required: true,
  },
  amountTax: {
    type: Number,
    required: true,
  },
  method: {
    type: String
  },
});

function submitForm() {
  btnAction({ action: props.method, value: true });
}

function btnAction(value) {
  emit("btnAction", value);
}
</script>

<template>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Totales</h4>
        <form class="needs-validation" @submit.prevent="submitForm">
          <div class="row">
            <div class="col-sm-12">
              <div class="float-center">
                <p>
                  <span class="font-weight-medium">Subtotal:</span>
                  <span class="float-end">${{ amountImport }}</span>
                </p>
                <p>
                  <span class="font-weight-medium">Descuento:</span>
                  <span class="float-end">${{ amountDiscount }}</span>
                </p>
                <p>
                  <span class="font-weight-medium">Impuesto:</span>
                  <span class="float-end">${{ amountTax }}</span>
                </p>
                <div class="row">
                  <div class="col-sm-6">
                    <button class="btn btn-outline-success" type="submit">
                      <i class="mdi mdi-cash-register mdi-18px"></i>
                      Cobrar venta
                    </button>
                  </div>
                  <div class="col-sm-6">
                    <h2>
                      <span class="badge badge-soft-success float-end">${{ amountTotal }} MXN</span>
                    </h2>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
            <!-- end col -->
          </div>
        </form>
      </div>
      <!-- end card-body-->
    </div>
    <!-- end card-->
  </div>
</template>
