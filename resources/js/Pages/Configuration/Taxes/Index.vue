<script setup>

// IMPORTS --------------------------
import {  getCurrentInstance, onMounted, ref } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';

import Footer from '@/Layouts/Footer.vue';
import TablePagination from '@/Components/helps/TablePagination.vue';
import Search from './Search.vue';

// VARIABLES --------------------------
const app = getCurrentInstance()
const api = app.appContext.config.globalProperties.api
const alert = app.appContext.config.globalProperties.alert

const title = ref('Configuración de impuestos')
const prevPageName = ref('Dashboard')
const pageNowName = ref('Configuración de impuestos')
const prevPageUrl = ref('')


// TABLAS --------------------------
let endpoint = ref(`v1/app-customers/collection`)

const getList = ref([])
const tableHeaders = ['Logo', 'Nombre', 'Email', 'Nombre de contacto','Estatus', 'Fecha de registro', 'Opciones'];
const tableTitle = ref('Lista de impuestos')

const tbody = [
    'path_logo',
    'name',
    'email',
    'name_contact',
    'is_active',
    'created_at',
];


const actions = [
    {
        name: 'edit',
        class: 'btn btn-warning btn-sm',
        class_icon: 'mdi mdi-image-edit',
        tooltip: 'Editar'
    },
    {
        name: 'delete',
        class: 'btn btn-danger btn-sm m-1',
        class_icon: 'mdi mdi-delete',
        tooltip: 'Eliminar'
    },
    {
        name: 'change_status',
        class: 'btn btn-primary btn-sm',
        class_icon: 'mdi mdi-account-reactivate',
        tooltip: 'Estatus'
    },
]

let searchForm = ref()

let reloadPage = ref(false)


const config = {
    confirmButtonText: 'Aceptar',
    showCloseButton: false,
    showCancelButton: false
}

// MOUNTED  --------------------------
onMounted(() => {
});


// FUNCIONES --------------------------

function action(value) {
    console.log(value)
    let actions = {
        edit: function (item) {
            onEdit(item)
        },
        search: function (item) {
            onSearch(item)
        },
        add: function (item) {
            onAdd(item)
        },
        delete: function (item) {
            onDelete(item)
        },
        change_status: function (item) {
            onChangeStatus(item)
        },
    }

    if (actions.hasOwnProperty(value.action)) {
        actions[value.action](value.value)
    }
}

const reload = (value) => {
    reloadPage.value = value
}

function onEdit(data) {

    let ids = data.id.toString();
    ids = btoa(ids);
    router.visit(`/admin/configuracion/catalogo-impuestos/${ids}/editar`);
}

function onSearch(data) {
    searchForm.value = { ...data };
}

function onAdd(data) {
    router.visit(`/admin/configuracion/catalogo-impuestos/nuevo`);
}

function onDelete(data) {
    
    alert
        .deleteConfirmation({
            title: 'Eliminar registro',
            text: `Ingresar la palabra "Confirmar" para eliminar el registro ${data.name}`,
            options: {
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Eliminar',
                inputPlaceholder: 'Ingresar',
                showCancelButton: true,
                reverseButtons: true
            }
        })
        .then((result) => {
            if (result.value == 'Confirmar') {
                api
                    .delete(`v1/app-customers/${data.id}/destroy`)
                    .then((response) => {
                        alert.apiSuccess({ title: response.message, description: '' }, config).then((result) => {
                            if (result.isConfirmed) {
                                reloadPage.value = true
                            }
                        })
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
        })
}

function onChangeStatus(data) {
    
    alert
        .deleteConfirmation({
            title: 'Cambiar estatus de registro',
            text: `Ingresar la palabra "Confirmar" para cambiar de estado el registro ${data.name}`,
            options: {
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Cambiar estatus',
                inputPlaceholder: 'Ingresar',
                showCancelButton: true,
                reverseButtons: true
            }
        })
        .then((result) => {
            if (result.value == 'Confirmar') {
                api
                    .post(`v1/app-customers/${data.id}/change-status`)
                    .then((response) => {
                        alert.apiSuccess({ title: response.message, description: '' }, config).then((result) => {
                            if (result.isConfirmed) {
                                reloadPage.value = true
                            }
                        })
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
            <div class="row">
                <Search :title="'Buscar'" :method="'search'" @btnAction="action" />

                <table-pagination :headers="tableHeaders" :tbody="tbody" :options="true" :actions="actions"
                    @btnAction="action" :endpoint="endpoint" :title="tableTitle" :searchPost="searchForm"
                    :labelBtnNew="'Nuevo impuesto'" :showBtnNew="true" :reload="reloadPage" @reload="reload" />
            </div>
        </div>
    </div>

    <Footer />

</template>
