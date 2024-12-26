<script setup>

// IMPORTS --------------------------
import {  getCurrentInstance, onMounted, ref, reactive } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';

import Footer from '@/Layouts/Footer.vue';
import TablePagination from '@/Components/helps/TablePagination.vue';
import MessageText from '@/Components/helps/MessageText.vue';
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
    cat_status_sale: {
        type: Array,
        required: true,
    },
    cat_payment_type: {
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
const tableHeaders = ['No. Ticket', 'Cliente', 'Total', 'Tipo de cobro', 'Fecha de venta', 'Aplica para factura', 'Estatus de venta', 'Opciones'];
const tableTitle = ref('Lista de ventas')

const tbody = [
    'ticket_no',
    'customer',
    'total',
    'payment_type.name',
    'date_sale',
    'is_with_invoice',
    'status_sale.name',
];


const actions = [
    {
        name: 'modal_assing_customer',
        class: 'btn btn-warning btn-sm',
        class_icon: 'mdi mdi-account-convert-outline',
        tooltip: 'Asignar o cambiar cliente a venta'
    },
    // {
    //     name: 'delete',
    //     class: 'btn btn-danger btn-sm m-1',
    //     class_icon: 'mdi mdi-delete',
    //     tooltip: 'Eliminar'
    // },
    {
        name: 'change_status',
        class: 'btn btn-primary btn-sm m-1',
        class_icon: 'mdi mdi-account-reactivate',
        tooltip: 'Estatus de venta',
        dropdown: true,
        dropdownItem: props.cat_status_sale
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
const messageTextRef = ref(null);

let modalAssingOrChangeCustomer
let modalMessageText

// STATUS SALE
const PENDIENTE     = ref(1);
const COMPLETADO    = ref(2);
const CANCELADO     = ref(3);
const TIMBRADO      = ref(4);

// MOUNTED  --------------------------
onMounted(() => {
    
    modalAssingOrChangeCustomer = new bootstrap.Modal(document.getElementById('modalAssingOrChangeCustomer'), {
        backdrop: 'static',
        keyboard: false
    })
    modalMessageText = new bootstrap.Modal(document.getElementById('modalMessageText'), {
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
        change_status: function (item, id_status_sale) {
            onChangeStatus(item, id_status_sale)
        },
        export: function (item) {
            onExport(item)
        },
        on_view_reason: function (item) {
            onViewReason(item)
        },
    }

    if (actions.hasOwnProperty(value.action)) {
        actions[value.action](value.value, value.key)
    }
}

const reload = (value) => {
    reloadPage.value = value
}

function onModalAssingCustomer(sale) {
    selected_sale.value         = sale.id;
    customer_sale_data.value    = sale.customer;

    assinOrChangeCustomerRef.value.getData(sale)
    modalAssingOrChangeCustomer.show()
}

function onAssingCustomer(data) {
    modalAssingOrChangeCustomer.hide()

    loadingScreenRef.value.startLoading();

    let id_customer = proxy.btoaSet(data.customer.id);
    let id_sale = proxy.btoaSet(selected_sale.value);

    const formData = new FormData()
    formData.append('is_with_invoice', data.is_with_invoice);
    
    proxy.api
        .post(`v1/app-sales-management/assing-customer-to-sale/${id_sale}/${id_customer}`, formData, {
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

function onChangeStatus(data, id_status_sale) {
    
    if (id_status_sale == TIMBRADO.value){
        proxy.alert.apiWarning({
            title: 'Advertencia',
            error: 'No puedes cambiar a estatus TIMBRADO a la venta.'
        });
        return;
    }
    
    if (data.status_sale_id == id_status_sale){
        proxy.alert.apiWarning({
            title: 'Advertencia',
            error: 'No puedes cancelar la venta, ya esta en estatus CANCELADO'
        });
        return;
    }


    if (id_status_sale == CANCELADO.value) {
        onCancelSale(data, id_status_sale)
        return;
    }

    proxy.alert
        .deleteConfirmation({
            title: 'Cambiar estatus de registro',
            text: `Ingresar la palabra "Confirmar" para cambiar de estado el registro ${data.ticket_no}`,
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
                loadingScreenRef.value.startLoading();
                proxy.api
                    .post(`v1/app-sales-management/${data.id}/${id_status_sale}/change-status`)
                    .then((response) => {
                        loadingScreenRef.value.stopLoading();
                        proxy.alert.apiSuccess({ title: response.message, description: '' }, config).then((result) => {
                            if (result.isConfirmed) {
                                reloadPage.value = true
                            }
                        })
                    })
                    .catch((error) => {
                        loadingScreenRef.value.stopLoading();
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

function onCancelSale(data, id_status_sale) {

    proxy.alert
        .deleteConfirmation({
            title: 'Cambiar estatus de registro',
            text: `Ingresar la palabra "Confirmar" para cambiar de estado el registro ${data.ticket_no}`,
            options: {
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Cambiar estatus',
                inputPlaceholder: 'Escribe el motivo de cancelación',
                showCancelButton: true,
                reverseButtons: true
            }
        })
        .then((result) => {
            // Si la confirmación es "Confirmar"
            if (result.value === 'Confirmar') {
                proxy.alert.actionConfirmation({
                    title: "¿Por qué realizas esta acción?",
                    text: "Por favor, ingresa el motivo de esta acción.",
                    options: {
                        cancelButtonText: 'Cancelar',
                        confirmButtonText: 'Cambiar estatus',
                        inputPlaceholder: 'Confirmar',
                        showCancelButton: true,
                        reverseButtons: true
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        loadingScreenRef.value.startLoading();

                        proxy.api
                            .post(`v1/app-sales-management/${data.id}/${id_status_sale}/change-status?reason_for_cancellation=${result.value}`)
                            .then((response) => {
                                loadingScreenRef.value.stopLoading();
                                proxy.alert.apiSuccess({ title: response.message, description: '' }, config).then((result) => {
                                    if (result.isConfirmed) {
                                        reloadPage.value = true
                                    }
                                })
                            })
                            .catch((error) => {
                                loadingScreenRef.value.stopLoading();
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
                    } else {
                        proxy.alert.apiWarning({
                            title: 'Advertencia',
                            error: 'No pudo realizar el cambio de estatus.'
                        });
                        return;
                    }
                });
            }
        });
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

    const exportUrl = `${baseUrl}/api/v1/app-sales-management/export-sales?${queryParams.join('&')}`;

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
            
            link.download = `Ventas_ ${date_end}.xlsx`;  // Nombre del archivo, puedes cambiarlo según sea necesario
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


function onViewReason(sale) {
    let title = 'Motivo de la Cancelación'
    messageTextRef.value.getData(title, sale.comentarios)
    modalMessageText.show()
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
                    :cat_payment_type="cat_payment_type" :cat_status_sale="cat_status_sale"/>

                <table-pagination :headers="tableHeaders" :tbody="tbody" :options="true" :actions="actions"
                    @btnAction="action" :endpoint="endpoint" :title="tableTitle" :searchPost="searchForm"
                    :showBtnNew="false" :labelBtnExport="'Exportar ventas'" :showBtnExport="true" :reload="reloadPage"
                    @reload="reload" />
            </div>

            <assing-or-change-customer ref="assinOrChangeCustomerRef" :method="'on_assing_customer'"
                :cat_customer="cat_customer" @btnAction="action" />

            <message-text ref="messageTextRef" />
        </div>
    </div>

    <Footer />

</template>
