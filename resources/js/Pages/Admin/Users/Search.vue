<script setup>
import { reactive, } from 'vue'

const emit = defineEmits(['btnAction'])
const props = defineProps({
  title: {
    type: String
  },
  method: {
    type: String
  },
})

let search = reactive({
  name: '',
  email: '',
})


async function submitForm() {
  btnAction({ action: props.method, value: search })
}

function btnAction(value) {
  emit('btnAction', value)
}
</script>

<template>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Busqueda de usuarios</h4>

                <form class="needs-validation" @submit.prevent="submitForm">
                    <div class="row">

                        <div class="mb-2 col-md-6">
                            <label for="name" class="form-label">Nombre</label>
                            <input v-model="search.name" type="text" class="form-control" id="name"
                                placeholder="Nombre">
                        </div>
                        <div class="mb-2 col-md-6">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input v-model="search.email" type="text" class="form-control" id="email"
                                placeholder="Correo electrónico">
                        </div>
                    </div>

                    <button class="btn btn-primary" type="submit">Buscar</button>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
</template>
