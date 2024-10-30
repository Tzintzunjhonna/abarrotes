<script setup>

// IMPORTS --------------------------
import { Head, Link, router } from '@inertiajs/vue3';
import {watch, computed, getCurrentInstance, onMounted, ref} from "vue";
import { useVuelidate, } from '@vuelidate/core';
import { helpers, required, email, numeric } from '@vuelidate/validators';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';

import Footer from '@/Layouts/Footer.vue';
import Divider from '@/Components/helps/Divider.vue';

const { proxy } = getCurrentInstance();
 
// VARIABLES --------------------------

const title = ref('Crear impuesto')
const prevPageName = ref('Impuestos')
const pageNowName = ref('Crear impuesto')
const prevPageUrl = ref('admin/configuracion/catalogo-impuestos')

const BaseUrl = window.location.origin

const props = defineProps({
    cat_sat_impuesto: {
        type: Object,
        required: true,
    },
    cat_sat_tipo_factor: {
        type: Object,
        required: true,
    },
});

let cat_tasa_cuota = ref([])

let is_submit = ref(false)

const form = ref({
    tipo_impuesto_id: '',
    tipo_factor_id: '',
    is_retencion: false,
    is_traslado: false,
    tasa_cuota_porcentage: '',
    is_products_new: false,
})


const config = {
        confirmButtonText: 'Aceptar',
        showCloseButton: false,
        showCancelButton: false
    }

// MOUNTED  --------------------------
onMounted(() => {
    // getData()
});

// WATCH -----------------------------

watch(() => form.value.tasa_cuota_porcentage, (newValue, oldValue) => {
    if (newValue != null) {
        is_submit.value = true;
    }else{
        is_submit.value = false;
    }
});

// VALIDACION DE FORMULARIOS --------------------------

const validateRulesForm = {
    
    tipo_impuesto_id: {
        required: helpers.withMessage(
            'El campo tipo impuesto es requerido.',
            required,
        )
    },
    tipo_factor_id: {
        required: helpers.withMessage(
            'El campo tipo factor es requerido.',
            required,
        )
    },
    tasa_cuota_porcentage: {
        required: helpers.withMessage(
            'El campo Tasa o Cuota es requerido.',
            required,
        )
    },
};

const f$ = useVuelidate(validateRulesForm, form);


const validateRulesTasaOCuota = {
    
    tipo_impuesto_id: {
        required: helpers.withMessage(
            'El campo tipo impuesto es requerido.',
            required,
        )
    },
    tipo_factor_id: {
        required: helpers.withMessage(
            'El campo tipo factor es requerido.',
            required,
        )
    },
};

const b$ = useVuelidate(validateRulesTasaOCuota, form);


// FUNCIONES --------------------------


