<template>
    <LoadingScreen ref="loadingScreenRef" />
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ props.title }}</h4>
                <div class="col-12 d-flex justify-content-end">
                    <template v-if="props.showBtnNew">
                        <div class="col-auto">
                            <button @click="btnAction({ action: 'add', value: null })" type="button"
                                :title="props.labelBtnNew" class="btn btn-danger mb-2 mr-1">
                                <i :class="props.btnNewIcon" />
                                {{ props.labelBtnNew }}
                            </button>
                        </div>
                    </template>
                    <template v-if="props.showBtnExport">
                        <div class="col-auto">
                            <button @click="btnAction({ action: 'export', value: null })" type="button"
                                :title="props.labelBtnExport" class="btn btn-success mb-2 mr-1">
                                <i :class="props.btnExportIcon" />
                                {{ props.labelBtnExport }}
                            </button>
                        </div>
                    </template>
                </div>
                <div class="responsive-table-plugin">
                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th v-for="(header, index) in headers" :key="index">{{ header }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="collection.length <= 0">
                                        <td colspan="15" style="text-align:center; color:black">
                                            <b>Sin registros</b>
                                        </td>
                                    </tr>
                                    <tr v-else v-for="(item, index) in collection" :key="index">
                                        <td class="text-justify" v-for="attribute in tbody" :key="attribute">
                                            <template v-if="['roles'].includes(attribute)">
                                                <template v-if="item.roles?.length > 0">
                                                    <span class="badge bg-primary">{{ item.roles[0].name }}</span>
                                                </template>
                                                <template v-else>
                                                    <span class="badge bg-danger">Sin rol asignado</span>
                                                </template>
                                            </template>
                                            <template v-else-if="['is_active'].includes(attribute)">
                                                <template v-if="item.is_active == 1">
                                                    <span class="badge bg-primary">Activo</span>
                                                </template>
                                                <template v-else>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                </template>
                                            </template>
                                            <template v-else-if="['is_traslado'].includes(attribute)">
                                                <template v-if="item.is_traslado == 1">
                                                    <span class="badge bg-primary">Sí aplica</span>
                                                </template>
                                                <template v-else>
                                                    <span class="badge bg-danger">No aplica</span>
                                                </template>
                                            </template>
                                            <template v-else-if="['is_retencion'].includes(attribute)">
                                                <template v-if="item.is_retencion == 1">
                                                    <span class="badge bg-primary">Sí aplica</span>
                                                </template>
                                                <template v-else>
                                                    <span class="badge bg-danger">No aplica</span>
                                                </template>
                                            </template>
                                            <template v-else-if="['path_logo'].includes(attribute)">
                                                <img id="logo" class="avatar-md rounded-circle"
                                                    :src="item.path_logo ? BaseUrl + item.path_logo : BaseUrl + '/images/users/avatar-1.jpg'" />
                                            </template>
                                            <template v-else-if="['is_with_invoice'].includes(attribute)">
                                                <template v-if="item.is_with_invoice == 1">
                                                    <span class="badge bg-primary">Sí aplica</span>
                                                </template>
                                                <template v-else>
                                                    <span class="badge bg-danger">No aplica</span>
                                                </template>
                                            </template>
                                            <template v-else-if="['customer'].includes(attribute)">
                                                <template v-if="item.customer == null">
                                                    <span class="badge bg-danger">Publico en general</span>
                                                </template>
                                                <template v-else>
                                                    {{ item.customer.business_name}}
                                                </template>
                                            </template>
                                            <template v-else-if="['status_sale.name'].includes(attribute)">
                                                <template v-if="item.status_sale.name === 'Pendiente'">
                                                    <span class="badge bg-warning ml-3">{{ getValueProperty(item, attribute)
                                                        }}</span>
                                                </template>
                                                <template v-else-if="item.status_sale.name === 'Completado'">
                                                    <span class="badge bg-success ml-3">{{ getValueProperty(item, attribute)
                                                        }}</span>
                                                </template>
                                                <template v-else-if="item.status_sale.name === 'Cancelado'">
                                                    <button type="button" :class="class_button"
                                                        @click="btnAction({ action: 'on_view_reason', value: item })"
                                                        :title="'Mostrar motivo de cancelación'">
                                                        <span class="badge bg-danger">
                                                            {{ getValueProperty(item, attribute) }}

                                                            <i class="mdi mdi-eye"></i>
                                                        </span>
                                                    </button>
                                                </template>
                                                <template v-else-if="item.status_sale.name === 'Timbrado'">
                                                    <span class="badge bg-info ml-3">{{ getValueProperty(item, attribute)
                                                        }}</span>
                                                </template>
                                                <template v-else-if="item.status_sale.name === 'Factura pendiente'">
                                                    <span class="badge bg-primary ml-3">{{ getValueProperty(item, attribute)
                                                        }}</span>
                                                </template>
                                            </template>
                                            <template v-else>
                                                {{ getValueProperty(item, attribute) }}
                                            </template>
                                        </td>
                                        <td v-if="props.options">
                                            <template v-if="habilitedActions(index)">
                                                <template v-if="props.actions.length">
                                                    <template v-for="(action, i) in props.actions" :key="action.name">
                                                        <template v-if="habilitedRecicleAction(item, action)">
                                                            <template v-if="habilitedDropdownAction(item, action)">
                                                                <div class="btn-group" role="group">
                                                                    <button id="btnGroupDrop" type="button"
                                                                        class="btn btn-primary dropdown-toggle"
                                                                        :class="getClass(action, i)"
                                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                                        :title="action.tooltip">
                                                                        <template v-if="action.hasOwnProperty('title')">
                                                                            {{ action.title }}
                                                                        </template>
                                                                        <template v-else>
                                                                            <i :class="action.class_icon"></i>
                                                                        </template>
                                                                    </button>
                                                                    <ul class="dropdown-menu"
                                                                        aria-labelledby="btnGroupDrop">
                                                                        <li v-for="(dropdownItem, keydropdownItem) in action.dropdownItem"
                                                                            :key="keydropdownItem">
                                                                            <a class="dropdown-item" href="#"
                                                                                @click.prevent="btnAction({ action: action.name, value: item, key: dropdownItem.id })">
                                                                                {{ dropdownItem.name }}
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </template>
                                                            <template v-else>
                                                                <button type="button" :class="getClass(action, i)"
                                                                    @click="btnAction({ action: action.name, value: item })"
                                                                    :title="action.tooltip">
                                                                    <template v-if="action.hasOwnProperty('title')">
                                                                        {{ action.title }}
                                                                    </template>
                                                                    <template v-else>
                                                                        <i :class="action.class_icon"></i>
                                                                    </template>

                                                                </button>
                                                            </template>
                                                        </template>
                                                    </template>

                                                </template>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td :colspan="props.options ? props.headers.length + 1 : props.options"
                                            class="pt-5">
                                            <pagination :showDisabled="true" align="right" :data="response"
                                                :size="'small'" :limit="5" :keep-length="true"
                                                @pagination-change-page="getData">
                                                <template v-slot:prev-nav>
                                                    <span>&laquo; Anterior</span>
                                                </template>
                                                <template v-slot:next-nav>
                                                    <span>Siguiente &raquo;</span>
                                                </template>
                                            </pagination>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {getCurrentInstance, onMounted, watch, ref} from 'vue';
import LoadingScreen from "@/Components/helps/loading.vue";

const { proxy } = getCurrentInstance();

const props = defineProps({
    headers: {
        type: Array,
        required: true,
    },
    tbody: {
        type: Array,
        required: true,
    },
    endpoint: {
        required: true
    },
    title: {
        required: true
    },
    options: {
        required: true
    },
    actions: {
        required: true
    },
    searchPost: {
        type: Object
    },
    labelBtnNew: {
        type: String
    },
    showBtnNew: {
        type: Boolean
    },
    btnNewIcon: {
        type: String,
        default: 'mdi mdi-content-save'
    },
    labelBtnExport: {
        type: String
    },
    showBtnExport: {
        type: Boolean
    },
    btnExportIcon: {
        type: String,
        default: 'mdi mdi-export'
    },
    reload: {
        type: Boolean
    },
});

// VARIABLES  --------------------------
const loadingScreenRef = ref(null);

const response = ref({})
const search = ref('')
let collection = ref([])
const BaseUrl = window.location.origin

const emit = defineEmits(['btnAction', 'reload']);

const btnAction = (item) => {
    emit('btnAction', item);
};

let habilitedAction = ref(['modal_assing_customer'])

const class_button = ref('btn btn-sm');
// WATCH  --------------------------
watch(
    () => props.searchPost,
    (newValue, oldValue) => {
        if (props.searchPost) {
            getData()
        }
    }
)


watch(
    () => props.reload,
    () => {
        if (props.reload) {
            reload(false)
            getData()
        }
    }
)


// MOUNTED  --------------------------
onMounted(() => {
    getData()
});


// FUNTIONS  --------------------------

function reload(value) {
    emit('reload', value)
}



function getValueProperty(item, attribute) {

    let value = ''
    let arr = attribute.split('.')

    let attributes = [
        '',
    ]

    if (arr.length > 1) {

        let property = arr[arr.length - 1]
        let parent = arr[arr.length - 2]

        for (const key of Object.keys(item)) {

            if (checkObjectValue(item, key)) {

                if (parent == key) {

                    value = checkProperty(item[key], property)

                } else {

                    if (item[key][parent] != undefined) {
                        return getValueProperty(item[key][parent], property)
                    }
                }
            }
        }
    } else {
        value = item[attribute]
    }

    if (attribute === 'created_at') {
        value = setFormatYMD(value)
    }

    return value
}


function checkObjectValue(item, key) {
  let is_valid = false
  if (typeof item[key] == 'object') {
    if (item[key] != null) {
      is_valid = true
    }
  }
  return is_valid
}

function checkProperty(item, attribute) {
    let value = ''
    for (const key of Object.keys(item)) {
        if (typeof item[key] == 'object') {
            if (item[key] != null) {
                value = checkProperty(item[key], attribute)
            }
        } else {
            if (key == attribute) {
                value = item[key]
                return value
            }
        }
    }
}


function setFormatYMD(value) {
    let date = new Date(value)
    let day = date.getDate()
    let month = date.getMonth() + 1
    if (month.toString().length == 1) {
        month = '0' + month
    }
    let year = date.getFullYear()
    let hours = date.getHours()
    let minutes = date.getMinutes()
    let ampm = hours >= 12 ? 'PM' : 'AM'
    hours = hours % 12
    hours = hours ? hours : 12 // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes

    return day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + ' ' + ampm
}


const formatNumber2 = (number, decimal) => {
    try {
        if (number == undefined) {
            return ''
        }
        if (typeof (number) == 'string') return parseFloat(number.replaceAll(',', '')).formatNumber(decimal, '.', ',')
        if (typeof (number) == 'number') return parseFloat(number).formatNumber(decimal, '.', ',')
    } catch (e) {
        console.error(e);
        return number
    }
}


function setFormatDMY(value) {
    if (value == undefined) {
        return value
    }

    if (typeof value == 'object') {
        return value
    }

    let str = value.replaceAll('-', '/')
    let date = new Date(str)
    let day = date.getDate()
    let month = date.getMonth() + 1
    let year = date.getFullYear()

    if (month.toString().length == 1) {
        month = '0' + month
    }

    let format = day + '/' + month + '/' + year

    return format
}

function habilitedActions(index) {
  
  return true
}

function habilitedRecicleAction(item, action) {
    
    // if (habilitedAction.value.includes('modal_assing_customer')) {
    //     if (action.name == 'modal_assing_customer') {
    //         if (item.customer != null) {
    //             return false
    //         }
    //     }
    // }

    return true
}

function habilitedDropdownAction(item, action) {
    
    if (action.dropdown == true) {
        return true
    }

    return false
}



function getData(page = 1, pageSize = 10) {
    if (props.endpoint == '') {
        return
    }

    let parameters = {}

    if (props.searchPost == undefined) {
        parameters = {
            page: page,
            paginate: true,
            pageSize: pageSize,
            search: search.value,
        }
    } else {

        parameters = {
            page: page,
            paginate: true,
            pageSize: pageSize
        }
        // SEARCH
        let properties = [
            'name',
            'email',
            'name_provider',
            'description',
            'tipo_impuesto_id',
            'percentage',
            'barcode',
            'category_id',
            'ticket_no',
            'customer',
            'status_sale_id',
            'payment_type_id',
        ]

        properties.forEach((property) => addParameter(parameters, props.searchPost, property))

    }

    loadingScreenRef.value.startLoading();

    proxy.api
        .get(`${props.endpoint}`, parameters)
        .then(({ data }) => {
            loadingScreenRef.value.stopLoading();
            response.value = data
            collection.value = data.hasOwnProperty('data') ? data.data : data
        })
        .catch((error) => {
            loadingScreenRef.value.stopLoading();
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
}


const addParameter = (parameters, obj, property) => {

    if (obj[property]) {
        if (obj[property] != '') {
            parameters[property] = obj[property]
        }
    }

    return parameters
}


function getClass(value, i) {
    let clase = ''

    clase = value.class

    return clase
}

</script>

<style scoped>
.table {
  width: 100%;
  margin-bottom: 1rem;
  color: #212529;
}
</style>
