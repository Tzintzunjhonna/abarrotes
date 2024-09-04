<template>
    <div>
        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- Brand Logo Light -->
            <a href="{{ route('dashboard') }}" class="logo logo-light">
                <span class="logo-lg">
                    <img src="{{ URL('images/abarrotes/logo.png') }}" alt="logo">
                </span>
                <span class="logo-sm">
                    <img src="{{ URL('images/abarrotes/logo.png') }}" alt="small logo">
                </span>
            </a>

            <!-- Brand Logo Dark -->
            <a href="{{ route('dashboard') }}" class="logo logo-dark">
                <span class="logo-lg">
                    <img src="{{ URL('images/abarrotes/logo.png') }}" alt="dark logo">
                </span>
                <span class="logo-sm">
                    <img src="{{ URL('images/abarrotes/logo.pngg') }}" alt="small logo">
                </span>
            </a>

            <!-- Sidebar -left -->
            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <!--- Sidemenu -->
                <ul class="side-nav">

                    <li class="side-nav-title">ABARROTES</li>

                    <li class="side-nav-item">
                        <a href="{{ route('dashboard') }}" class="side-nav-link">
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
                                    <a href="/admin/usuarios">Usuarios</a>
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
const formatDate = app.appContext.config.globalProperties.formatDate

// MOUNTED  --------------------------
onMounted(() => {
});


// FUNCIONES --------------------------



</script>