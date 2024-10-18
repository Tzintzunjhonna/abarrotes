<template>
    <div>
        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- Brand Logo Light -->
            <a href="/admin/mi-empresa" class="logo logo-light">
                <template v-if="company != null">
                    <span class="logo-xxl">
                        <img :src="BaseUrl + company.path_logo" alt="logo" class="img-thumbnail">
                    </span>
                </template>

                <template v-else>
                    <span class="logo-xxl">
                        <img :src="BaseUrl + '/images/abarrotes/logo_1.png'" alt="logo" class="img-thumbnail">
                    </span>
                </template>
            </a>


            <!-- Brand Logo Dark -->
            <a href="/admin/mi-empresa" class="logo logo-dark">
                <span class="logo-lg">
                    <img :src="BaseUrl + '/images/abarrotes/logo_1.png'" alt="dark logo">
                </span>
                <span class="logo-sm">
                    <img :src="BaseUrl + '/images/abarrotes/logo_1.png'" alt="small logo">
                </span>
            </a>

            <!-- Sidebar -left -->
            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <!--- Sidemenu -->
                <ul class="side-nav">

                    <li class="side-nav-title">{{ company == null ? 'CONTRERAS CORP' : company.business_name }}</li>

                    <li class="side-nav-item">
                        <a href="/dashboard" class="side-nav-link">
                            <i class="ri-dashboard-3-line"></i>
                            <span class="badge bg-success float-end">9+</span>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    <li class="side-nav-title">Administrador</li>

                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarSystem" aria-expanded="false"
                            aria-controls="sidebarSystem" class="side-nav-link">
                            <i class="ri-briefcase-line"></i>
                            <span> Sistema </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarSystem">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="/admin/mi-empresa">Empresa</a>
                                </li>
                            </ul>
                        </div>
                        <div class="collapse" id="sidebarSystem">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="/admin/roles">Roles</a>
                                </li>
                            </ul>
                        </div>
                        <div class="collapse" id="sidebarSystem" v-if="can('Leer permisos')">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="/admin/permisos">Permisos</a>
                                </li>
                            </ul>
                        </div>
                        <div class="collapse" id="sidebarSystem">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="/admin/usuarios">Usuarios</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item">
                        <a href="/admin/proveedores" class="side-nav-link">
                            <i class="mdi mdi-truck-delivery"></i>
                            <span> Proveedores </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/admin/clientes" class="side-nav-link">
                            <i class="mdi mdi-account-group"></i>
                            <span> Clientes </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarProducts" aria-expanded="false"
                            aria-controls="sidebarProducts" class="side-nav-link">
                            <i class="ri-briefcase-line"></i>
                            <span> Productos </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarProducts">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="/admin/categorias-de-producto">Categoría de productos</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                </ul>
                <!--- End Sidemenu -->

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- ========== Left Sidebar End ========== -->
    </div>
</template>

<script setup>

// IMPORTS --------------------------
import { computed, getCurrentInstance, onMounted, ref } from "vue";
import { Head, Link, router } from '@inertiajs/vue3';

// VARIABLES --------------------------
const app = getCurrentInstance()
const api = app.appContext.config.globalProperties.api
const can = app.appContext.config.globalProperties.can;
const company = app.appContext.config.globalProperties.company;


const BaseUrl = window.location.origin

// MOUNTED  --------------------------
onMounted(() => {
});


// FUNCIONES --------------------------



</script>