<template>
    <div class="mt-5">
        <!-- Footer Start -->
        <footer class="footer footer-important text-white">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center text-footer-important">
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

<style scoped>
.footer-important {
    background-color: #1a2942;
}

.text-footer-important{
    color: #70809a;
}
</style>