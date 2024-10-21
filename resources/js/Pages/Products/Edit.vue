<script setup>

// IMPORTS --------------------------
import { Head, Link, router } from '@inertiajs/vue3';
import {computed, getCurrentInstance, onMounted, ref} from "vue";
import { useVuelidate } from '@vuelidate/core';
import { helpers, required, email } from '@vuelidate/validators';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';
import LeftSideBar from '@/Layouts/LeftSideBar.vue';
import Footer from '@/Layouts/Footer.vue';

// VARIABLES --------------------------
const app = getCurrentInstance()
const api = app.appContext.config.globalProperties.api
const alert = app.appContext.config.globalProperties.alert


const title = ref('Editar producto')
const prevPageName = ref('Productos')
const pageNowName = ref('Crear producto')
const prevPageUrl = ref('admin/productos')

const is_disabled = ref(true);
const text_disabled = ref('Editar');
const class_button_disabled = ref('btn btn-primary');
const icon_disabled = ref('mdi mdi-content-save');

const BaseUrl = window.location.origin

const props = defineProps({
    item_info: {
        type: Object,
        required: true,
    },
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
});

const form = ref({
    name: '',
    description: '',
    barcode: '',
    price: '',
    discount: '',
    stock: '',
    category_id: '',
    provider_id: '',
    unit_of_measurement: '',
})

// MOUNTED  --------------------------
onMounted(() => {
    getData()
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
    discount: {
        required: helpers.withMessage(
            'El campo descuento es requerido.',
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
};

const f$ = useVuelidate(validateRulesForm, form);


// FUNCIONES --------------------------

function getData() {
    form.value.name = props.item_info.name;
    form.value.description = props.item_info.description;
    form.value.barcode = props.item_info.barcode;
    form.value.price = props.item_info.price;
    form.value.discount = props.item_info.discount;
    form.value.stock = props.item_info.stock;
    form.value.category_id = props.item_info.has_categorie_products;
    form.value.provider_id = props.item_info.has_provider;
    form.value.unit_of_measurement = props.item_info.has_unit_of_measurement;
    
}

async function onSubmit() {

    const isFormCorrect = await f$.value.$validate();
    console.log(isFormCorrect)
    if (!isFormCorrect) return;

    let formData = setForm(form.value);

    api
        .post(`v1/app-products/${props.item_info.id}/update`, formData, {
          headers: {
            'Content-Type': `multipart/form-data`,
          },})
        .then((response) => {
            alert.apiSuccess({ title: response.message, description: ''}, config).then((result) => {
                if (result.isConfirmed) {
                    router.visit(`/admin/productos`);
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


function btnIndex() {

    router.visit(`/admin/productos`);

}


function btnEdit() {

    is_disabled.value = !is_disabled.value;
    if (is_disabled.value == false) {
        text_disabled.value = 'Cancelar edicion';
        class_button_disabled.value = 'btn btn-danger';
        icon_disabled.value = 'mdi mdi-book-cancel';
    } else {
        text_disabled.value = 'Editar';
        class_button_disabled.value = 'btn btn-primary';
        icon_disabled.value = 'mdi mdi-content-save';
    }

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

                                    <a ref="#" type="button" title="Regresar" class="m-2" @click="btnIndex()">
                                        <i class="mdi mdi-page-previous-outline"></i>
                                    </a>
                                    Editar producto:
                                    <span class="badge bg-danger">
                                        {{ item_info.name }}
                                    </span>
                                </h4>
                                <div class="col-12 d-flex justify-content-end">
                                    <button :class="class_button_disabled" type="button" @click="btnEdit()">
                                        <i :class="icon_disabled"></i>
                                        {{text_disabled}}
                                    </button>
                                </div>

                                <form class="needs-validation" @submit.prevent="onSubmit">
                                    <div class="row">

                                        <div class="mb-2 col-md-6">
                                            <label for="name" class="form-label">Nombre</label>
                                            <input :disabled="is_disabled" v-model="form.name" type="text"
                                                class="form-control" id="name" name="name" placeholder="Nombre">
                                            <div class="input-errors" v-for="error of f$.name.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>

                                        <div class="mb-2 col-md-6">
                                            <label for="description" class="form-label">Descripción</label>
                                            <input :disabled="is_disabled" v-model="form.description" type="text"
                                                class="form-control" id="description" name="description"
                                                placeholder="Descripción">
                                            <div class="input-errors" v-for="error of f$.description.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="barcode" class="form-label">Código de barra</label>
                                            <input :disabled="is_disabled" v-model="form.barcode" type="text"
                                                class="form-control" id="barcode" name="barcode"
                                                placeholder="Código de barra">
                                            <div class="input-errors" v-for="error of f$.barcode.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="price" class="form-label">Precio</label>
                                            <input :disabled="is_disabled" v-model="form.price" type="number"
                                                class="form-control" id="price" placeholder="Precio" step="1"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="10">
                                            <div class="input-errors" v-for="error of f$.price.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="discount" class="form-label">Descuento</label>
                                            <input v-model="form.discount" type="number" class="form-control"
                                                id="discount" placeholder="Descuento" step="1"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="10">
                                            <div class="input-errors" v-for="error of f$.discount.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="stock" class="form-label">Stock</label>
                                            <input :disabled="is_disabled" v-model="form.stock" type="number"
                                                class="form-control" id="stock" placeholder="Stock" step="1"
                                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                maxlength="10">
                                            <div class="input-errors" v-for="error of f$.stock.$errors"
                                                :key="error.$uid">
                                                <div class="text-danger">{{ error.$message }}</div>
                                            </div>
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="rol" class="form-label">Unidad de medida</label>
                                            <Multiselect :disabled="is_disabled" v-model="form.unit_of_measurement"
                                                track-by="name" label="name"
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
                                            <Multiselect :disabled="is_disabled" v-model="form.provider_id"
                                                track-by="name" label="name" placeholder="Selecciona un proveedor"
                                                :show-labels="false" deselectLabel=" " :block-keys="['Tab', 'Enter']"
                                                :options="cat_provider" :searchable="true" :allow-empty="true"
                                                :showNoOptions="false">
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
                                            <Multiselect :disabled="is_disabled" v-model="form.category_id"
                                                track-by="name" label="name" placeholder="Selecciona un categoría"
                                                :show-labels="false" deselectLabel=" " :block-keys="['Tab', 'Enter']"
                                                :options="cat_category" :searchable="true" :allow-empty="true"
                                                :showNoOptions="false">
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



                                    <button class="btn btn-primary" type="submit" v-if="!is_disabled">
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