<script setup>

// IMPORTS --------------------------
import {  getCurrentInstance, onMounted, ref } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';

import Footer from '@/Layouts/Footer.vue';
import TablePagination from '@/Components/helps/TablePagination.vue';
import Search from './Search.vue';

// Loading 
import LoadingScreen from "@/Components/helps/loading.vue";

// VARIABLES --------------------------
const { proxy } = getCurrentInstance();
const loadingScreenRef = ref(null);

const props = defineProps({
    cat_category: {
        type: Object,
        required: true,
    }
});

const title = ref('Productos')
const prevPageName = ref('Dashboard')
const pageNowName = ref('Lista de productos')
const prevPageUrl = ref('')


// TABLAS --------------------------
let endpoint = ref(`v1/app-products/collection`)

const getList = ref([])
const tableHeaders = ['Nombre', 'Código', 'Proveedor', 'Estatus', 'Fecha de registro', 'Opciones'];
const tableTitle = ref('Lista de productos')

const tbody = [
    'name',
    'barcode',
    'has_provider.name',
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
    router.visit(`/admin/productos/${ids}/editar`);
}

function onSearch(data) {
    searchForm.value = { ...data };
}

function onAdd(data) {
    router.visit(`/admin/productos/nuevo`);
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
                    .delete(`v1/app-products/${data.id}/destroy`)
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
                    .post(`v1/app-products/${data.id}/change-status`)
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

    loadingScreenRef.value.startLoading();
    const data_copy = searchRef.value.submitFormExport();
    const baseUrl = window.location.origin;
    const token = localStorage.getItem("token");
    let queryParams = [];

    // Añadir los parámetros de la consulta
    for (let key in data_copy) {
        if (data_copy.hasOwnProperty(key)) {
            queryParams.push(`${encodeURIComponent(key)}=${encodeURIComponent(data_copy[key])}`);
        }
    }

    const exportUrl = `${baseUrl}/api/v1/app-products/export-products?${queryParams.join('&')}`;

    // Verifica si el token está presente
    if (!token) {
        console.error("Token no encontrado");
        return;
    }

    // Usar fetch para enviar la solicitud con el token en los encabezados
    fetch(exportUrl, {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${token}`,  // Incluye el token en los encabezados
            'Accept': 'application/json',  // Especifica que esperamos JSON o el tipo de archivo necesario
        }
    })
        .then(response => response.blob())  // Esperamos que la respuesta sea un archivo binario (blob)
        .then(blob => {
            const date = new Date();
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');  // +1 porque los meses en JS empiezan en 0
            const day = String(date.getDate()).padStart(2, '0');
            const date_end = `${year}_${month}_${day}`

            // Crear un enlace temporal para descargar el archivo
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;

            link.download = `Productos_ ${date_end}.xlsx`;  // Nombre del archivo, puedes cambiarlo según sea necesario
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);  // Limpiar el objeto URL
            loadingScreenRef.value.stopLoading();
        })
        .catch(error => {
            console.error('Error al exportar:', error);  // Manejar cualquier error
            loadingScreenRef.value.stopLoading();
        });
}



</script>

<template>
    <LoadingScreen ref="loadingScreenRef" />
    <MenuPage />

    <div class="content">
        <div class="container-fluid">

            <Head :title="title" />
            <PageTitle :title="title" :prevPageName="prevPageName" :prevPageUrl="prevPageUrl"
                :pageNowName="pageNowName" />
            <div class="row">
                <Search ref="searchRef" :title="'Buscar'" :method="'search'" @btnAction="action"
                    :cat_category="props.cat_category" />

                <table-pagination :headers="tableHeaders" :tbody="tbody" :options="true" :actions="actions"
                    @btnAction="action" :endpoint="endpoint" :title="tableTitle" :searchPost="searchForm"
                    :labelBtnNew="'Nuevo producto'" :showBtnNew="true" :labelBtnExport="'Exportar productos'"
                    :showBtnExport="true" :reload="reloadPage" @reload="reload" />
            </div>
        </div>
    </div>

    <Footer />

</template>
