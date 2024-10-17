<script setup>

// IMPORTS --------------------------
import { Head, Link, router } from '@inertiajs/vue3';
import {watch, computed, getCurrentInstance, onMounted, ref} from "vue";
import { useVuelidate } from '@vuelidate/core';
import { helpers, required, email, numeric } from '@vuelidate/validators';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';
import LeftSideBar from '@/Layouts/LeftSideBar.vue';
import Footer from '@/Layouts/Footer.vue';
import Divider from '@/Components/helps/Divider.vue';

// VARIABLES --------------------------
const app = getCurrentInstance()
const api = app.appContext.config.globalProperties.api
const alert = app.appContext.config.globalProperties.alert

const title = ref('Editar cliente')
const prevPageName = ref('Clientes')
const pageNowName = ref('Editar cliente')
const prevPageUrl = ref('admin/clientes')

const BaseUrl = window.location.origin

const props = defineProps({
    item_info: {
        type: Object,
        required: true,
    },
    cat_uso_cdfi: {
        type: Object,
        required: true,
    },
    cat_regimen_fiscal: {
        type: Object,
        required: true,
    },
    cat_metodo_pago: {
        type: Object,
        required: true,
    },
    cat_forma_pago: {
        type: Object,
        required: true,
    },
    cat_paises: {
        type: Object,
        required: true,
    },
    cat_tipo_exportacion: {
        type: Object,
        required: true,
    }
});

let cat_estado = ref([])
let cat_municipio = ref([])
let cat_localidad = ref([])

const form = ref({
    name: '',
    business_name: '',
    address: '',
    phone: '',
    email: '',
    name_contact: '',
    photo: '',
    photo_input: '',
    path_logo: '',

    // Facturación
    uso_cdfi_id: '',
    regimen_fiscal_id: '',
    metodo_pago_id: '',
    forma_pago_id: '',
    tipo_exportacion_id: '',
    zip_code: '',

    //Dirección
    pais_id: {
        id: 151,
        nombre: 'México',
    },
    codigo_postal_id: '',
    estado_id: '',
    municipio_id: '',
    localidad_id: '',
    street: '',
    number: '',
})

// MOUNTED  --------------------------
onMounted(() => {
    getData()
});


// WATCH -----------------------------

watch(() => form.value.codigo_postal_id, (newValue, oldValue) => {
    if (newValue?.length >= 5) {
        getZipCode(newValue);
    }
});

// VALIDACION DE FORMULARIOS --------------------------

const validateRulesForm = {

    name: {
        required: helpers.withMessage(
            'El campo nombre es requerido.',
            required,
        )
    },
    business_name: {
        required: helpers.withMessage(
            'El campo razón social es requerido.',
            required,
        )
    },
    address: {
        required: helpers.withMessage(
            'El campo dirección es requerido.',
            required,
        )
    },
    phone: {
        required: helpers.withMessage(
            'El campo teléfono es requerido.',
            required,
        )
    },
    name_contact: {
        required: helpers.withMessage(
            'El campo nombre de contacto es requerido.',
            required,
        )
    },
    email: {
        required: helpers.withMessage(
            'El campo email es requerido.',
            required,
        ),
        email: helpers.withMessage(
            "El campo debe ser un de correo electrónico valido.",
            email
        ),
    },
    uso_cdfi_id: {
        required: helpers.withMessage(
            'El campo uso de cdfi es requerido.',
            required,
        )
    },
    regimen_fiscal_id: {
        required: helpers.withMessage(
            'El campo regimen fiscal es requerido.',
            required,
        )
    },
    metodo_pago_id: {
        required: helpers.withMessage(
            'El campo metodo pago es requerido.',
            required,
        )
    },
    forma_pago_id: {
        required: helpers.withMessage(
            'El campo forma pago es requerido.',
            required,
        )
    },
    tipo_exportacion_id: {
        required: helpers.withMessage(
            'El campo tipo exportacion es requerido.',
            required,
        )
    },
    zip_code: {
        required: helpers.withMessage(
            'El campo codigo postal (Timbrado) para el timbrado es requerido.',
            required,
        )
    },
    pais_id: {
        required: helpers.withMessage(
            'El campo país para el timbrado es requerido.',
            required,
        )
    },
    codigo_postal_id: {
        required: helpers.withMessage(
            'El campo codigo postal para el timbrado es requerido.',
            required,
        ),
        numeric: helpers.withMessage(
            'El código postal debe contener solo números.',
            numeric
        ),
    },
    estado_id: {
        required: helpers.withMessage(
            'El campo estado para el timbrado es requerido.',
            required,
        )
    },
    municipio_id: {
        required: helpers.withMessage(
            'El campo municipio para el timbrado es requerido.',
            required,
        )
    },
    localidad_id: {
        required: helpers.withMessage(
            'El campo localidad para el timbrado es requerido.',
            required,
        )
    },
    street: {
        required: helpers.withMessage(
            'El campo calle para el timbrado es requerido.',
            required,
        )
    },
    number: {
        required: helpers.withMessage(
            'El campo número para el timbrado es requerido.',
            required,
        )
    },
};

