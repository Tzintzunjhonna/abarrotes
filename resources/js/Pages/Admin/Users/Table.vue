<script setup>

// IMPORTS --------------------------
import {computed, getCurrentInstance, onMounted, ref} from "vue";
import { Head, Link, router } from '@inertiajs/vue3';
import PageTitle from '@/Components/PageTitle.vue';

// VARIABLES --------------------------
const app = getCurrentInstance()
const api = app.appContext.config.globalProperties.api
const formatDate = app.appContext.config.globalProperties.formatDate

const title = ref('Usuarios')
const prevPage = ref('Dashboard')
const pageNow = ref('Lista de usuarios')

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
            console.log(data)
            getList.value = data
        })
        .catch((error) => {
            console.log(error)
        })
}

</script>

<template>

    <Head :title="title" />
    <PageTitle :title="title" :prevPage="prevPage" :pageNow="pageNow" />
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="responsive-table-plugin">
                        <div class="table-rep-plugin">
                            <div class="table-responsive" data-pattern="priority-columns">
                                <table id="tech-companies-1" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th data-priority="1">Nombre</th>
                                            <th data-priority="3">Email</th>
                                            <th data-priority="1">Rol</th>
                                            <th data-priority="3">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="getList.total == 0">
                                            <td colspan="10" style="text-align:center; color:black"> <b> Sin
                                                    registros</b></td>
                                        </tr>
                                        <tr v-for="(user, index) in getList.data" :key="index">
                                            <th>{{ user.name}}</th>
                                            <th>{{ user.email }}</th>
                                            <th>ROL</th>
                                            <th>{{ formatDate(user.created_at) }}</th>
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
</template>
