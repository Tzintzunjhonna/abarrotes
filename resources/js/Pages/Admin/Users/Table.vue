<script setup>

// IMPORTS --------------------------
import {computed, getCurrentInstance, onMounted, ref} from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';
import LeftSideBar from '@/Layouts/LeftSideBar.vue';
import Footer from '@/Layouts/Footer.vue';

// VARIABLES --------------------------
const app = getCurrentInstance()
const api = app.appContext.config.globalProperties.api
const formatDate = app.appContext.config.globalProperties.formatDate

const title = ref('Usuarios')
const prevPageName = ref('Dashboard')
const pageNowName = ref('Lista de usuarios')
const prevPageUrl = ref('')

const search = ref({
    email : null,
    role: null
})
const getList = ref([])

// MOUNTED  --------------------------
onMounted(() => {
    getData()
});


// FUNCIONES --------------------------

function getData(page = 1, pageSize = 10) {
    const url = `v1/users?page=${page}&pageSize=${pageSize}`;
    
    api.get(url)
        .then(({data}) => {
            getList.value = setData(data.data);
            console.log()
        })
        .catch((error) => {
            console.log(error)
        })
}

function setData(data) {

    let arrData = []

    if (!data.length)
        return arrData

    data.forEach(function (item) {
        arrData.push({
            id: item.id,
            name: item.name,
            email: item.email,
            role: !item.roles.length ? null : item.roles.name,
            created_at: item.created_at,
        })
    });

    return arrData
}

function onUpdate(data) {

    let ids = data.id.toString();
    ids = btoa(ids);
    location.href = window.location.origin  + '/admin/usuarios/' + ids + '/editar';
    //v-if="can('admin.usuarios.editar')"
}

</script>

<template>

    <MenuPage />
    <LeftSideBar />

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <Head :title="title" />
                <PageTitle :title="title" :prevPageName="prevPageName" :prevPageUrl="prevPageUrl" :pageNowName="pageNowName" />
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title">Busqueda de usuarios</h4>

                                <form class="needs-validation" @submit.prevent="getData">
                                    <div class="row">

                                        <div class="mb-2 col-md-6">
                                            <label for="name" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="name" placeholder="Nombre">
                                        </div>
                                        <div class="mb-2 col-md-6">
                                            <label for="email" class="form-label">Correo electrónico</label>
                                            <input type="email" class="form-control" id="email"
                                                placeholder="Correo electrónico">
                                        </div>
                                    </div>

                                    <button class="btn btn-primary" type="submit">Buscar</button>
                                </form>

                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="responsive-table-plugin">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table id="tech-companies-1" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre</th>
                                                        <th>Email</th>
                                                        <th>Rol</th>
                                                        <th>Fecha de registro</th>
                                                        <th>Opciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr v-if="getList.length <= 0">
                                                        <td colspan="10" style="text-align:center; color:black"> <b> Sin
                                                                registros</b></td>
                                                    </tr>
                                                    <tr v-else v-for="(user, index) in getList" :key="index">
                                                        <th>{{ user.name }}</th>
                                                        <th>{{ user.email }}</th>
                                                        <th>
                                                            <template v-if="user.role != null">
                                                                <span class="badge bg-primary">
                                                                    {{ user.role }}
                                                                </span>
                                                            </template>
                                                            <template v-else>
                                                                <span class="badge bg-danger">
                                                                    Sin rol asignado
                                                                </span>
                                                            </template>
                                                        </th>
                                                        <th>{{ formatDate(user.created_at) }}</th>
                                                        <th>
                                                            <button class="btn btn-danger btn-sm" type="button"
                                                                title="Eliminar">
                                                                <i class="mdi mdi-delete"></i>
                                                            </button>
                                                            <button class="btn btn-warning btn-sm m-1" type="button" @click="onUpdate(user)"
                                                                title="Editar">
                                                                <i class="mdi mdi-image-edit"></i>
                                                            </button>

                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Footer />

</template>
