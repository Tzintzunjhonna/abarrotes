<template>
    <div class="modal fade" id="modalSearchProductView" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: calc(80% - 100px);">
            <form class="modal-content" @submit.prevent="submitForm">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Buscar producto
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 mb-4">
                        <label class="text-input">Descripción o código</label>
                        <input v-model="searchQuery" type="text" class="form-control allow-spin" id="barcode"
                            placeholder="Ingresa descripción del producto o el código">

                    </div>

                    <div class="responsive-table-plugin">
                        <div class="table-rep-plugin">
                            <div class="table-responsive" data-pattern="priority-columns">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Código producto</th>
                                            <th>Precio</th>
                                            <th>Descuento</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="filteredProducts?.length > 0">
                                        <tr v-for="(item, i )  in filteredProducts" :key="i">
                                            <td>
                                                <label>
                                                    <input type="checkbox" @change="onSelectProduct(item)"
                                                        :checked="selectedProduct === item.id" />
                                                </label>
                                            </td>
                                            <td>{{ item.name }}</td>
                                            <td>{{ item.description }}</td>
                                            <td>{{ item.barcode }}</td>
                                            <td>{{ item.price }}</td>
                                            <td>{{ item.discount }}</td>
                                            <td>{{ item.stock }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="7">
                                                <p class="text-center">No se encontrarón resultados</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button class="btn btn-primary" type="submit" title="Agregar producto a lista de venta">
                        <i class="mdi mdi-content-save"></i>
                        Agregar producto a la venta
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" title="Cerrar ventana emergente" @click="resetForm">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script setup>

// IMPORTS --------------------------
import { getCurrentInstance, onMounted, ref, computed, watch } from "vue";

// VARIABLES --------------------------
const { proxy } = getCurrentInstance();
const emit = defineEmits(['btnAction'])


const searchQuery = ref('');

const selectedProduct = ref(null);


// PROPS
const props = defineProps({
    products: {
        type: Array,
    },
    method: {
        type: String
    },
});

// MOUNTED  --------------------------
onMounted(() => {
});

// WATCH  --------------------------
// watch(selectedProduct, (newVal, oldVal) => {
//     console.log('Selected product changed from', oldVal, 'to', newVal);
// });

// FUNCIONES --------------------------

const filteredProducts = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();
    if (!query) return []; // Si no hay texto, muestra todos
    return props.products.filter(product =>
        product.name.toLowerCase().includes(query) ||
        product.description.toLowerCase().includes(query) ||
        product.barcode.toLowerCase().includes(query)
    );
});


function onSelectProduct(selectedItem) {

    if (selectedProduct.value === selectedItem.barcode){
        selectedProduct.value = null
    }else{
        selectedProduct.value = selectedItem.barcode
    }

}

function submitForm() {

    if (selectedProduct.value === null) {
        proxy.alert.apiError({
            title: 'Advertencia',
            error: 'No ha seleccionado un producto.'
        });
        return;
    }

    btnAction({ action: props.method, value: selectedProduct.value })

    resetForm()
}

function btnAction(value) {
    emit('btnAction', value)
}

function resetForm() {
    searchQuery.value = ''
    selectedProduct.value = null
}

</script>