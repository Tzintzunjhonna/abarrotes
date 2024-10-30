<script setup>

// IMPORTS --------------------------
import { getCurrentInstance, onMounted, ref, reactive } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';
import TableProductsSales from '@/Components/helps/TableProductsSales.vue';
import Footer from '@/Layouts/Footer.vue';
import Search from './Search.vue';
import Totals from './Totals.vue';

// VARIABLES --------------------------
const { proxy } = getCurrentInstance();

const title = ref('Caja')
const prevPageName = ref('Dashboard')
const pageNowName = ref('Caja')
const prevPageUrl = ref('dashboard')


// TABLAS --------------------------
let endpoint = ref(`v1/app-providers/collection`)

const products = ref([])

// Totales
const amountImport = ref(0)
const amountDiscount = ref(0)
const amountTotal = ref(0)

const tableHeaders = ['Código de barras', 'Nombre', 'Precio Venta', 'Descuento', 'Cantidad', 'Importe', 'Inventario' , 'Opciones'];
const tableTitle = ref('Lista de productos de caja')

const tbody = [
    'barcode',
    'name',
    'price',
    'discount',
    'quantity',
    'import',
    'stock',
];


const actions = [
    {
        name: 'delete',
        class: 'btn btn-danger btn-sm m-1',
        class_icon: 'mdi mdi-delete',
        tooltip: 'Eliminar'
    },
]

let searchForm = ref([])

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
        delete: function (item, key) {
            onDelete(item, key)
        },
        change_status: function (item) {
            onChangeStatus(item)
        },
    }

    if (actions.hasOwnProperty(value.action)) {
        actions[value.action](value.value, value.key)
    }
}

const reload = (value) => {
    reloadPage.value = value
}

function onEdit(data) {

    let ids = data.id.toString();
    ids = btoa(ids);
    router.visit(`/admin/proveedores/${ids}/editar`);
}

function onSearch() {
    products.value.push(
        {
            barcode: '123456789012',
            name: 'Producto 1',
            price: 50.00,
            discount: 5.00,
            quantity: 2,
            import: 100.00,
            stock: 20
        }
    )

    onGetTotal();
}

function onGetTotal() {
    
    amountImport.value = 0;
    amountDiscount.value = 0;
    amountImport.value = 0;

    products.value.forEach((element) => {
        const importAmount = element.price * element.quantity;

        amountImport.value = (amountImport.value + parseFloat(importAmount));
        amountDiscount.value = (amountDiscount.value + parseFloat(element.discount));

    })

    amountTotal.value = parseFloat(amountImport.value - amountDiscount.value);

}

function onAdd(data) {
    router.visit(`/admin/proveedores/nuevo`);
}

function onDelete(data, key) {

    if (key !== -1) {
        products.value.splice(key, 1);
    }

    onGetTotal()
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
                    .post(`v1/app-providers/${data.id}/change-status`)
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
                <Totals
                :amountImport="amountImport"
                :amountDiscount="amountDiscount"
                :amountTotal="amountTotal"
                />

                <table-products-sales :headers="tableHeaders" :tbody="tbody" :options="true" :actions="actions"
                    @btnAction="action" :title="tableTitle" :collectionData="products" />
            </div>
        </div>
    </div>

    <Footer />

</template>
