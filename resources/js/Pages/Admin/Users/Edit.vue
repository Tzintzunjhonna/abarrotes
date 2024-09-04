<script setup>

// IMPORTS --------------------------
import { Head, Link, router } from '@inertiajs/vue3';
import {defineProps, computed, getCurrentInstance, onMounted, ref} from "vue";
import { useVuelidate } from '@vuelidate/core';
import { helpers, required, email } from '@vuelidate/validators';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';
import LeftSideBar from '@/Layouts/LeftSideBar.vue';
import Footer from '@/Layouts/Footer.vue';

// VARIABLES --------------------------
const app = getCurrentInstance()
const api = app.appContext.config.globalProperties.api
const formatDate = app.appContext.config.globalProperties.formatDate

const title = ref('Editar usuario')
const prevPageName = ref('Usuarios')
const pageNowName = ref('Editar usuario')
const prevPageUrl = ref('admin/usuarios')

const props = defineProps({
  user: Object
});

const form = ref({
    name    : '',
    role    : '',
    password: '',
    email   : '',
})

const rolesSelect = ref(
    [
        { name: 'Admin', id: 1 },
        { name: 'Editor', id: 2 },
        { name: 'Subscriber', id: 3 },
        // Agrega más roles aquí
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
    // password: {
    //     required: helpers.withMessage(
    //         'El campo contraseña es requerido.',
    //         required,
    //     ),
    // },
    // rol: {
    //     required: helpers.withMessage(
    //         'El campo es requerido.',
    //         required,
    //     ),
    // }
};

const f$ = useVuelidate(validateRulesForm, form);


// FUNCIONES --------------------------

function getData() {
    form.value.name     = props.user.name;
    form.value.role     = props.user.role;
    form.value.email    = props.user.email;
}

async function onSubmit() {
    console.log('onSubmit')
    const isFormCorrect = await f$.value.$validate();
    console.log(isFormCorrect)
    console.log(f$.value)
    if (!isFormCorrect) return;
    window.alert('PAso la validacion')
}

</script>

<template>

    <MenuPage />
    <LeftSideBar />

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <Head :title="title" />
                <PageTitle :title="title" :prevPageName="prevPageName" :prevPageUrl="prevPageUrl"
                    :pageNowName="pageNowName" />
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Editar usuario {{ user.name }}</h4>

                                <form class="needs-validation" @submit.prevent="onSubmit">
                                    <div class="row">

                                        <div class="mb-2 col-md-6">
                                            <label for="name" class="form-label">Nombre</label>
                                            <input v-model="form.name" type="text" class="form-control" id="name"
                                                name="name" placeholder="Nombre">
                                            <div class="input-errors" v-for="error of f$.name.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="email" class="form-label">Correo electrónico</label>
                                            <input v-model="form.email" type="email" class="form-control" id="email"
                                                placeholder="Correo electrónico">
                                            <div class="input-errors" v-for="error of f$.email.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="password" class="form-label">Contraseña</label>
                                            <input v-model="form.password" type="password" class="form-control"
                                                id="password" placeholder="Contraseña">
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="rol" class="form-label">Rol</label>
                                            <!-- <select class="form-select" aria-label=".form-select-sm example">
                                                <option selected>Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select> -->
                                            <Multiselect v-model="form.role" track-by="name"
                                                label="name" placeholder="Selecciona un rol" :show-labels="false"
                                                deselectLabel=" " :block-keys="['Tab', 'Enter']" :options="rolesSelect"
                                                :searchable="true" :allow-empty="true" :showNoOptions="false">
                                            </Multiselect>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary" type="submit">
                                        <i class="mdi-content-save"></i>
                                        Guardar
                                    </button>
                                </form>

                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Footer />

</template>

<style scope>

</style>