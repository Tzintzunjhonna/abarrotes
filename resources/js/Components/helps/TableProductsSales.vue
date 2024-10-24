<template>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ props.title }}</h4>
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
                                            {{ getValueProperty(item, attribute) }}
                                        </td>
                                        <td v-if="props.options">
                                            <template v-if="habilitedActions(index)">
                                                <template v-if="props.actions.length">
                                                    <template v-if="habilitedRecicleAction(item)">
                                                        <button type="button" :class="getClass(action, i)"
                                                            @click="btnAction({ action: action.name, value: item, key: index })"
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { getCurrentInstance, onMounted, watch, ref, reactive } from 'vue';

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
    collectionData: {
        type: Array,
        required: true,
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
});

// VARIABLES  --------------------------
const response = ref({})
const search = ref('')
const BaseUrl = window.location.origin

const emit = defineEmits(['btnAction']);

const btnAction = (item) => {
    emit('btnAction', item);
};

let collection = ref(props.collectionData);

// WATCH  --------------------------
watch(
    () => props.collectionData,
    (newValue, oldValue) => {
        if (props.collectionData) {
            // getData()
        }
    }
)


// MOUNTED  --------------------------
onMounted(() => {
    
});


// FUNTIONS  --------------------------


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

            console.log('key');
            console.log(key);
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
        value = item[attribute];
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

function habilitedRecicleAction(item) {
    return true
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


function getData() {

    if (props.collectionData == undefined) {
        console.info('no hay collectionData')
        return
    } else {

        console.log(props.collectionData)

    }
}


function typeofData(value) {

    console.log(value)
    return typeof value;
}



</script>

<style scoped>
.table {
  width: 100%;
  margin-bottom: 1rem;
  color: #212529;
}
</style>
