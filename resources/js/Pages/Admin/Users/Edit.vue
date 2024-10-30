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
const { proxy } = getCurrentInstance();

const title = ref('Editar usuario')
const prevPageName = ref('Usuarios')
const pageNowName = ref('Editar usuario')
const prevPageUrl = ref('admin/usuarios')

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    rolesCat: {
        type: Object,
        required: true,
    },
});

const form = ref({
    name    : '',
    role    : '',
    password: '',
    email   : '',
})

const is_disabled = ref(true);
const text_disabled = ref('Editar');
const class_button_disabled = ref('btn btn-primary');
const icon_disabled = ref('mdi mdi-content-save');

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
    email: {
        required: helpers.withMessage(
            'El campo email es requerido.',
            required,
        ),
        email: helpers.withMessage(
            "El campo debe ser un de correo electrónico valido.",
            email
        ),
    },
    role: {
        required: helpers.withMessage(
            'El campo es requerido.',
            required,
        ),
    }
};

const f$ = useVuelidate(validateRulesForm, form);


// FUNCIONES --------------------------

function getData() {
    form.value.name     = props.user.name;
    form.value.email    = props.user.email;
    if (props.user.roles?.length > 0){
        form.value.role = props.user.roles[0];
    }
    
}

async function onSubmit() {
    console.log('onSubmit')
    const isFormCorrect = await f$.value.$validate();
    console.log(isFormCorrect)
    console.log(f$.value)
    if (!isFormCorrect) return;
    
    const formData = new FormData();

    formData.append('name', form.value.name);
    formData.append('role', form.value.role.id);
    formData.append('password', form.value.password);
    formData.append('email', form.value.email);

    proxy.api
        .put(`v1/app-users/${props.user.id}/update`, formData)
        .then((response) => {
            proxy.alert.apiSuccess({ title: response.message, description: ''}, config).then((result) => {
                if (result.isConfirmed) {
                    router.visit(`/admin/usuarios`);
                }
            });
        })
        .catch((error) => {
            console.log(error)
            if (error.message) {
                proxy.alert.apiError({
                    title: 'Error en la operación',
                    error: error.message
                });
            } else {
                proxy.alert.apiError({
                    title: 'Error en la operación',
                    error: error.errors.message
                });
            }
        })
}


function btnIndex() {

    router.visit(`/admin/usuarios`);

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
                                Asignar rol
                                <span class="badge bg-danger">
                                    {{ user.name }}
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
                                        <label for="email" class="form-label">Correo electrónico</label>
                                        <input :disabled="is_disabled" v-model="form.email" type="email"
                                            class="form-control" id="email" placeholder="Correo electrónico">
                                        <div class="input-errors" v-for="error of f$.email.$errors" :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input :disabled="is_disabled" v-model="form.password" type="password"
                                            class="form-control" id="password" placeholder="Contraseña">
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="rol" class="form-label">Rol</label>
                                        <Multiselect :disabled="is_disabled" v-model="form.role" track-by="name"
                                            label="name" placeholder="Selecciona un rol" :show-labels="false"
                                            deselectLabel=" " :block-keys="['Tab', 'Enter']" :options="rolesCat"
                                            :searchable="true" :allow-empty="true" :showNoOptions="false">
                                            <template v-slot:noResult>
                                                <span>Opción no encontrada</span>
                                            </template>
                                        </Multiselect>
                                        <div class="input-errors" v-for="error of f$.role.$errors" :key="error.$uid">
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