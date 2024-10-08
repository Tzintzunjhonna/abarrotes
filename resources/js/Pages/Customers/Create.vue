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

const title = ref('Crear proveedor')
const prevPageName = ref('Proveedores')
const pageNowName = ref('Crear proveedor')
const prevPageUrl = ref('admin/proveedores')

const BaseUrl = window.location.origin

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
};

const f$ = useVuelidate(validateRulesForm, form);


// FUNCIONES --------------------------


async function onSubmit() {
    console.log('onSubmit')
    const isFormCorrect = await f$.value.$validate();
    console.log(isFormCorrect)
    console.log(f$.value)
    if (!isFormCorrect) return;
    
    const formData = new FormData();

    const keys = Object.keys(form.value);
    for (let i = 0; i < keys.length; i++) {
        formData.append(keys[i], form.value[keys[i]]);
    }

    api
        .post(`v1/app-providers/store`, form.value)
        .then((response) => {
            alert.apiSuccess({ title: response.message, description: ''}, config).then((result) => {
                if (result.isConfirmed) {
                    router.visit(`/admin/proveedores`);
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

function uploadFile(event, name) {
    const file = event.target.files[0];
    form.value.photo_input = file;
    form.value.photo = URL.createObjectURL(event.target.files[0]);

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
                                <h4 class="header-title">Crear proveedor</h4>

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
                                            <input v-model="form.business_name" type="text" class="form-control" id="business_name"
                                                name="business_name" placeholder="Razón social">
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