const f$ = useVuelidate(validateRulesForm, form);


// FUNCIONES --------------------------

function getData() {

    form.value.name            = props.item_info.name;
    form.value.business_name   = props.item_info.business_name;
    form.value.address         = props.item_info.address;
    form.value.phone           = props.item_info.phone;
    form.value.email           = props.item_info.email;
    form.value.name_contact    = props.item_info.name_contact;
    form.value.photo           = props.item_info.path_logo != null ? BaseUrl + props.item_info.path_logo : null;

    const has_billing = props.item_info.has_billing;
    const has_address = props.item_info.has_address;
    
    // Facturación
    form.value.uso_cdfi_id         = has_billing.has_uso_cdfi;
    form.value.regimen_fiscal_id   = has_billing.has_regimen_fiscal;
    form.value.metodo_pago_id      = has_billing.has_metodo_pago;
    form.value.forma_pago_id       = has_billing.has_forma_pago;
    form.value.tipo_exportacion_id = has_billing.has_tipo_exportacion;
    form.value.zip_code            = has_billing.zip_code;
    
    //Dirección
    form.value.pais_id             = has_address.has_pais;
    form.value.codigo_postal_id = has_address.has_codigo_postal.codigo;
    form.value.estado_id           = has_address.has_estado;
    form.value.municipio_id        = has_address.has_municipio;
    form.value.localidad_id        = has_address.has_localidad;
    form.value.street              = has_address.street;
    form.value.number              = has_address.number;
}

async function onSubmit() {
    console.log('onSubmit')
    const isFormCorrect = await f$.value.$validate();
    console.log(isFormCorrect)
    console.log(f$.value)
    if (!isFormCorrect) return;

    let formData = setForm(form.value);

    api
        .post(`v1/app-customers/${props.item_info.id}/update`, formData, {
          headers: {
            'Content-Type': `multipart/form-data`,
          },})
        .then((response) => {
            alert.apiSuccess({ title: response.message, description: ''}, config).then((result) => {
                if (result.isConfirmed) {
                    router.visit(`/admin/clientes`);
                }
            });
        })
        .catch((error) => {
           console.log(error)
            if (error.errors) {
                alert.apiError({
                    title: 'Error en la operación',
                    error: error.errors.message
                });
            } else {
                alert.apiError({
                    title: 'Error en la operación',
                    error: error.message
                });
            }
        })
}

