<script setup>

// IMPORTS --------------------------
import { getCurrentInstance, onMounted, ref, reactive, watch } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';
import TableProductsSales from '@/Components/helps/TableProductsSales.vue';
import Footer from '@/Layouts/Footer.vue';
import Search from './Search.vue';
import Totals from './Totals.vue';
import ModalSearchProduct from './ModalSearchProduct.vue';
import ModalCollectingMoney from './ModalCollectingMoney.vue';

import LoadingScreen from "@/Components/helps/loading.vue";

// VARIABLES --------------------------
const { proxy } = getCurrentInstance();

const title = ref('Caja')
const prevPageName = ref('Dashboard')
const pageNowName = ref('Caja')
const prevPageUrl = ref('dashboard')

// PROPS

const props = defineProps({
    cat_products: {
        type: Array,
        required: true,
    },
    cat_customer: {
        type: Array,
        required: true,
    },
});

// TABLAS --------------------------
let endpoint = ref(`v1/app-providers/collection`)

const products = ref([])

// Totales
const amountImport = ref(0)
const amountTax = ref(0)
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
        tooltip: 'Eliminar producto'
    },
]

let searchForm = ref([])

let reloadPage = ref(false)

const loadingScreenRef = ref(null);

const config = {
    confirmButtonText: 'Aceptar',
    showCloseButton: false,
    showCancelButton: false
}
let modalSearchProductView
let modalCollectingMoneyView

// MOUNTED  --------------------------
onMounted(() => {

    modalSearchProductView = new bootstrap.Modal(document.getElementById('modalSearchProductView'), {
        backdrop: 'static',
        keyboard: false
    })
    modalCollectingMoneyView = new bootstrap.Modal(document.getElementById('modalCollectingMoneyView'), {
        backdrop: 'static',
        keyboard: false
    })

});

// WATCH  --------------------------
watch(
    () => reloadPage.value,
    (newValue, oldValue) => {
        if (reloadPage.value) {
            reload()
        }
    }
)

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
        cancel_sale: function (item) {
            onCancelSale()
        },
        change_quantity: function (item) {
            onChangeQuantity(item)
        },
        on_view_modal_search_product: function (item) {
            onViewModalSearchProduct(item)
        },
        on_search_product_modal: function (item) {
            onModalSearchProduct(item)
        },
        on_collecting_money: function (item) {
            onCollectingMoney(item)
        },
        on_collecting_money_submit: function (item) {
            onCollectingMoneySubmit(item)
        },
    }

    if (actions.hasOwnProperty(value.action)) {
        actions[value.action](value.value, value.key)
    }
}

const reload = (value) => {
    window.location.reload();
}

function onEdit(data) {

    let ids = data.id.toString();
    ids = btoa(ids);
    router.visit(`/admin/proveedores/${ids}/editar`);
}

function onChangeQuantity(data) {
    products.value = data
    onGetTotal();    
    
}

function onSearch(barcode) {

    // Buscar producto
    const productFind = props.cat_products.find(producto => producto.barcode === barcode);

    // Si no se encuentra el producto, mostrar alerta y salir
    if (!productFind) {
        proxy.alert.apiError({
            title: 'Advertencia',
            error: 'No se encuentra el producto con el código escaneado'
        });
        return;
    }

    // Verificar si el producto ya está en la lista
    let product = products.value.find(item => item.barcode === barcode);

    if (product) {

        // Si ya no tiene inventario no permite agregar producto 
        if (product.stock == 0) {
            proxy.alert.apiError({
                title: 'Advertencia',
                error: 'Ya no tiene este producto en inventario.'
            });
            return;
        }
        // Si el producto ya está en la lista, aumentar la cantidad y el importe
        product.quantity++;
        product.discount + product.discount;
        product.import = product.import;
        product.stock = product.stock - 1
    } else {
        // Si el producto no está en la lista, agregarlo
        products.value.push({
            barcode: productFind.barcode,
            name: productFind.name,
            price: productFind.price,
            discount: productFind.discount,
            quantity: 1,
            import: 1 * productFind.price,
            stock: productFind.stock - 1,
            is_with_tax: productFind.is_with_tax,
            has_taxes: productFind.has_taxes, 
            id: productFind.id,
        });
    }

    onGetTotal();
}

