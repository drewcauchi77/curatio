<script setup lang="ts">
import { Link, useForm, InertiaForm } from '@inertiajs/vue3';
import type { RegisterForm } from '@/definitions/types';
import { useCurrentYear } from '@/composables/useDates';
import FormLayout from '@/components/layouts/FormLayout.vue';
import InputField from '@/components/global/InputField.vue';
import { Button } from '@/components/ui/button';
import { User, Mail, Lock, Building2 } from 'lucide-vue-next';

defineOptions({ hasLayout: false });

const { currentYear } = useCurrentYear();

const registerForm: InertiaForm<RegisterForm> = useForm({
    fullName: '',
    email: '',
    password: '',
    password_confirmation: '',
    companyName: '',
});

const registerUser = (): void => {
    registerForm.post('/register');
};
</script>

<template>
    <FormLayout sub-title="Create your account today">
        <template #main>
            <form class="p-8" @submit.prevent="registerUser()">
                <div class="space-y-5">
                    <InputField
                        v-model="registerForm.fullName"
                        input-name="fullName"
                        :label-name="$t('label.full-name')"
                        input-type="text"
                        :placeholder="$t('input.placeholders.full-name')"
                    >
                        <User class="h-5" />
                    </InputField>

                    <InputField
                        v-model="registerForm.email"
                        input-name="email"
                        :label-name="$t('label.email')"
                        input-type="text"
                        :placeholder="$t('input.placeholders.email')"
                    >
                        <Mail class="h-5" />
                    </InputField>

                    <InputField
                        v-model="registerForm.companyName"
                        input-name="companyName"
                        :label-name="$t('label.company')"
                        input-type="text"
                        :placeholder="$t('input.placeholders.company')"
                    >
                        <Building2 class="h-5" />
                    </InputField>

                    <InputField
                        v-model="registerForm.password"
                        input-name="password"
                        :label-name="$t('label.password')"
                        input-type="password"
                        :placeholder="$t('input.placeholders.password')"
                    >
                        <Lock class="h-5" />
                    </InputField>

                    <InputField
                        v-model="registerForm.password_confirmation"
                        input-name="confirmPassword"
                        :label-name="$t('label.confirm-password')"
                        input-type="password"
                        :placeholder="$t('input.placeholders.password')"
                    >
                        <Lock class="h-5" />
                    </InputField>

                    <Button class="button">
                        {{ $t('register.actions.create') }}
                    </Button>
                </div>
            </form>

            <div class="border-popover bg-popover border-t p-6 text-center">
                <p class="text-popover-foreground text-sm">
                    {{ $t('register.have-account') }}
                    <Link href="/login" class="text-primary hover:text-primary-hover font-medium transition-colors">{{
                        $t('register.sign-in')
                    }}</Link>
                </p>
            </div>
        </template>

        <template #footer>
            <div class="text-foreground-light text-center text-xs">
                <p>{{ $t('general.copyright', ['currentYear', currentYear.toString()]) }}</p>
            </div>
        </template>
    </FormLayout>
</template>
