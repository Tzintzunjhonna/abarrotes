<template>
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center bg-light py-5">
        <div class="mb-4">
            <slot name="logo" />
        </div>

        <div class="w-100" style="max-width: 500px;">
            <div class="card shadow-sm">
                <div class="card-body">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
