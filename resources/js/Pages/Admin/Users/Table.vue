<script setup>

// IMPORTS --------------------------
import {computed, getCurrentInstance, onMounted, ref} from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import PageTitle from '@/Components/PageTitle.vue';
import MenuPage from '@/Layouts/Menu.vue';
import LeftSideBar from '@/Layouts/LeftSideBar.vue';
import Footer from '@/Layouts/Footer.vue';
import TablePagination from '@/Components/helps/TablePagination.vue';
import Search from './Search.vue';

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
    name: null
})

// TABLAS --------------------------
let endpoint = ref(`v1/users`)

const getList = ref([])
const tableHeaders = ['ID', 'Nombre', 'Email', 'Rol', 'Fecha de registro', 'Opciones'];
const tableTitle = ref('Lista de usuarios')

const tbody = [
    'id',
    'name',
    'email',
    'role',
    'created_at',
];


const actions = [
    {
        name: 'edit',
        class: 'btn btn-warning btn-sm',
        class_icon: 'mdi mdi-image-edit',
        tooltip: 'Editar'
    },
    {
        name: 'delete',
        class: 'btn btn-danger btn-sm m-1',
        class_icon: 'mdi mdi-delete',
        tooltip: 'Eliminar'
    },
]

let searchPost = ref()

// MOUNTED  --------------------------
onMounted(() => {
    // getData()
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
            role: !item.roles.length ? null : item.roles[0].name,
            created_at: item.created_at,
        })
    });

    return arrData
}


function action(value) {
    let actions = {
        onUpdate: function (item) {
            onUpdate(item)
        },
        search: function (item) {
            onSearch(item)
        },
    }

    if (actions.hasOwnProperty(value.action)) {
        actions[value.action](value.value)
    }
}

function onUpdate(data) {

    let ids = data.id.toString();
    ids = btoa(ids);
    location.href = window.location.origin  + '/admin/usuarios/' + ids + '/editar';
    //v-if="can('admin.usuarios.editar')"
}

function onSearch(data) {
    searchPost.value = data
    setTimeout(function () {
        searchPost.value = data
    }, 500)
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
                <div class="row">
                    <Search :title="'Buscar'" :method="'search'" @btnAction="action"/>

                    <table-pagination :headers="tableHeaders" :tbody="tbody" @update="onUpdate" :options="true"
                        :actions="actions" @btnAction="action" :endpoint="endpoint" :title="tableTitle"
                        :searchPost="searchPost" />
                </div>
            </div>
        </div>
    </div>

    <Footer />

</template>
