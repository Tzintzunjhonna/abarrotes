<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {computed, getCurrentInstance, onMounted, ref} from "vue";


const { proxy } = getCurrentInstance();

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: 'admin@corp.com',
    password: 'admin',
    remember: false,
});

const submit = () => {

    proxy.api
        .post(`login`, form, {
            headers: {
                'Content-Type': `multipart/form-data`,
            },
        }
        )
        .then((response) => {
            localStorage.setItem('token', response.token);

            form.post(route('login'), {
                onFinish: () => form.reset('password'),
            });
        })
        .catch((error) => {
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
};

</script>

<template>

    <Head title="Log in" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div v-if="status" class="mb-4 text-success">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div class="mb-3">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="form.email" type="email" class="form-control" required autofocus
                    autocomplete="username" />
                <InputError class="text-danger mt-2" :message="form.errors.email" />
            </div>

            <div class="mb-3">
                <InputLabel for="password" value="Password" />
                <TextInput id="password" v-model="form.password" type="password" class="form-control" required
                    autocomplete="current-password" />
                <InputError class="text-danger mt-2" :message="form.errors.password" />
            </div>

            <div class="form-check mb-3">
                <Checkbox v-model:checked="form.remember" name="remember" class="form-check-input" />
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>

            <div class="d-flex justify-content-between">
                <div>
                    <Link v-if="canResetPassword" :href="route('password.request')" class="link-secondary">
                    Forgot your password?
                    </Link>
                </div>

                <PrimaryButton class="btn btn-primary" :class="{ 'disabled': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