function onGetTotal() {

    // Valores para la vista de totales
    amountImport.value = 0;
    amountDiscount.value = 0;
    amountTax.value = 0;

    products.value.forEach((element, index) => {

        const discount = element.discount * element.quantity
        const price_of_discount = (element.price * element.quantity);

        const price = (element.price * element.quantity) - discount;

        const importAmount = price_of_discount;

        amountImport.value      = (amountImport.value + parseFloat(importAmount));
        amountDiscount.value    = (amountDiscount.value + parseFloat(discount));

        // En caso de ser con impuesto el producto se va a iterar para sacar los totales
        if (element.is_with_tax === 1){

            const has_taxes = element.has_taxes || [];

            let tax_import = 0;

            has_taxes.forEach((taxe, index) => {
                const tax_setting = taxe.tax_setting

                let tasa_cuota_porcentage = tax_setting.tasa_cuota_porcentage / 100;
                let tasa_cuota = tasa_cuota_porcentage * price;

                if (tax_setting.is_traslado == 1 && tax_setting.is_retencion == 0) {

                    tax_import += tasa_cuota;
                }
                if (tax_setting.is_traslado == 0 && tax_setting.is_retencion == 1) {

                    tax_import -= tasa_cuota;
                }
            });
            
            amountTax.value = amountTax.value + tax_import;
            
        }

    })


    //Total
    let total = amountImport.value - amountDiscount.value;
    total = total + amountTax.value;
    amountTotal.value = roundToTwoDecimals(total);

    //Subtotal (Importe)
    amountImport.value = roundToTwoDecimals(amountImport.value);

    // Descuento
    amountDiscount.value = roundToTwoDecimals(amountDiscount.value);

    // Impuesto
    amountTax.value = roundToTwoDecimals(amountTax.value);

}

const roundToTwoDecimals = (value) => {
    return parseFloat(parseFloat(value).toFixed(2));
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

function onCancelSale() {
    
    proxy.alert
        .deleteConfirmation({
            title: 'Cancelar compra',
            text: `Ingresar la palabra "Confirmar" para cancelar la compra`,
            options: {
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Aceptar cancelar compra',
                inputPlaceholder: 'Confirmar',
                showCancelButton: true,
                reverseButtons: true
            }
        })
        .then((result) => {
            if (result.value == 'Confirmar') {
                reloadPage.value = true
            }
        })
}

function onViewModalSearchProduct() {
    
    modalSearchProductView.show()
    
}

function onModalSearchProduct(barcode) {
    modalSearchProductView.hide()
    onSearch(barcode);

}

function onCollectingMoney(barcode) {

    if (products.value.length == 0) {
        proxy.alert.apiWarning({
            title: 'Advertencia',
            error: 'Para continuar con la venta, selecciona al menos un producto.'
        });
        return;
    }
    modalCollectingMoneyView.show()

}

function onCollectingMoneySubmit(data) {
    modalCollectingMoneyView.hide()
    console.log(data)
    console.log(products)

    let formData = proxy.setFormData(data);
    formData.append("products", JSON.stringify(products.value));

    formData.append("amountImport", amountImport.value);
    formData.append("amountTax", amountTax.value);
    formData.append("amountDiscount", amountDiscount.value);
    formData.append("amountTotal", amountTotal.value);
    
    onSubmit(formData);
}


async function onSubmit(formData) {

    loadingScreenRef.value.startLoading();

    proxy.api
        .post(`v1/app-sales-management/store`, formData, {
            headers: {
                'Content-Type': `multipart/form-data`,
            },
        })
        .then((response) => {
            loadingScreenRef.value.stopLoading();
            proxy.alert.apiSuccess({ title: response.message, description: '' }, config).then((result) => {
                if (result.isConfirmed) {
                    reload();
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
                <Search :title="'Buscar'" :method="'search'" @btnAction="action" />
                <Totals :amountImport="amountImport" :amountDiscount="amountDiscount" :amountTotal="amountTotal"
                    :amountTax="amountTax" :method="'on_collecting_money'" @btnAction="action" />

                <table-products-sales :headers="tableHeaders" :tbody="tbody" :options="true" :actions="actions"
                    @btnAction="action" :title="tableTitle" :collectionData="products"
                    :labelBtnCancelSale="'Cancelar proceso de venta'" :showBtnCancelSale="true" />
            </div>
            <modal-search-product :products="props.cat_products" :method="'on_search_product_modal'"
                @btnAction="action" />

            <modal-collecting-money @btnAction="action" :amountTotal="amountTotal" :cat_customer="props.cat_customer"
                :method="'on_collecting_money_submit'" />
        </div>
    </div>

    <Footer />

</template>
