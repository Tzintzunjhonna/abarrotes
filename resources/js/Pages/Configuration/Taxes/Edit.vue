<script setup>

// IMPORTS --------------------------
import { Head, Link, router } from '@inertiajs/vue3';
import {watch, computed, getCurrentInstance, onMounted, ref} from "vue";
import { useVuelidate, } from '@vuelidate/core';
import { helpers, required, email, numeric } from '@vuelidate/validators';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';
import AddTaxTable from './AddTaxTable.vue';
import Footer from '@/Layouts/Footer.vue';
import FlashMessage from '@/Components/helps/FlashMessage.vue';
import Divider from '@/Components/helps/Divider.vue';

const { proxy } = getCurrentInstance();
 
// VARIABLES --------------------------

const title = ref('Editar impuesto')
const prevPageName = ref('Impuestos')
const pageNowName = ref('Editar impuesto')
const prevPageUrl = ref('admin/configuracion/catalogo-impuestos')

const is_disabled = ref(true);
const text_disabled = ref('Editar');
const class_button_disabled = ref('btn btn-primary');
const icon_disabled = ref('mdi mdi-content-save');


const props = defineProps({
    item_info: {
        type: Object,
        required: true,
    },
    cat_sat_impuesto: {
        type: Object,
        required: true,
    },
    cat_sat_tipo_factor: {
        type: Object,
        required: true,
    },
    cat_tasa_cuota_prop: {
        type: Object,
        required: true,
    },
});


const tax_iva_ref = ref(null)
const tax_isr_ref = ref(null)
const tax_ieps_ref = ref(null)

let is_submit = ref(false)

const formGeneral = ref({
    name: '',
    is_products_new: false,
})


const config = {
        confirmButtonText: 'Aceptar',
        showCloseButton: false,
        showCancelButton: false
    }

// MOUNTED  --------------------------
onMounted(() => {
    getData()
});

// // WATCH -----------------------------

// watch(() => formGeneral.value.tasa_cuota_porcentage, (newValue, oldValue) => {
//     if (newValue != null) {
//         is_submit.value = true;
//     }else{
//         is_submit.value = false;
//     }
// });

// VALIDACION DE FORMULARIOS --------------------------

const validateRulesForm = {
    
    name: {
        required: helpers.withMessage(
            'El campo nombre del impuesto es requerido.',
            required,
        )
    }
};

const g$ = useVuelidate(validateRulesForm, formGeneral);



// FUNCIONES --------------------------


function getData() {

    console.error('info:',props.item_info)
    formGeneral.value.name = props.item_info.name;
    
    let tax_iva = [];  
    // props.item_info.has_taxes.filter((tax) => {
    //     return tax.is_traslado == 1; // IVA
    // });
    let tax_isr = [];  
    // props.item_info.has_taxes.filter((tax) => {
    //     return tax.is_retencion == 1; // ISR
    // });
    let tax_ieps = [];  
    // props.item_info.has_taxes.filter((tax) => {
    //     return tax.is_ieps == 1; // IEPS
    // });

    props.item_info.has_taxes.forEach((tax) => {
        console.error('tax:', tax)
        if (tax.is_traslado == 1) {
            tax_iva.push({
                id: tax.id,
                tipo_impuesto_id: tax.has_tipo_impuesto,
                tipo_factor_id: tax.has_tipo_factor,
                tasa_cuota_porcentage: tax.tasa_cuota_porcentage,
            });
        } else if (tax.is_retencion == 1) {
            tax_isr.push({
                id: tax.id,
                tipo_impuesto_id: tax.has_tipo_impuesto,
                tipo_factor_id: tax.has_tipo_factor,
                tasa_cuota_porcentage: tax.tasa_cuota_porcentage,
            });
        } else if (tax.is_ieps == 1) {
            tax_ieps.push({
                id: tax.id,
                tipo_impuesto_id: tax.has_tipo_impuesto,
                tipo_factor_id: tax.has_tipo_factor,
                tasa_cuota_porcentage: tax.tasa_cuota_porcentage,
            });
        }
    });


    tax_iva_ref.value?.addListTaxes(tax_iva);
    tax_isr_ref.value?.addListTaxes(tax_isr);
    tax_ieps_ref.value?.addListTaxes(tax_ieps);

}

