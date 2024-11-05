<template>
  <div class="form-group" v-if="catalog">
    <template v-if="!isDisabled">
      <multiselect
        v-model="selected"
        :options="catalog.map((item) => item.id)"
        :close-on-select="true"
        :custom-label="customLabel"
        :clear-on-select="true"
        :placeholder="placeholder != undefined ? placeholder : 'Seleccione una opción'"
        :multiple="multiple"
        :allow-empty="true"
        :showNoOptions="false"
        :disabled="isDisabled"
        :searchable="searchable"
        :loading="loading"
        :showLabels="false"
        @search-change="searchOptions"
      >
        <template v-slot:noResult>
          <span>Opción no encontrada</span>
        </template>
      </multiselect>
    </template>
    <template v-else>
      <input v-model="item" type="text" disabled class="form-control" />
    </template>
  </div>
</template>

<script setup>
import { computed, getCurrentInstance, onMounted, ref, watch  } from "vue";

const { proxy } = getCurrentInstance();


const props = defineProps({
  modelValue: {
    type: Number,
  },
  placeholder: {
    type: String
  },
  endpoint: {
    required: true
  },
  errors: {
    type: Array,
    default: () => []
  },
  isDisabled: {
    type: Boolean,
    default: false
  },
  searchable: {
    type: Boolean,
    default: false
  },
  selectedItem: {
    type: Object,
    default: {},
    required: false
  },
  itemLabel: {
    type: String,
    default: 'description'
  },
  multiple: {
    type: Boolean,
    default: false
  },
  onlyBD: {
    type: Boolean,
    default: true
  },
  sortData: {
    type: Boolean,
    default: false
  },
  reloadSelect: {
    type: Boolean,
    default: false
  },
  reloadIdSelect: {
    type: Number,
  }
})

const loading = ref(false)

const emit = defineEmits(['update:modelValue', 'getItem', 'onOptionsLoaded'])

const catalog = ref([])
const customLabel = (opt) => {
  const c = catalog.value.find((x) => x.id == opt)
  return c ? `${c[props.itemLabel]}` : ''
}

const selected = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    let item = null
    if (value != null) {
      item = catalog.value.find((x) => x.id == value)
    }
    emit('update:modelValue', value)
    emit('getItem', item)
  }
})

const item = computed({
  get() {
    const c = catalog.value.find((x) => x.id == selected.value)
    return c ? `${c[props.itemLabel]}` : ''
  }
})

watch(
  () => props.endpoint,
  () => {
    if (!props.searchable) {
      getData()
    }
  }
)
watch(
  () => props.reloadSelect,
  () => {
    if (props.reloadIdSelect){
      getData(null, props.reloadIdSelect)
    }
  }
)

watch(
  () => props.selectedItem,
  () => {
    if (props.searchable) {
      getData(props.selectedItem.trade_name)
    }
  }
)

onMounted(() => {
  if (!props.searchable) {
    // getData()
  }
  else if (props.reloadIdSelect){
    getData(null, props.reloadIdSelect)
  }
  else {
    if (props.selectedItem) {
      getData(props.selectedItem.trade_name)
    }
  }
})

function setCollection($data) {
  let collection = [{ id: 0, description: 'Seleccione una opción' }]
  $data.forEach(function (item) {
    collection.push({
      id: item.id,
      description: item.codigo + ' - ' + item.nombre
    })
  })

  return collection
}

function getData(text = null, id = null) {
  if (props.endpoint == '') {
    return
  }

  let data = null

  if (text != null) {
    data = {
      search: text
    }
  }
  if (id != null) {
    data = {
      id: id
    }
  }

  proxy.api
    .get(`${props.endpoint}`, data)
    .then(({ data }) => {
      if (
        [
        ].includes(props.endpoint)
      ) {
        catalog.value = setCollection(data)
      } else {
        catalog.value = data
      }

      if (props.sortData) {
        catalog.value = data.sort((a, b) => {
          if (a.description.toLowerCase() < b.description.toLowerCase()) return -1
          if (a.description.toLowerCase() > b.description.toLowerCase()) return 1
          return 0
        })
      }

      emit('onOptionsLoaded', data)
    })
    .catch((error) => {
            console.log(error)
            if (error.errors) {
                proxy.alert.apiError({
                    title: 'Error en la operación',
                    error: error.errors.message
                });
            } else {
                proxy.alert.apiError({
                    title: 'Error en la operación',
                    error: error.message
                });
            }
        })
}

function searchOptions(query) {
  if (props.onlyBD) {
    if (query.length > 4) {
      getData(query)
    }
  }
}
</script>
