<template>
    <div class="row">
        <div class="col-12">
            <h4 class="p-0 m-0">{{label}}</h4>
            <hr class="hr-red mt-0 mb-3">
        </div>
    </div>
</template>

<script setup>
defineProps({
  label: {
    type: String,
  }
})

</script>
