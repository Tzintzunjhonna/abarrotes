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

// Modals
import AssingOrChangeCustomer from './modal/AssingOrChangeCustomer.vue';

// VARIABLES --------------------------
const { proxy } = getCurrentInstance();


const props = defineProps({
    cat_customer: {
        type: Array,
        required: true,
    },
});

const title = ref('Ventas')
const prevPageName = ref('Dashboard')
const pageNowName = ref('Lista de ventas')
const prevPageUrl = ref('')


// TABLAS --------------------------
let endpoint = ref(`v1/app-sales-management/collection/history_sales`)

const getList = ref([])
const tableHeaders = ['No. Ticket', 'Cliente', 'Total', 'Tipo de cobro', 'Fecha de venta', 'Aplica para factura', 'Opciones'];
const tableTitle = ref('Lista de ventas')

const tbody = [
    'ticket_no',
    'customer',
    'total',
    'payment_type.name',
    'date_sale',
    'is_with_invoice',
];


const actions = [
    {
        name: 'modal_assing_customer',
        class: 'btn btn-warning btn-sm',
        class_icon: 'mdi mdi-account-convert-outline',
        tooltip: 'Asignar o cambiar cliente a venta'
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
const selected_sale = ref(null);
const customer_sale_data = ref(null);
const loadingScreenRef = ref(null);
const assinOrChangeCustomerRef = ref(null);

let modalAssingOrChangeCustomer


// MOUNTED  --------------------------
onMounted(() => {
    
    modalAssingOrChangeCustomer = new bootstrap.Modal(document.getElementById('modalAssingOrChangeCustomer'), {
        backdrop: 'static',
        keyboard: false
    })
});


// FUNCIONES --------------------------

function action(value) {
    console.log(value)
    let actions = {
        modal_assing_customer: function (item) {
            onModalAssingCustomer(item)
        },
        on_assing_customer: function (item) {
            onAssingCustomer(item)
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

function onModalAssingCustomer(data) {
    selected_sale.value         = data.id;
    customer_sale_data.value    = data.customer;

    assinOrChangeCustomerRef.value.getData(data.customer)
    modalAssingOrChangeCustomer.show()
}

function onAssingCustomer(data) {
    modalAssingOrChangeCustomer.hide()

    loadingScreenRef.value.startLoading();

    let id_customer = proxy.btoaSet(data.customer.id);
    let id_sale = proxy.btoaSet(selected_sale.value);

    proxy.api
        .post(`v1/app-sales-management/assing-customer-to-sale/${id_sale}/${id_customer}`, {
            headers: {
                'Content-Type': `multipart/form-data`,
            },
        })
        .then((response) => {
            loadingScreenRef.value.stopLoading();
            proxy.alert.apiSuccess({ title: response.message, description: '' }, config).then((result) => {
                if (result.isConfirmed) {
                    
                    reload(true);
                }
            });
        })
        .catch((error) => {
            loadingScreenRef.value.stopLoading();
            console.log(error)
            if (error.errors) {
                proxy.alert.apiError({
                    title: 'Error en la operación',
                    error: error.errors.message
                });
            } else {
                proxy.alert.apiError({
                    title: 'Error en la operación',
                    error: error.message
                });
            }
        })
}

function onSearch(data) {
    searchForm.value = { ...data };
}

function onAdd(data) {
    
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
    
    const data_copy = searchRef.value.submitFormExport()

    const baseUrl = window.location.origin;

    let queryParams = [];

    for (let key in data_copy) {
        if (data_copy.hasOwnProperty(key)) {
            queryParams.push(`${encodeURIComponent(key)}=${encodeURIComponent(data_copy[key])}`);
        }
    }
    const exportUrl = `${baseUrl}/api/v1/app-products/export-products?${queryParams.join('&')}`;
    
    window.open(exportUrl, "_blank");


    
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
                <Search ref="searchRef" :title="'Buscar'" :method="'search'" @btnAction="action" />

                <table-pagination :headers="tableHeaders" :tbody="tbody" :options="true" :actions="actions"
                    @btnAction="action" :endpoint="endpoint" :title="tableTitle" :searchPost="searchForm"
                    :showBtnNew="false" :labelBtnExport="'Exportar ventas'" :showBtnExport="true" :reload="reloadPage"
                    @reload="reload" />
            </div>

            <assing-or-change-customer ref="assinOrChangeCustomerRef" :method="'on_assing_customer'"
                :cat_customer="cat_customer" @btnAction="action" />
        </div>
    </div>

    <Footer />

</template>
