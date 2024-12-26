<script setup>
import { reactive, ref, defineExpose } from 'vue'

const emit = defineEmits(['btnAction'])
const props = defineProps({
  title: {
    type: String
  },
  method: {
    type: String
  },
  cat_payment_type: {
    type: Array,
    required: true,
  },
  cat_status_sale: {
    type: Array,
    required: true,
  },
})

let search = ref({
  ticket_no: '',
  customer: '',
  payment_type_id: '',
  status_sale_id: '',
})


async function submitForm() {

  const search_form = formData()

  btnAction({ action: props.method, value: search_form })
}

const submitFormExport = () => {
  

  return formData()
};




function formData(value) {

  const search_form = {

    ticket_no: search.value.ticket_no,
    customer: search.value.customer,
    status_sale_id: (search.value.status_sale_id != undefined && search.value.status_sale_id != '') ? search.value.status_sale_id?.id : '',
    payment_type_id: (search.value.payment_type_id != undefined && search.value.payment_type_id != '') ? search.value.payment_type_id?.id : '',
  }

  return search_form
}


function btnAction(value) {
  emit('btnAction', value)
}

defineExpose({
  submitFormExport
});
</script>

<template>
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Busqueda de ventas</h4>

        <form class="needs-validation" @submit.prevent="submitForm">
          <div class="row">

            <div class="mb-2 col-md-6">
              <label for="name" class="form-label">No. Ticket</label>
              <input v-model="search.ticket_no" type="text" class="form-control" id="name" placeholder="Nombre">
            </div>
            <div class="mb-2 col-md-6">
              <label for="description" class="form-label">Cliente</label>
              <input v-model="search.customer" type="text" class="form-control" id="name" placeholder="Descripción">
            </div>
            <div class="mb-2 col-md-6">
              <label for="name_provider" class="form-label">Estatus de venta</label>
              <Multiselect v-model="search.status_sale_id" track-by="name" label="name"
                placeholder="Selecciona un estatus" :show-labels="false" deselectLabel=" "
                :block-keys="['Tab', 'Enter']" :options="cat_status_sale" :searchable="true" :allow-empty="true"
                :showNoOptions="false">
                <template v-slot:noResult>
                  <span>Opción no encontrada</span>
                </template>
              </Multiselect>
            </div>
            <div class="mb-2 col-md-6">
              <label for="barcode" class="form-label">Tipo de cobro</label>
              <Multiselect v-model="search.payment_type_id" track-by="name" label="name"
                placeholder="Selecciona un tipo de cobro" :show-labels="false" deselectLabel=" "
                :block-keys="['Tab', 'Enter']" :options="cat_payment_type" :searchable="true" :allow-empty="true"
                :showNoOptions="false">
                <template v-slot:noResult>
                  <span>Opción no encontrada</span>
                </template>
              </Multiselect>
            </div>
          </div>

          <button class="btn btn-primary" type="submit">
            <i class="mdi mdi-text-search"></i>
            Buscar
          </button>
        </form>

      </div> <!-- end card-body-->
    </div> <!-- end card-->
  </div>
</template>
