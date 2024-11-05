<script setup>

// IMPORTS --------------------------
import { Head, Link, router } from '@inertiajs/vue3';
import {computed, getCurrentInstance, onMounted, ref} from "vue";
import { useVuelidate } from '@vuelidate/core';
import { helpers, required, email } from '@vuelidate/validators';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';

import Footer from '@/Layouts/Footer.vue';
import Divider from '@/Components/helps/Divider.vue';

// VARIABLES --------------------------
const { proxy } = getCurrentInstance();


const props = defineProps({
    cat_provider: {
        type: Object,
        required: true,
    },
    cat_category: {
        type: Object,
        required: true,
    },
    unit_of_measurement: {
        type: Object,
        required: true,
    },
    cat_tax_settings: {
        type: Object,
        required: true,
    },
});

const title = ref('Crear categoría')
const prevPageName = ref('Productos')
const pageNowName = ref('Crear categoría')
const prevPageUrl = ref('admin/productos')

const BaseUrl = window.location.origin

const form = ref({
    name: '',
    description: '',
    barcode: '',
    price: '',
    stock: '',
    category_id: '',
    provider_id: '',
    unit_of_measurement: '',
    discount: '',
    revenue : '',
    is_with_tax : false,
    is_with_discount : false,
    sale_price : '',
    wholesale_price : '',

    // PARA IVA
    tax_settings_id : '',

    // PARA FACTURACIÓN
    clave_producto_id: '',
    clave_unidad_id: '',
})

const list_taxes = ref([]);
const no_valid_price = ref(false);

const config = {
        confirmButtonText: 'Aceptar',
        showCloseButton: false,
        showCancelButton: false
    }

// MOUNTED  --------------------------
onMounted(() => {
    // getData()
});

// VALIDACION DE FORMULARIOS --------------------------

const validateRulesForm = {
    
    name: {
        required: helpers.withMessage(
            'El campo nombre es requerido.',
            required,
        )
    },
    description: {
        required: helpers.withMessage(
            'El campo descripción es requerido.',
            required,
        )
    },
    barcode: {
        required: helpers.withMessage(
            'El campo código de barra es requerido.',
            required,
        )
    },
    price: {
        required: helpers.withMessage(
            'El campo precio es requerido.',
            required,
        )
    },
    stock: {
        required: helpers.withMessage(
            'El campo stock es requerido.',
            required,
        )
    },
    category_id: {
        required: helpers.withMessage(
            'El campo categoría es requerido.',
            required,
        )
    },
    provider_id: {
        required: helpers.withMessage(
            'El campo proveedor es requerido.',
            required,
        )
    },
    unit_of_measurement: {
        required: helpers.withMessage(
            'El campo unidad de medida es requerido.',
            required,
        )
    },
    discount: {
        required: helpers.withMessage(
            'El campo descuento es requerido.',
            required,
        )
    },
    revenue: {
        required: helpers.withMessage(
            'El campo ganancia es requerido.',
            required,
        )
    },
    sale_price: {
        required: helpers.withMessage(
            'El campo precio venta es requerido.',
            required,
        )
    },
    wholesale_price: {
        required: helpers.withMessage(
            'El campo precio venta es requerido.',
            required,
        )
    },
    tax_settings_id: {
        required: helpers.withMessage(
            'El campo precio mayoreo es requerido.',
            required,
        )
    },
    clave_producto_id: {
        required: helpers.withMessage(
            'El campo clave de producto para facturación es requerido.',
            required,
        )
    },
    clave_unidad_id: {
        required: helpers.withMessage(
            'El campo clave de unidad para facturación es requerido.',
            required,
        )
    },
};

const f$ = useVuelidate(validateRulesForm, form);


// FUNCIONES --------------------------


