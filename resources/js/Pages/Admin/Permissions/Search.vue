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
  name: '',
})


async function submitForm() {
  btnAction({ action: props.method, value: search.value })
}

function btnAction(value) {
  emit('btnAction', value)
}
</script>

<template>
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Busqueda de permisos</h4>

        <form class="needs-validation" @submit.prevent="submitForm">
          <div class="row">

            <div class="mb-2 col-md-6">
              <label for="name" class="form-label">Nombre</label>
              <input v-model="search.name" type="text" class="form-control" id="name" placeholder="Nombre">
            </div>
          </div>

          <button class="btn btn-primary" type="submit">
            <i class="mdi mdi-cloud-search-outline"></i>
            Buscar
          </button>
        </form>

      </div> <!-- end card-body-->
    </div> <!-- end card-->
  </div>
</template>
