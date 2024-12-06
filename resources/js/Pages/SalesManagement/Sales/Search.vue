<script setup>
import { reactive, ref } from 'vue'

const emit = defineEmits(['btnAction'])
const props = defineProps({
  title: {
    type: String
  },
  method: {
    type: String
  },
})

let search = ref({
  barcode: '',
})


async function submitForm() {
  btnAction({ action: props.method, value: search.value.barcode })
  search.value.barcode = '';
}

function btnAction(value) {
  emit('btnAction', value)
}

function btnSearchBarcode() {
  btnAction({ action: 'on_view_modal_search_product', value: true })
}
</script>

<template>
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Busqueda de productos</h4>

        <form class="needs-validation" @submit.prevent="submitForm">
          <div class="row">

            <div class="mb-2 col-md-12 mt-4">
              <label for="barcode" class="form-label">Código del producto</label>
              <input v-model="search.barcode" type="text" class="form-control" id="barcode"
                placeholder="Código del producto">
            </div>
          </div>

          <button class="btn btn-primary" type="submit">
            <i class="mdi mdi-content-save"></i>
            Agregar
          </button>
          <button class="btn btn-warning m-2" type="button" @click="btnSearchBarcode">
            <i class="mdi mdi-text-search"></i>
            Abrir buscador de código
          </button>
        </form>

      </div>
    </div>
  </div>
</template>
