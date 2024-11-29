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
const { proxy } = getCurrentInstance();

const title = ref('Clientes')
const prevPageName = ref('Dashboard')
const pageNowName = ref('Lista de clientes')
const prevPageUrl = ref('')


// TABLAS --------------------------
let endpoint = ref(`v1/app-customers/collection`)

const getList = ref([])
const tableHeaders = ['Logo', 'Nombre', 'Email', 'Nombre de contacto','Estatus', 'Fecha de registro', 'Opciones'];
const tableTitle = ref('Lista de clientes')

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

const searchRef = ref(null);

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
        export: function (item) {
            onExport(item)
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
    router.visit(`/admin/clientes/${ids}/editar`);
}

function onSearch(data) {
    searchForm.value = { ...data };
}

function onAdd(data) {
    router.visit(`/admin/clientes/nuevo`);
}

function onDelete(data) {
    
    proxy.alert
        .deleteConfirmation({
            title: 'Eliminar registro',
            text: `Ingresar la palabra "Confirmar" para eliminar el registro ${data.name}`,
            options: {
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Eliminar',
                inputPlaceholder: 'Confirmar',
                showCancelButton: true,
                reverseButtons: true
            }
        })
        .then((result) => {
            if (result.value == 'Confirmar') {
                proxy.api
                    .delete(`v1/app-customers/${data.id}/destroy`)
                    .then((response) => {
                        proxy.alert.apiSuccess({ title: response.message, description: '' }, config).then((result) => {
                            if (result.isConfirmed) {
                                reloadPage.value = true
                            }
                        })
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
        })
}

function onChangeStatus(data) {
    
    proxy.alert
        .deleteConfirmation({
            title: 'Cambiar estatus de registro',
            text: `Ingresar la palabra "Confirmar" para cambiar de estado el registro ${data.name}`,
            options: {
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Cambiar estatus',
                inputPlaceholder: 'Confirmar',
                showCancelButton: true,
                reverseButtons: true
            }
        })
        .then((result) => {
            if (result.value == 'Confirmar') {
                proxy.api
                    .post(`v1/app-customers/${data.id}/change-status`)
                    .then((response) => {
                        proxy.alert.apiSuccess({ title: response.message, description: '' }, config).then((result) => {
                            if (result.isConfirmed) {
                                reloadPage.value = true
                            }
                        })
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
        })
}

function onExport(data) {

    const data_copy = searchRef.value.submitFormExport()

    const baseUrl = window.location.origin;

    let queryParams = [];

    for (let key in data_copy) {
        if (data_copy.hasOwnProperty(key)) {
            queryParams.push(`${encodeURIComponent(key)}=${encodeURIComponent(data_copy[key])}`);
        }
    }
    const exportUrl = `${baseUrl}/api/v1/app-customers/export-customers?${queryParams.join('&')}`;

    window.open(exportUrl, "_blank");



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
                <Search ref="searchRef" :title="'Buscar'" :method="'search'" @btnAction="action" />

                <table-pagination :headers="tableHeaders" :tbody="tbody" :options="true" :actions="actions"
                    @btnAction="action" :endpoint="endpoint" :title="tableTitle" :searchPost="searchForm"
                    :labelBtnNew="'Nuevo cliente'" :showBtnNew="true" :reload="reloadPage" @reload="reload"
                    :labelBtnExport="'Exportar clientes'" :showBtnExport="true" />
            </div>
        </div>
    </div>

    <Footer />

</template>
