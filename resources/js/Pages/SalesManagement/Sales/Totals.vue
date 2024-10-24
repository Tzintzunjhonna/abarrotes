<script setup>
import { integer } from '@vuelidate/validators';
import { reactive, ref } from 'vue'

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
})

</script>

<template>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Totales</h4>

        <div class="row">
          <div class="col-sm-12">
            <div class="float-center">
              <p><span class="font-weight-medium">Subtotal:</span> <span class="float-end">${{ amountImport }}</span></p>
              <p><span class="font-weight-medium">Descuento:</span> <span class="float-end">${{ amountDiscount }}</span></p>
              <h2>
                <span class="badge badge-soft-success float-end">${{ amountTotal }} MXN</span>
              </h2>
            </div>
            <div class="clearfix"></div>
          </div> <!-- end col -->
        </div>

      </div> <!-- end card-body-->
    </div> <!-- end card-->
  </div>
</template>