async function onSubmit() {
    console.log('onSubmit')
    const isFormCorrect = await f$.value.$validate();
    console.log(isFormCorrect)
    console.log(f$.value)
    if (!isFormCorrect) return;
    
    let formData = proxy.setFormData(form.value);

    formData.append("userData", JSON.stringify(list_taxes.value));

    proxy.api
        .post(`v1/app-products/store`, formData)
        .then((response) => {
            proxy.alert.apiSuccess({ title: response.message, description: ''}, config).then((result) => {
                if (result.isConfirmed) {
                    router.visit(`/admin/productos`);
                }
            });
        })
        .catch((error) => {
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




function btnIndex() {

    router.visit(`/admin/productos`);

}


function on_check_tax(key) {

    const selectedTax = props.cat_tax_settings[key];
    let exists = false;

    // Recorrer lista de impuestos para saber si existe una configuracion igual a la seleccionada y no aplicar.

    if (selectedTax.select_tax == true){

        for (let index = 0; index < list_taxes.value.length; index++) {
            const element = list_taxes.value[index];

            if (index === key) {
                continue;
            }

            if (
                element.tipo_impuesto_id === selectedTax.tipo_impuesto_id &&
                element.tipo_factor_id === selectedTax.tipo_factor_id &&
                element.tasa_cuota_porcentage === selectedTax.tasa_cuota_porcentage &&
                element.is_retencion === selectedTax.is_retencion &&
                element.is_traslado === selectedTax.is_traslado
            ) {
                exists = true;
                break;
            }
        }
        
        if (!exists) {
            list_taxes.value.push(selectedTax)
        }
    }else{
        
        const index = list_taxes.value.findIndex(element => element.id === selectedTax.id);

        if (index !== -1) {
            list_taxes.value.splice(index, 1);
        } else {
            proxy.alert.apiError({
                title: '',
                error: 'No se encontró elemento para borrar, recarga la pagina..'
            });
            return;
        }
    }
    

    if (exists) {
        props.cat_tax_settings[key].select_tax = false;
        proxy.alert.apiError({
            title: '',
            error: 'Ya existe un impuesto seleccionado con la misma configuración.'
        });
        return;
    }
    
    handleInputPrice();
}


function handleInputPrice(){
    
    let str_precio

    if (form.value.price == '' || form.value.price == "") {

        str_precio = 0;

    } else {

        str_precio = String(form.value.price);
        str_precio = str_precio.slice(0, 10);
    }
    
    let total_precio = parseFloat(str_precio);
    
    let strDiscount
    if (form.value.discount == '' || form.value.discount == "") {

        strDiscount = 0;
    } else {

        strDiscount = String(form.value.discount);
        strDiscount = strDiscount.slice(0, 10);
    }
    let total_discount = parseFloat(strDiscount);


    switch (form.value.is_with_tax) {
        case true:
            
            if (form.value.is_with_discount) {
                total_precio = total_precio - total_discount;

                total_precio = onPriceWithTaxs(total_precio);

                form.value.sale_price = parseFloat(total_precio);
                handleInputSalePrice();
            }else{

                total_precio = onPriceWithTaxs(total_precio);

                form.value.sale_price = parseFloat(total_precio);

            }
            
            
            break;
    
        case false:
            form.value.sale_price = total_precio;

            if (form.value.is_with_discount){
                form.value.sale_price = total_precio - total_discount;
                handleInputSalePrice();
            }
            
            break;
    
        default:
            break;
    }

}

function handleInputSalePrice(){
        
    if (form.value.sale_price <= 0){
        no_valid_price.value = true;
        proxy.alert.apiError({
            title: '',
            error: 'El total del precio es menor o igual a 0, verifica los datos.'
        });
    }else{
        no_valid_price.value = false;
    }

}

function onPriceWithTaxs(price){

    let price_with_tax = price;

    list_taxes.value.forEach(element => {
        let tasa_cuota_porcentage = element.tasa_cuota_porcentage / 100;
        let tasa_cuota = tasa_cuota_porcentage * price;

        if (element.is_traslado == 1 && element.is_retencion == 0) {
            
            price_with_tax += tasa_cuota;
        }
        if (element.is_traslado == 0 && element.is_retencion == 1) {
            
            price_with_tax -= tasa_cuota;
        }
    });

    
    return price_with_tax;

    
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
                                Crear producto
                            </h4>

                            <form class="needs-validation" @submit.prevent="onSubmit">
                                <div class="row">

                                    <div class="mb-2 col-md-6">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input v-model="form.name" type="text" class="form-control" id="name"
                                            name="name" placeholder="Nombre">
                                        <div class="input-errors" v-for="error of f$.name.$errors" :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="description" class="form-label">Descripción</label>
                                        <input v-model="form.description" type="text" class="form-control"
                                            id="description" name="description" placeholder="Descripción">
                                        <div class="input-errors" v-for="error of f$.description.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="barcode" class="form-label">Código de barra</label>
                                        <input v-model="form.barcode" type="text" class="form-control" id="barcode"
                                            name="barcode" placeholder="Código de barra">
                                        <div class="input-errors" v-for="error of f$.barcode.$errors" :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="stock" class="form-label">Stock</label>
                                        <input v-model="form.stock" type="number" class="form-control" id="stock"
                                            placeholder="Stock" step="1"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        <div class="input-errors" v-for="error of f$.stock.$errors" :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="rol" class="form-label">Unidad de medida</label>
                                        <Multiselect v-model="form.unit_of_measurement" track-by="name" label="name"
                                            placeholder="Selecciona una unidad de medida" :show-labels="false"
                                            deselectLabel=" " :block-keys="['Tab', 'Enter']"
                                            :options="unit_of_measurement" :searchable="true" :allow-empty="true"
                                            :showNoOptions="false">
                                            <template v-slot:noResult>
                                                <span>Opción no encontrada</span>
                                            </template>
                                        </Multiselect>
                                        <div class="input-errors" v-for="error of f$.unit_of_measurement.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="rol" class="form-label">Proveedor</label>
                                        <Multiselect v-model="form.provider_id" track-by="name" label="name"
                                            placeholder="Selecciona un proveedor" :show-labels="false" deselectLabel=" "
                                            :block-keys="['Tab', 'Enter']" :options="cat_provider" :searchable="true"
                                            :allow-empty="true" :showNoOptions="false">
                                            <template v-slot:noResult>
                                                <span>Opción no encontrada</span>
                                            </template>
                                        </Multiselect>
                                        <div class="input-errors" v-for="error of f$.provider_id.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="rol" class="form-label">Categoría</label>
                                        <Multiselect v-model="form.category_id" track-by="name" label="name"
                                            placeholder="Selecciona un categoría" :show-labels="false" deselectLabel=" "
                                            :block-keys="['Tab', 'Enter']" :options="cat_category" :searchable="true"
                                            :allow-empty="true" :showNoOptions="false">
                                            <template v-slot:noResult>
                                                <span>Opción no encontrada</span>
                                            </template>
                                        </Multiselect>
                                        <div class="input-errors" v-for="error of f$.category_id.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                </div>

                                <Divider label="Precios e impuesto del producto" />

                                <div class="row">

                                    <div class="mb-2 col-md-6">
                                        <label for="price" class="form-label">Precio</label>
                                        <input v-model="form.price" type="number" class="form-control" id="price"
                                            placeholder="Precio" step="0.01"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10" @input="handleInputPrice">
                                        <div class="input-errors" v-for="error of f$.price.$errors" :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>

                                        <div class="form-check form-switch">
                                            <input v-model="form.is_with_tax" class="form-check-input" type="checkbox"
                                                id="flexSwitchCheckIs_with_tax">
                                            <label class="form-check-label" for="flexSwitchCheckIs_with_tax">¿El precio
                                                aplica con IVA?</label>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="revenue" class="form-label">Ganancia</label>
                                        <input v-model="form.revenue" type="number" class="form-control" id="revenue"
                                            placeholder="Ganancia" step="1"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        <div class="input-errors" v-for="error of f$.revenue.$errors" :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="wholesale_price" class="form-label">Precio mayoreo</label>
                                        <input v-model="form.wholesale_price" type="number" class="form-control"
                                            id="wholesale_price" placeholder="Precio mayoreo" step="1"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        <div class="input-errors" v-for="error of f$.wholesale_price.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="discount" class="form-label">Descuento</label>
                                        <input v-model="form.discount" type="number" class="form-control" id="discount"
                                            placeholder="Descuento" step="0.01"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10" @input="handleInputPrice">
                                        <div class="input-errors" v-for="error of f$.discount.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input v-model="form.is_with_discount" class="form-check-input" @change="handleInputPrice"
                                                type="checkbox" id="flexSwitchCheckis_with_discount">
                                            <label class="form-check-label" for="flexSwitchCheckis_with_discount">¿El
                                                descuento se aplicará al precio?</label>
                                        </div>
                                    </div>
                                    <div class="responsive-table-plugin" v-if="form.is_with_tax">
                                        <div class="table-rep-plugin">
                                            <div class="table-responsive" data-pattern="priority-columns">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Impuesto</th>
                                                            <th>Tipo factor</th>
                                                            <th>Porcentaje %</th>
                                                            <th>Traslado</th>
                                                            <th>Retención</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-if="props.cat_tax_settings?.length > 0">
                                                        <tr v-for="(item, key )  in props.cat_tax_settings" :key="key">
                                                            <td>
                                                                {{ item.id }}
                                                                <label>
                                                                    <input type="checkbox" v-model="item.select_tax"
                                                                        @change.prevent="on_check_tax(key)" />
                                                                    <span></span>
                                                                </label>
                                                            </td>
                                                            <td>{{ item.has_tipo_impuesto.nombre }}</td>
                                                            <td>{{ item.has_tipo_factor.nombre }}</td>
                                                            <td>{{ item.tasa_cuota_porcentage }}</td>
                                                            <td>{{ item.is_traslado }}</td>
                                                            <td>{{ item.is_retencion }}</td>
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

                                    <div class="mb-2 col-md-6" :class="form.is_with_tax == true ? 'mt-5' : ''">
                                        <label for="sale_price" class="form-label">Precio Venta</label>
                                        <input v-model="form.sale_price" disabled type="number" class="form-control" :class="no_valid_price == true ? 'border-danger': ''"
                                            id="sale_price" placeholder="Precio Venta" step="1"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10">
                                        <div class="input-errors" v-for="error of f$.sale_price.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                </div>

                                <Divider label="Datos de facturación" />

                                <div class="row">
                                    <div class="mb-2 col-md-6">
                                        <label for="rol" class="form-label">Clave de producto</label>
                                        <Multiselect v-model="form.clave_producto_id" track-by="nombre" label="nombre"
                                            placeholder="Selecciona un clave de producto" :show-labels="false"
                                            deselectLabel=" " :block-keys="['Tab', 'Enter']" :options="cat_category"
                                            :searchable="true" :allow-empty="true" :showNoOptions="false">
                                            <template v-slot:noResult>
                                                <span>Opción no encontrada</span>
                                            </template>
                                        </Multiselect>
                                        <div class="input-errors" v-for="error of f$.clave_producto_id.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="rol" class="form-label">Clave de unidad</label>
                                        <Multiselect v-model="form.clave_unidad_id" track-by="nombre" label="nombre"
                                            placeholder="Selecciona un clave de unidad" :show-labels="false"
                                            deselectLabel=" " :block-keys="['Tab', 'Enter']" :options="cat_category"
                                            :searchable="true" :allow-empty="true" :showNoOptions="false">
                                            <template v-slot:noResult>
                                                <span>Opción no encontrada</span>
                                            </template>
                                        </Multiselect>
                                        <div class="input-errors" v-for="error of f$.clave_unidad_id.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-primary" type="submit">
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
/*Eliminar botones de input number*/
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.btn-option {
    border: solid 1px red;
    margin-right: 15px;
    margin-bottom: 15px;
    width: 450px;
    height: 70px;
}

.inputfile {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}

.inputfile label {
    max-width: 80%;
    font-size: 1.25rem;
    font-weight: 700;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    display: inline-block;
    overflow: hidden;
    /* padding: 0.625rem 1.25rem; */
}

.inputfile-2+label {
    display: block;
    width: 100%;
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 5rem;
    font-weight: 400;
    line-height: 1.5;
    color: #0b21b9;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #d1d3e2;
    border-radius: 0.35rem 0 0 0.35rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
</style>