async function onSubmit() {

    // Validar campos de cada uno de los impuestos
    const formData = validateFormTaxes();

    console.error('formData', formData)

    if (formData === false) {
        return;
    }

    proxy.api
        .post(`v1/app-configuration/tax-settings/${props.item_info.id}/update`, formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }
        )
        .then((response) => {
            proxy.alert.apiSuccess({ title: response.message, description: '' }, config).then((result) => {
                if (result.isConfirmed) {
                    router.visit(`/admin/configuracion/catalogo-impuestos`);
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



function validateFormTaxes() {

    if (formGeneral.value.name == '') {
        g$.value.name.$touch();
        proxy.alert.apiError({
            title: 'Advertencia',
            error: 'Deberás agregar el nombre de la configuracion del impuesto.'
        });
        return false;
    }


    let impuestosIva = tax_iva_ref.value?.validarCampos();
    let impuestosIsr = tax_isr_ref.value?.validarCampos();
    let impuestosIeps = tax_ieps_ref.value?.validarCampos();

    console.error('impuestosIva', impuestosIva)
    console.error('impuestosIsr', impuestosIsr)
    console.error('impuestosIeps', impuestosIeps)
    if (
        impuestosIva.success == false &&
        impuestosIsr.success == false &&
        impuestosIeps.success == false
    ) {
        proxy.alert.apiError({
            title: 'Advertencia',
            error: 'Deberás agregar al menos un impuesto de IVA, ISR e IEPS.'
        });
        return false;
    }

    const formData = new FormData();

    if (impuestosIva.success == true) {
        formData.append('impuestos_iva', JSON.stringify(impuestosIva.data));
    }
    if (impuestosIsr.success == true) {
        formData.append('impuestos_isr', JSON.stringify(impuestosIsr.data));
    }
    if (impuestosIeps.success == true) {
        formData.append('impuestos_ieps', JSON.stringify(impuestosIeps.data));
    }

    formData.append('name', formGeneral.value.name);
    formData.append('is_products_new', formGeneral.value.is_products_new);

    return formData;


}

function btnIndex() {

    router.visit(`/${prevPageUrl.value}`);

}

</script>

<template>

    <MenuPage />
    <div class="content">
        <div class="container-fluid">

            <Head :title="title" />
            <PageTitle :title="title" :prevPageName="prevPageName" :prevPageUrl="prevPageUrl"
                :pageNowName="pageNowName" />

            <FlashMessage />
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">
                                <a ref="#" type="button" title="Regresar" class="m-2" @click="btnIndex()">
                                    <i class="mdi mdi-page-previous-outline"></i>
                                </a>
                                Editar impuesto
                            </h4>

                            <form class="needs-validation" @submit.prevent="onSubmit">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input v-model="formGeneral.name" type="text" class="form-control" id="name"
                                            name="name" placeholder="Nombre">
                                        <div class="input-errors" v-for="error of g$.name.$errors" :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                </div>
                                <Divider label="Impuestos de IVA" />
                                <AddTaxTable :cat_sat_impuesto="props.cat_sat_impuesto"
                                    :cat_sat_tipo_factor="props.cat_sat_tipo_factor" type_tax="IVA" ref="tax_iva_ref" />

                                <Divider label="Impuestos de retención" class="mt-2" />
                                <AddTaxTable :cat_sat_impuesto="props.cat_sat_impuesto"
                                    :cat_sat_tipo_factor="props.cat_sat_tipo_factor" type_tax="ISR" ref="tax_isr_ref" />

                                <Divider label="Impuestos de IEPS" class="mt-2" />
                                <AddTaxTable :cat_sat_impuesto="props.cat_sat_impuesto"
                                    :cat_sat_tipo_factor="props.cat_sat_tipo_factor" type_tax="IEPS"
                                    ref="tax_ieps_ref" />

                                <button class="btn btn-primary mt-1" type="submit">
                                    <i class="mdi mdi-content-save"></i>
                                    Guardar configuración
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

</style>