function setForm(form) {

    const formData = new FormData();

    for (const [key, value] of Object.entries(form)) {
        if (typeof value === 'object' && value !== null) {
            if (key == 'photo_input') {
                formData.append(key, value);

            } else {
                formData.append(key, value.id);
            }
        } else {
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

function getZipCode(code) {

    api
        .get(`v1/sat/addresses/zip_code/${code}`)
        .then((response) => {
            const data = response.data
            cat_estado.value = data.state;
            cat_municipio.value = data.cat_municipality;
            cat_localidad.value = data.cat_location;

        })
        .catch((error) => {
            console.log(error)
            if (error.errors) {
                alert.apiError({
                    title: 'Error en la operación',
                    error: error.errors.message
                });
            } else {
                alert.apiError({
                    title: 'Error en la operación',
                    error: error.message
                });
            }
        })
}
</script>

<template>

    <MenuPage />
    <LeftSideBar />

    <div class="content-page">
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
                                    Editar cliente: 
                                    <span class="badge bg-danger">
                                        {{ item_info.name }}
                                    </span> 
                                </h4>

                                <form class="needs-validation" @submit.prevent="onSubmit">
                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label for="name" class="form-label">Nombre</label>
                                            <input v-model="form.name" type="text" class="form-control" id="name"
                                                name="name" placeholder="Nombre">
                                            <div class="input-errors" v-for="error of f$.name.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="business_name" class="form-label">Razón social</label>
                                            <input v-model="form.business_name" type="text" class="form-control"
                                                id="business_name" name="business_name" placeholder="Razón social">
                                            <div class="input-errors" v-for="error of f$.business_name.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="email" class="form-label">Correo electrónico</label>
                                            <input v-model="form.email" type="email" class="form-control" id="email"
                                                placeholder="Correo electrónico">
                                            <div class="input-errors" v-for="error of f$.email.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="address" class="form-label">Dirección</label>
                                            <input v-model="form.address" type="text" class="form-control" id="address"
                                                placeholder="Dirección">
                                            <div class="input-errors" v-for="error of f$.address.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="phone" class="form-label">Teléfono</label>
                                            <input v-model="form.phone" type="number" class="form-control" id="phone"
                                                placeholder="Teléfono" step="1"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="10">
                                            <div class="input-errors" v-for="error of f$.phone.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="name_contact" class="form-label">Nombre de contacto</label>
                                            <input v-model="form.name_contact" type="text" class="form-control"
                                                id="name_contact" placeholder="Nombre de contacto">
                                            <div class="input-errors" v-for="error of f$.name_contact.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row col-sm-12 col-md-4 col-lg-4 d-flex justify-content-center">
                                        <div class="">
                                            <div class="ml-5">
                                                <label class="text-input">Logo de la Empresa</label>
                                                <input type="file" name="path_logo" id="path_logo"
                                                    @change="uploadFile($event, 'path_logo')"
                                                    class="inputfile inputfile-2" accept="image/png, image/jpeg" />
                                                <label for="path_logo" class="p-0">
                                                    <div style="width: 100%; height: 130px">
                                                        <img id="imgPreview"
                                                            :src="form.photo ? form.photo : BaseUrl + '/images/users/avatar-1.jpg'"
                                                            style="
                                      width: 100%;
                                      height: 100%;
                                      display: block;
                                      margin-left: auto;
                                      margin-right: auto;
                                  " alt="Imagen previa" />
                                                    </div>
                                                </label>
                                                <label style="color: #6d6d6d; font-size: 10px">Formato recomendado: JPG,
                                                    PNG. Máximo 1MB</label>
                                            </div>
                                        </div>
                                    </div>

                                    <Divider label="Datos de facturación"/>

                                    <div class="row">
                                        <div class="mb-2 col-md-6">
                                            <label for="rol" class="form-label">Uso de CFDI</label>
                                            <Multiselect v-model="form.uso_cdfi_id" track-by="nombre" label="nombre"
                                                placeholder="Selecciona un uso de CFDI" :show-labels="false"
                                                deselectLabel=" " :block-keys="['Tab', 'Enter']" :options="cat_uso_cdfi"
                                                :searchable="true" :allow-empty="true" :showNoOptions="false">
                                                <template v-slot:noResult>
                                                    <span>Opción no encontrada</span>
                                                </template>
                                            </Multiselect>
                                            <div class="input-errors" v-for="error of f$.uso_cdfi_id.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="regimen_fiscal_id" class="form-label">Regimen fiscal</label>
                                            <Multiselect v-model="form.regimen_fiscal_id" track-by="nombre"
                                                label="nombre" placeholder="Selecciona un regimen fiscal"
                                                :show-labels="false" deselectLabel=" " :block-keys="['Tab', 'Enter']"
                                                :options="cat_regimen_fiscal" :searchable="true" :allow-empty="true"
                                                :showNoOptions="false">
                                                <template v-slot:noResult>
                                                    <span>Opción no encontrada</span>
                                                </template>
                                            </Multiselect>
                                            <div class="input-errors" v-for="error of f$.regimen_fiscal_id.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="metodo_pago_id" class="form-label">Método de pago</label>
                                            <Multiselect v-model="form.metodo_pago_id" track-by="nombre" label="nombre"
                                                placeholder="Selecciona un método de pago" :show-labels="false"
                                                deselectLabel=" " :block-keys="['Tab', 'Enter']"
                                                :options="cat_metodo_pago" :searchable="true" :allow-empty="true"
                                                :showNoOptions="false">
                                                <template v-slot:noResult>
                                                    <span>Opción no encontrada</span>
                                                </template>
                                            </Multiselect>
                                            <div class="input-errors" v-for="error of f$.metodo_pago_id.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="forma_pago_id" class="form-label">Forma de pago</label>
                                            <Multiselect v-model="form.forma_pago_id" track-by="nombre" label="nombre"
                                                placeholder="Selecciona un forma de pago" :show-labels="false"
                                                deselectLabel=" " :block-keys="['Tab', 'Enter']"
                                                :options="cat_forma_pago" :searchable="true" :allow-empty="true"
                                                :showNoOptions="false">
                                                <template v-slot:noResult>
                                                    <span>Opción no encontrada</span>
                                                </template>
                                            </Multiselect>
                                            <div class="input-errors" v-for="error of f$.forma_pago_id.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="tipo_exportacion_id" class="form-label">Tipo de
                                                exportación</label>
                                            <Multiselect v-model="form.tipo_exportacion_id" track-by="nombre"
                                                label="nombre" placeholder="Selecciona un tipo de exportación"
                                                :show-labels="false" deselectLabel=" " :block-keys="['Tab', 'Enter']"
                                                :options="cat_tipo_exportacion" :searchable="true" :allow-empty="true"
                                                :showNoOptions="false">
                                                <template v-slot:noResult>
                                                    <span>Opción no encontrada</span>
                                                </template>
                                            </Multiselect>
                                            <div class="input-errors" v-for="error of f$.tipo_exportacion_id.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="zip_code" class="form-label">Código postal (Timbrado)</label>
                                            <input v-model="form.zip_code" type="number" class="form-control"
                                                id="zip_code" placeholder="Código postal (Timbrado)" step="1"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="5">
                                            <div class="input-errors" v-for="error of f$.zip_code.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <Divider label="Datos de dirección del cliente"/>

                                    <div class="row">

                                        <div class="mb-2 col-md-6">
                                            <label for="pais_id" class="form-label">País</label>
                                            <Multiselect v-model="form.pais_id" track-by="nombre" label="nombre"
                                                placeholder="Selecciona un país" :show-labels="false"
                                                deselectLabel=" " :block-keys="['Tab', 'Enter']" :options="cat_paises"
                                                :searchable="true" :allow-empty="true" :showNoOptions="false">
                                                <template v-slot:noResult>
                                                    <span>Opción no encontrada</span>
                                                </template>
                                            </Multiselect>
                                            <div class="input-errors" v-for="error of f$.pais_id.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2 col-md-6">
                                            <label for="codigo_postal_id" class="form-label">Código postal</label>
                                            <input v-model="form.codigo_postal_id" type="text" class="form-control"
                                                id="codigo_postal_id" placeholder="Código postal" step="1"
                                                maxlength="6">
                                            <div class="input-errors" v-for="error of f$.codigo_postal_id.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>

                                        <div class="mb-2 col-md-6">
                                            <label for="estado_id" class="form-label">Estado</label>
                                            <Multiselect v-model="form.estado_id" track-by="nombre" label="nombre"
                                                placeholder="Selecciona un estado" :show-labels="false"
                                                deselectLabel=" " :block-keys="['Tab', 'Enter']" :options="cat_estado"
                                                :searchable="true" :allow-empty="true" :showNoOptions="false">
                                                <template v-slot:noResult>
                                                    <span>Opción no encontrada</span>
                                                </template>
                                            </Multiselect>
                                            <div class="input-errors" v-for="error of f$.estado_id.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="municipio_id" class="form-label">Municipio</label>
                                            <Multiselect v-model="form.municipio_id" track-by="nombre"
                                                label="nombre" placeholder="Selecciona un municipio"
                                                :show-labels="false" deselectLabel=" " :block-keys="['Tab', 'Enter']"
                                                :options="cat_municipio" :searchable="true" :allow-empty="true"
                                                :showNoOptions="false">
                                                <template v-slot:noResult>
                                                    <span>Opción no encontrada</span>
                                                </template>
                                            </Multiselect>
                                            <div class="input-errors" v-for="error of f$.municipio_id.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="localidad_id" class="form-label">Localidad</label>
                                            <Multiselect v-model="form.localidad_id" track-by="nombre" label="nombre"
                                                placeholder="Selecciona un localidad" :show-labels="false"
                                                deselectLabel=" " :block-keys="['Tab', 'Enter']"
                                                :options="cat_localidad" :searchable="true" :allow-empty="true"
                                                :showNoOptions="false">
                                                <template v-slot:noResult>
                                                    <span>Opción no encontrada</span>
                                                </template>
                                            </Multiselect>
                                            <div class="input-errors" v-for="error of f$.localidad_id.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>

                                        <div class="mb-2 col-md-6">
                                            <label for="street" class="form-label">Calle</label>
                                            <input v-model="form.street" type="text" class="form-control" id="street"
                                                name="street" placeholder="Calle">
                                            <div class="input-errors" v-for="error of f$.street.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>

                                        <div class="mb-2 col-md-6">
                                            <label for="number" class="form-label">Número</label>
                                            <input v-model="form.number" type="number" class="form-control"
                                                id="number" placeholder="Número" step="1"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="9">
                                            <div class="input-errors" v-for="error of f$.number.$errors"
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