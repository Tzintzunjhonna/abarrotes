<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light top-menu-important shadow-sm">
            <div class="logo-box ml-3">
                <a href="/admin/mi-empresa" class="logo-light">
                    <template v-if="proxy.company != null">
                        <span>
                            <img :src="BaseUrl + proxy.company.path_logo" alt="logo" class="logo-lg rounded" height="28">
                        </span>
                    </template>

                    <template v-else>
                        <span>
                            <img :src="BaseUrl + '/images/abarrotes/logo_1.png'" alt="logo" class="logo-lg rounded"
                                height="28">
                        </span>
                    </template>
                    <br>
                    <p>{{ proxy.company == null ? 'CONTRERAS CORP' : proxy.company.business_name }}</p>
                </a>

            </div>
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav ms-auto">
                        <div>
                            <li class="nav-item dropdown">
                                <a href="/dashboard" class="side-nav-link">
                                    <i class="ri-dashboard-3-line"></i>
                                    <span class="ml-1"> Dashboard </span>
                                </a>
                            </li>
                        </div>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <div>
                            <li class="nav-item dropdown">
                                <a id="navbarSistem" class="side-nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-cog-outline"></i>

                                    <span class="ml-1"> Configuración </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarSistem">
                                    <a class="side-nav-item dropdown-item" href="/admin/configuracion/catalogo-impuestos">Impuestos</a>
                                </div>
                            </li>
                        </div>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <div>
                            <li class="nav-item dropdown">
                                <a id="navbarSistem" class="side-nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ri-briefcase-line"></i>

                                    <span class="ml-1"> Sistema </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarSistem">
                                    <a class="side-nav-item dropdown-item" href="/admin/mi-empresa">Empresa</a>
                                    <a class="side-nav-item dropdown-item" href="/admin/roles">Roles</a>
                                    <a class="side-nav-item dropdown-item" href="/admin/permisos">Permisos</a>
                                    <a class="side-nav-item dropdown-item" href="/admin/usuarios">Usuarios</a>
                                </div>
                            </li>
                        </div>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <div>
                            <li class="nav-item dropdown">
                                <a href="/admin/proveedores" class="side-nav-link">
                                    <i class="mdi mdi-truck-delivery"></i>
                                    <span class="ml-1"> Proveedores </span>
                                </a>
                            </li>
                        </div>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <div>
                            <li class="nav-item dropdown">
                                <a href="/admin/clientes" class="side-nav-link">
                                    <i class="mdi mdi-account-group"></i>
                                    <span class="ml-1"> Clientes </span>
                                </a>
                            </li>
                        </div>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <div>
                            <li class="nav-item dropdown">
                                <a id="navbarProducts" class="side-nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ri-briefcase-line"></i>
                                    <span class="ml-1"> Productos </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarProducts">
                                    <a class="side-nav-item dropdown-item"
                                        href="/admin/categorias-de-producto">Categoría de productos</a>
                                    <a class="side-nav-item dropdown-item" href="/admin/productos">Productos</a>
                                </div>
                            </li>
                        </div>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <div>
                            <li class="nav-item dropdown">
                                <a id="navbarSales" class="side-nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-point-of-sale"></i>
                                    <span class="ml-1"> Gestión de ventas</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarSales">
                                    <a class="side-nav-item dropdown-item" href="/admin/gestion-de-ventas/caja">Caja</a>
                                </div>
                            </li>
                        </div>
                    </ul>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav ms-auto" v-if="proxy.user != null">
                        <div>
                            <li class="nav-item dropdown">
                                <a id="navbarPerfil" class="side-nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-account"></i>
                                    {{ proxy.user.name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarPerfil">
                                    <button class="side-nav-item dropdown-item">
                                        Profile
                                    </button>
                                    <button type="button" class="side-nav-item dropdown-item" method="post"
                                        @click="logout">
                                        Cerrar sesion
                                    </button>
                                </div>
                            </li>
                        </div>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>
<script setup>
import { computed, getCurrentInstance, onMounted, ref } from "vue";


const { proxy } = getCurrentInstance();
const BaseUrl = window.location.origin

const logout = () => {

    proxy.api
        .post(`logout-in`, {
            headers: {
                'Content-Type': `multipart/form-data`,
            },
        }
        )
        .then((response) => {
            localStorage.removeItem('token');
            window.location.href = window.location.origin + '/login';
        })
        .catch((error) => {
            console.log(error)
            if (error.message) {
                proxy.alert.apiError({
                    title: 'Error en la operación',
                    error: error.message
                });
            } else {
                proxy.alert.apiError({
                    title: 'Error en la operación',
                    error: error.errors.message
                });
            }
        })
};



</script>




<style scoped>
.top-menu-important{
    background-color: #1a2942;
}
.side-nav-link{
    color: #70809a;
}
.side-nav-link:hover,
.side-nav-link:focus,
.side-nav-link:active {
    color: #3bc0c3;
    text-decoration: none;
}
</style>