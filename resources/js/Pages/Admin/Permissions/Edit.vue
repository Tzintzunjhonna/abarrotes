<script setup>

// IMPORTS --------------------------
import { Head, Link, router } from '@inertiajs/vue3';
import {computed, getCurrentInstance, onMounted, ref} from "vue";
import { useVuelidate } from '@vuelidate/core';
import { helpers, required, email } from '@vuelidate/validators';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';

import Footer from '@/Layouts/Footer.vue';

// VARIABLES --------------------------
const app = getCurrentInstance()
const api = app.appContext.config.globalProperties.api
const alert = app.appContext.config.globalProperties.alert

const title = ref('Editar permiso')
const prevPageName = ref('Roles')
const pageNowName = ref('Editar permiso')
const prevPageUrl = ref('admin/permisos')


const is_disabled = ref(true);
const text_disabled = ref('Editar');
const class_button_disabled = ref('btn btn-primary');
const icon_disabled = ref('mdi mdi-content-save');

const props = defineProps({
    info_get: {
        type: Object,
        required: true,
    }
});

const form = ref({
    name    : '',
    guard_name    : '',
})

const rolesSelect = ref(
    [
        { name: 'Admin', id: 1 },
        { name: 'Editor', id: 2 },
        { name: 'Subscriber', id: 3 },
        // Agrega más permisos aquí
    ]
);

// MOUNTED  --------------------------
onMounted(() => {
    getData()
});

// VALIDACION DE FORMULARIOS --------------------------

const validateRulesForm = {
    name: {
        required: helpers.withMessage(
            'El campo nombre es requerido.',
            required,
        )
    },
    guard_name: {
        required: helpers.withMessage(
            'El campo es requerido.',
            required,
        ),
    }
};

const f$ = useVuelidate(validateRulesForm, form);


// FUNCIONES --------------------------

function getData() {
    form.value.name     = props.info_get.name;
    form.value.guard_name = props.info_get.guard_name;
    
}

async function onSubmit() {
    console.log('onSubmit')
    const isFormCorrect = await f$.value.$validate();
    console.log(isFormCorrect)
    console.log(f$.value)
    if (!isFormCorrect) return;
    
    const formData = new FormData();

    formData.append('name', form.value.name);
    formData.append('guard_name', form.value.guard_name);

    api
        .put(`v1/app-permissions/${props.info_get.id}/update`, formData)
        .then((response) => {
            alert.apiSuccess({ title: response.message, description: ''}, config).then((result) => {
                if (result.isConfirmed) {
                    router.visit(`/admin/permisos`);
                }
            });
        })
        .catch((error) => {
            console.log(error)
            if (error.message) {
                alert.apiError({
                    title: 'Error en la operación',
                    error: error.message
                });
            } else {
                alert.apiError({
                    title: 'Error en la operación',
                    error: error.errors.message
                });
            }

            
        })
}

function btnIndex() {

    router.visit(`/admin/permisos`);

}


function btnEdit() {

    is_disabled.value = !is_disabled.value;
    if (is_disabled.value == false) {
        text_disabled.value = 'Cancelar edicion';
        class_button_disabled.value = 'btn btn-danger';
        icon_disabled.value = 'mdi mdi-book-cancel';
    } else {
        text_disabled.value = 'Editar';
        class_button_disabled.value = 'btn btn-primary';
        icon_disabled.value = 'mdi mdi-content-save';
    }
}


</script>

<template>

    <MenuPage />
    <div class="content">
        <div class="container-fluid">

            <Head :title="title" />
            <PageTitle :title="title" :prevPageName="prevPageName" :prevPageUrl="prevPageUrl"
                :pageNowName="pageNowName" />
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">
                                <a ref="#" type="button" title="Regresar" class="m-2" @click="btnIndex()">
                                    <i class="mdi mdi-page-previous-outline"></i>
                                </a>
                                Editar permiso
                                <span class="badge bg-danger">
                                    {{ info_get.name }}
                                </span>
                            </h4>
                            <div class="col-12 d-flex justify-content-end">
                                <button :class="class_button_disabled" type="button" @click="btnEdit()">
                                    <i :class="icon_disabled"></i>
                                    {{ text_disabled }}
                                </button>
                            </div>

                            <form class="needs-validation" @submit.prevent="onSubmit">
                                <div class="row">

                                    <div class="mb-2 col-md-6">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input :disabled="is_disabled" v-model="form.name" type="text"
                                            class="form-control" id="name" name="name" placeholder="Nombre">
                                        <div class="input-errors" v-for="error of f$.name.$errors" :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="guard_name" class="form-label">Nombre del permiso</label>
                                        <input :disabled="is_disabled" v-model="form.guard_name" type="text"
                                            class="form-control" id="guard_name" placeholder="Nombre del permiso">
                                        <div class="input-errors" v-for="error of f$.guard_name.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-primary" type="submit" v-if="!is_disabled">
                                    <i class="mdi mdi-content-save"></i>
                                    Guardar
                                </button>
                            </form>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div>
            </div>
        </div>
    </div>

    <Footer />

</template>

<style scope>

</style>