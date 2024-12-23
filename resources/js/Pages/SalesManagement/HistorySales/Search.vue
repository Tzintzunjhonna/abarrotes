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
})

let search = ref({
  name: '',
  description: '',
  name_provider: '',
  barcode: '',
  category_id: '',
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

    name: search.value.name,
    description: search.value.description,
    name_provider: search.value.name_provider,
    barcode: search.value.barcode,
    category_id: (search.value.category_id != undefined && search.value.category_id != '') ? search.value.category_id?.id : '',
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
              <label for="name" class="form-label">Nombre</label>
              <input v-model="search.name" type="text" class="form-control" id="name" placeholder="Nombre">
            </div>
            <div class="mb-2 col-md-6">
              <label for="description" class="form-label">Descripción</label>
              <input v-model="search.description" type="text" class="form-control" id="name" placeholder="Descripción">
            </div>
            <div class="mb-2 col-md-6">
              <label for="name_provider" class="form-label">Nombre de proveedor</label>
              <input v-model="search.name_provider" type="text" class="form-control" id="name"
                placeholder="Nombre de proveedor">
            </div>
            <div class="mb-2 col-md-6">
              <label for="barcode" class="form-label">Código de producto</label>
              <input v-model="search.barcode" type="text" class="form-control" id="name"
                placeholder="Código de producto">
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
