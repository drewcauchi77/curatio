<script setup lang="ts">
import { Link, useForm, InertiaForm } from "@inertiajs/vue3";
import type { RegisterForm } from "@/definitions/types";
import { useCurrentYear } from "@/composables/useDates";

import FormLayout from "@/components/layouts/FormLayout.vue";
import InputField from "@/components/global/InputField.vue";
import { Button } from "@/components/ui/button";
import { User, Mail, Lock, Building2 } from 'lucide-vue-next';

const { currentYear } = useCurrentYear();
defineOptions({ hasLayout: false });

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
    <FormLayout subTitle="Create your account today">
        <template v-slot:main>
            <form class="p-8" @submit.prevent="registerUser()">
                <div class="space-y-5">
                    <InputField inputName="fullName" :labelName="$t('label.full-name')" inputType="text" :placeholder="$t('input.placeholders.full-name')" v-model="registerForm.fullName">
                        <User class="h-5" />
                    </InputField>
                    
                    <InputField inputName="email" :labelName="$t('label.email')" inputType="text" :placeholder="$t('input.placeholders.email')" v-model="registerForm.email">
                        <Mail class="h-5" />
                    </InputField>
                    
                    <InputField inputName="companyName" :labelName="$t('label.company')" inputType="text" :placeholder="$t('input.placeholders.company')" v-model="registerForm.companyName">
                        <Building2 class="h-5" />
                    </InputField>
                    
                    <InputField inputName="password" :labelName="$t('label.password')" inputType="password" :placeholder="$t('input.placeholders.password')" v-model="registerForm.password">
                        <Lock class="h-5" />
                    </InputField>
                    
                    <InputField inputName="confirmPassword" :labelName="$t('label.confirm-password')" inputType="password" :placeholder="$t('input.placeholders.password')" v-model="registerForm.password_confirmation">
                        <Lock class="h-5" />
                    </InputField>
                    
                    <Button class="button">
                        {{ $t('register.actions.create') }}
                    </Button>
                </div>
            </form>

            <div class="border-t border-popover bg-popover p-6 text-center">
                <p class="text-sm text-popover-foreground">
                    {{ $t('register.have-account') }}
                    <Link href="/login" class="font-medium text-primary hover:text-primary-hover transition-colors">{{ $t('register.sign-in') }}</Link>
                </p>
            </div>
        </template>

        <template v-slot:footer>
            <div class="text-center text-xs text-foreground-light">
                <p>{{ $t('general.copyright', ['currentYear', currentYear.toString()]) }}</p>
            </div>
        </template>
    </FormLayout>
</template>