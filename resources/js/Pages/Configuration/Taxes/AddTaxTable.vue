<script setup>

// IMPORTS --------------------------
import { watch, computed, getCurrentInstance, onMounted, ref } from "vue";
import { useVuelidate, } from '@vuelidate/core';
import { helpers, required, email, numeric } from '@vuelidate/validators';

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
    type_tax: {
        type: String,
        required: true,
    },
});

const showErrors = ref(false)
const list_taxes = ref([])

let is_submit = ref(false)

const form = ref({
    tipo_impuesto_id: '',
    tipo_factor_id: '',
    tasa_cuota_porcentage: '',
})


const config = {
    confirmButtonText: 'Aceptar',
    showCloseButton: false,
    showCancelButton: false
}

// MOUNTED  --------------------------
onMounted(() => {
    form.value.tipo_impuesto_id = props.cat_sat_impuesto.find(item => item.nombre === props.type_tax);
});

// WATCH -----------------------------

watch(() => form.value.tasa_cuota_porcentage, (newValue, oldValue) => {
    if (newValue != null) {
        is_submit.value = true;
    } else {
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
            'El campo tasa o cueota % es requerido.',
            required,
        )
    },
};

const f$ = useVuelidate(validateRulesForm, form);

// DefineExpose --------------------------
defineExpose({
    validarCampos,
    addListTaxes
});

// FUNCIONES --------------------------


async function onSubmit() {

    showErrors.value = true;
    
    const isFormCorrect = await f$.value.$validate();
    if (!isFormCorrect) return;


    list_taxes.value.push({ ...form.value });

    form.value.tipo_factor_id = null;
    form.value.tasa_cuota_porcentage = null;

    f$.value.$reset();
    
}

function eliminarImpuesto(index) {
    list_taxes.value.splice(index, 1)
}

function validarCampos () {
    if (!list_taxes.value.length) {
        return { success: false, message: 'Debe agregar al menos un impuesto de ' + props.type_tax + '.' }
    }

    let validateImpuesto = true
    list_taxes.value.forEach(function (impuesto) {
        if (impuesto.tax === null || impuesto.factor === null || impuesto.cuota === null) {
            validateImpuesto = false
        }
        if (impuesto.tax === '' || impuesto.factor === '' || impuesto.cuota === '') {
            validateImpuesto = false
        }
    })

    if (!validateImpuesto) {
        return { success: false, message: 'Los campos del impuesto ' + props.type_tax + ', son incorrectos.' }
    }

    return { success: true, data: list_taxes.value }
}

function addListTaxes (list_items) {
    
    console.error('list_items:', list_items)
    
    list_taxes.value = list_items;

    console.error('list_taxes:', list_taxes.value)
}




</script>

<template>

    <div>
        <form class="needs-validation" @submit.prevent="onSubmit">
            <div class="row">
                <div class="mb-2 col-md-4">
                    <label for="rol" class="form-label">Tipo de impuesto</label>
                    <Multiselect v-model="form.tipo_impuesto_id" track-by="nombre" label="nombre" disabled
                        placeholder="Selecciona un tipo de impuesto" :show-labels="false" deselectLabel=" "
                        :block-keys="['Tab', 'Enter']" :options="cat_sat_impuesto" :searchable="true"
                        :allow-empty="true" :showNoOptions="false">
                        <template v-slot:noResult>
                            <span>Opción no encontrada</span>
                        </template>
                    </Multiselect>
                    <div class="input-errors" v-if="showErrors" v-for="error of f$.tipo_impuesto_id.$errors" :key="error.$uid">
                        <div class="text-danger">{{ error.$message }}</div>
                    </div>
                </div>
                <div class="mb-2 col-md-4">
                    <label for="rol" class="form-label">Tipo factor</label>
                    <Multiselect v-model="form.tipo_factor_id" track-by="nombre" label="nombre"
                        placeholder="Selecciona un tipo factor" :show-labels="false" deselectLabel=" "
                        :block-keys="['Tab', 'Enter']" :options="cat_sat_tipo_factor" :searchable="true"
                        :allow-empty="true" :showNoOptions="false">
                        <template v-slot:noResult>
                            <span>Opción no encontrada</span>
                        </template>
                    </Multiselect>
                    <div class="input-errors" v-if="showErrors" v-for="error of f$.tipo_factor_id.$errors" :key="error.$uid">
                        <div class="text-danger">{{ error.$message }}</div>
                    </div>
                </div>

                <div class="mb-2 col-md-4">
                    <label for="tasa_cuota_porcentage" class="form-label">Tasa o Cuota %</label>
                    <input v-model="form.tasa_cuota_porcentage" type="number" class="form-control"
                        id="tasa_cuota_porcentage" name="tasa_cuota_porcentage" placeholder="Ingresar tasa o couta %"
                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                        maxlength="12" step="0.000001">
                    <div class="input-errors" v-if="showErrors" v-for="error of f$.tasa_cuota_porcentage.$errors" :key="error.$uid">
                        <div class="text-danger">{{ error.$message }}</div>
                    </div>
                </div>
            </div>

            <button class="btn btn-warning" type="submit">
                Agregar impuesto
            </button>

            <div class="responsive-table-plugin">
                <div class="table-rep-plugin">
                    <div class="table-responsive" data-pattern="priority-columns">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Impuesto</th>
                                    <th>Tipo factor</th>
                                    <th>Tasa o Cuota %</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody v-if="list_taxes?.length > 0">
                                <tr v-for="(item, key) in list_taxes" :key="key">
                                    <td>{{ item.tipo_impuesto_id?.nombre }}</td>
                                    <td>{{ item.tipo_factor_id.nombre }}</td>
                                    <td>{{ item.tasa_cuota_porcentage }}</td>
                                    <td>
                                        <button id="btnGroupDrop" type="button" class="btn btn-danger btn-sm m-1"
                                            data-bs-toggle="dropdown" aria-expanded="false" title="Eliminar"
                                            @click="eliminarImpuesto(key)">

                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="4">
                                        <p class="text-center">No se han agregado datos</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<style scope></style>