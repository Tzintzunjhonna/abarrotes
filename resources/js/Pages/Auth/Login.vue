<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: 'admin@bitfx.mx',
    password: 'admin',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
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
