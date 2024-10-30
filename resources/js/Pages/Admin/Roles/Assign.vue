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

const title = ref('Asignar rol')
const prevPageName = ref('Roles')
const pageNowName = ref('Asignar rol')
const prevPageUrl = ref('admin/roles')

const props = defineProps({
    role: {
        type: Object,
        required: true,
    },
    lista_permission: {
        type: Object,
        required: true,
    },
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
    form.value.name     = props.role.name;
    form.value.guard_name = props.role.guard_name;
    
}

async function onSubmit() {
    
    const siPermisos = [];
    const noPermisos = [];
    props.lista_permission.forEach((item) => {
        if (item.rol) {
            siPermisos.push(item.id);
        }
        else {
            noPermisos.push(item.id);
        }
    });

    const formAsignar = {
        siPermisos: siPermisos,
        noPermisos: noPermisos
    };
    

    proxy.api
        .put(`v1/app-roles/${props.role.id}/assing/permissions`, formAsignar)
        .then((response) => {
            proxy.alert.apiSuccess({ title: response.message, description: ''}, config).then((result) => {
                if (result.isConfirmed) {
                    //router.visit(`/admin/roles`);
                    window.location.href = window.location.origin + '/admin/roles';

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
                                Asignar rol
                                <span class="badge bg-danger">
                                    {{ role.name }}
                                </span>
                            </h4>

                            <form class="needs-validation" @submit.prevent="onSubmit">
                                <div class="responsive-table-plugin">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nombre</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="props.lista_permission?.length > 0">
                                                    <tr v-for="(item, i )  in props.lista_permission" :key="i">
                                                        <td>
                                                            {{ item.id }}
                                                            <label>
                                                                <input type="checkbox" v-model="item.rol" />
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                        <td>{{ item.name }}</td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr>
                                                        <td colspan="2">
                                                            <p>No se encontrarón resultados</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="mdi mdi-content-save"></i>
                                        Guardar
                                    </button>
                                </div>
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