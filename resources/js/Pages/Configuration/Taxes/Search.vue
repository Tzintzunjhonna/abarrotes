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
  cat_impuesto: {
    type: Object,
    required: true,
  },
})

let search = ref({
  tipo_impuesto_id: '',
  percentage: '',
})


async function submitForm() {
  const search_form = {
    tipo_impuesto_id: search.value.tipo_impuesto_id?.id,
    percentage: search.value.percentage,
  }
  btnAction({ action: props.method, value: search_form })
}

function btnAction(value) {
  emit('btnAction', value)
}
</script>

<template>
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">Busqueda de impuestos</h4>

        <form class="needs-validation" @submit.prevent="submitForm">
          <div class="row">

            <div class="mb-2 col-md-6">
              <label for="name" class="form-label">Impuesto</label>
              <Multiselect v-model="search.tipo_impuesto_id" track-by="nombre" label="nombre"
                placeholder="Selecciona un tipo de impuesto" :show-labels="false" deselectLabel=" "
                :block-keys="['Tab', 'Enter']" :options="cat_impuesto" :searchable="true" :allow-empty="true"
                :showNoOptions="false">
                <template v-slot:noResult>
                  <span>Opción no encontrada</span>
                </template>
              </Multiselect>
            </div>
            <div class="mb-2 col-md-6">
              <label for="percentage" class="form-label">Porcentaje %</label>
              <input v-model="search.percentage" type="number" class="form-control" id="percentage"
              placeholder="Porcentaje" step="0.01"
              oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
              maxlength="5">
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
