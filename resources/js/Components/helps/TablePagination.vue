<template>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ props.title }}</h4>
                <template v-if="props.showBtnNew">
                    <div class="col-12 d-flex justify-content-end">
                        <div class="col-auto">
                            <button @click="btnAction({ action: 'add', value: null })" type="button"
                                class="btn btn-danger mb-2 mr-1">
                                <i :class="props.btnNewIcon" />
                                {{ props.labelBtnNew }}
                            </button>
                        </div>
                    </div>
                </template>
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
                                            <template v-else-if="['path_logo'].includes(attribute)">
                                                <img id="logo" class="avatar-md rounded-circle"
                                                    :src="item.path_logo ? BaseUrl + item.path_logo : BaseUrl + '/images/users/avatar-1.jpg'" />
                                            </template>
                                            <template v-else>
                                                {{ getValueProperty(item, attribute) }}
                                            </template>
                                        </td>
                                        <td v-if="props.options">
                                            <template v-if="habilitedActions(index)">
                                                <template v-if="props.actions.length">
                                                    <template v-if="habilitedRecicleAction(item)">
                                                        <button type="button" :class="getClass(action, i)"
                                                            @click="btnAction({ action: action.name, value: item })"
                                                            v-for="(action, i) in props.actions" :key="action.name"
                                                            :title="props.actions[i].tooltip">
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
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td :colspan="props.options ? props.headers.length + 1 : props.options"
                                            class="pt-5">
                                            <pagination :showDisabled="true" :limit="5" align="right" :data="response"
                                                @pagination-change-page="getData" />
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

const app = getCurrentInstance()
const api = app.appContext.config.globalProperties.api
const alert = app.appContext.config.globalProperties.alert

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
    reload: {
        type: Boolean
    },
});

// VARIABLES  --------------------------
const response = ref({})
const search = ref('')
let collection = ref([])
const BaseUrl = window.location.origin

const emit = defineEmits(['btnAction', 'reload']);

const btnAction = (item) => {
    emit('btnAction', item);
};


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

function habilitedRecicleAction(item) {
    return true
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
        let properties = [
            'name',
            'email',
        ]

        properties.forEach((property) => addParameter(parameters, props.searchPost, property))

    }

    api
        .get(`${props.endpoint}`, parameters)
        .then(({ data }) => {
            response.value = data
            collection.value = data.hasOwnProperty('data') ? data.data : data
        })
        .catch((error) => {
            console.log(error)
            if (error.message) {
                alert.apiError({
                    title: 'Error en la operación',
                    error: error.message
                });
            } else {
                alert.apiError({
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
