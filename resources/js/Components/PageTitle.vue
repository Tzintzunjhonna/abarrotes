<template>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item" v-if="prevPageName"><a :href="BaseUrl + prevPageUrl"> {{ prevPageName }} </a></li>
                        <li class="breadcrumb-item active" v-if="pageNowName">{{ pageNowName }}</li>
                    </ol>
                </div>
                <h4 class="page-title  ml-5">{{ title }} </h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
</template>

<script setup>
import { computed, getCurrentInstance, onMounted, ref } from "vue";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    title: String,
    prevPageName: String,
    prevPageUrl: String,
    pageNowName: String,
    pageNowUrl: String,
});

const BaseUrl = ref(window.location.origin + '/');

</script>