async function onSubmit() {

    const isFormCorrect = await f$.value.$validate();
    if (!isFormCorrect) return;

    let formData = proxy.setFormData(form.value);

    proxy.api
        .post(`v1/app-configuration/tax-settings/store`, formData,
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

function setForm(form){

    const formData = new FormData();

    for (const [key, value] of Object.entries(form)) {
        if (typeof value === 'object' && value !== null) {
            if(key == 'photo_input'){
                formData.append(key, value);

            }
            else if (key == 'tasa_cuota_porcentage'){
                formData.append(key, value.codigo);
            }
            else{
                formData.append(key, value.id);
            }
        }else {
            formData.append(key, value);
        }
    }

    return formData;
}
function uploadFile(event, name) {
    const file = event.target.files[0];
    form.value.photo_input = file;
    form.value.photo = URL.createObjectURL(event.target.files[0]);

}


async function btnSearchTasaCuota() {

    cat_tasa_cuota.value = [];
    const isFormCorrect = await b$.value.$validate();
    if (!isFormCorrect) return;

    if (form.value.is_retencion == false && form.value.is_traslado == false){
        proxy.alert.message({
            title: 'Advertencia',
            text: 'Debes de elegir una opción, si es retencion o traslado.'
        });
        return;
    }

    const formData = setForm(form.value);
    proxy.api
        .post(`v1/app-configuration/tax-settings/info-tasa-cuota`, formData)
        .then((response) => {
            const data = response.data
            cat_tasa_cuota.value = data;
            
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
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">
                                <a ref="#" type="button" title="Regresar" class="m-2" @click="btnIndex()">
                                    <i class="mdi mdi-page-previous-outline"></i>
                                </a>
                                Crear impuesto

                            </h4>

                            <form class="needs-validation" @submit.prevent="onSubmit">
                                <div class="row">
                                    <div class="mb-2 col-md-6">
                                        <label for="rol" class="form-label">Tipo de impuesto</label>
                                        <Multiselect v-model="form.tipo_impuesto_id" track-by="nombre" label="nombre"
                                            placeholder="Selecciona un tipo de impuesto" :show-labels="false"
                                            deselectLabel=" " :block-keys="['Tab', 'Enter']" :options="cat_sat_impuesto"
                                            :searchable="true" :allow-empty="true" :showNoOptions="false">
                                            <template v-slot:noResult>
                                                <span>Opción no encontrada</span>
                                            </template>
                                        </Multiselect>
                                        <div class="input-errors" v-for="error of f$.tipo_impuesto_id.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                        <div class="input-errors" v-for="error of b$.tipo_impuesto_id.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <label for="rol" class="form-label">Tipo factor</label>
                                        <Multiselect v-model="form.tipo_factor_id" track-by="nombre" label="nombre"
                                            placeholder="Selecciona un tipo factor" :show-labels="false"
                                            deselectLabel=" " :block-keys="['Tab', 'Enter']"
                                            :options="cat_sat_tipo_factor" :searchable="true" :allow-empty="true"
                                            :showNoOptions="false">
                                            <template v-slot:noResult>
                                                <span>Opción no encontrada</span>
                                            </template>
                                        </Multiselect>
                                        <div class="input-errors" v-for="error of f$.tipo_factor_id.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                        <div class="input-errors" v-for="error of b$.tipo_factor_id.$errors"
                                            :key="error.$uid">
                                            <div class="text-danger">{{ error.$message }}</div>
                                        </div>
                                    </div>
                                    <div class="mb-2 col-md-6">
                                        <div class="form-check form-switch">
                                            <input v-model="form.is_retencion" class="form-check-input" type="checkbox"
                                                id="flexSwitchCheckRetencion">
                                            <label class="form-check-label"
                                                for="flexSwitchCheckRetencion">Retención</label>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input v-model="form.is_traslado" class="form-check-input" type="checkbox"
                                                id="flexSwitchCheckTraslado">
                                            <label class="form-check-label"
                                                for="flexSwitchCheckTraslado">Traslado</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-2 col-md-6">
                                        <button class="btn btn-warning" type="button" @click="btnSearchTasaCuota">
                                            <i class="mdi mdi-cloud-search-outline mdi-18px"></i>
                                            Buscar rango de Tasa o Cuota
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-2 col-md-6">
                                    <label for="rol" class="form-label">Tasa o cuota %</label>
                                    <Multiselect v-model="form.tasa_cuota_porcentage" track-by="codigo" label="codigo"
                                        placeholder="Selecciona un tipo factor" :show-labels="false" deselectLabel=" "
                                        :block-keys="['Tab', 'Enter']" :options="cat_tasa_cuota" :searchable="true"
                                        :allow-empty="true" :showNoOptions="false">
                                        <template v-slot:noResult>
                                            <span>Opción no encontrada</span>
                                        </template>
                                    </Multiselect>
                                    <div class="input-errors" v-for="error of f$.tasa_cuota_porcentage.$errors"
                                        :key="error.$uid">
                                        <div class="text-danger">{{ error.$message }}</div>
                                    </div>
                                </div>
                                <div class="mb-2 col-md-6">
                                    <div class="form-check form-switch">
                                        <input v-model="form.is_products_new" class="form-check-input" type="checkbox"
                                            id="flexSwitchCheckRetencion">
                                        <label class="form-check-label"
                                            for="flexSwitchCheckRetencion">Incluir a productos nuevos automaticamente</label>
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

</style>