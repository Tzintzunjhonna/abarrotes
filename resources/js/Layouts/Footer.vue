<template>
    <div>
        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        {{ currentYear() }} © CONTRERAS CORP
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
</template>

<script setup>


// FUNCIONES --------------------------

function currentYear() {

    return new Date().getFullYear();
}

</script>