<script setup lang="ts">
import { Link, useForm, InertiaForm } from "@inertiajs/vue3";
import type { LoginForm } from "@/definitions/types";
import { useCurrentYear } from "@/composables/useDates";

import FormLayout from "@/components/layouts/FormLayout.vue";
import InputField from "@/components/global/InputField.vue";
import { Button } from "@/components/ui/button";
import { Mail, Lock } from 'lucide-vue-next';

const { currentYear } = useCurrentYear();
defineOptions({ hasLayout: false });

const loginForm: InertiaForm<LoginForm> = useForm({
    email: '',
    password: '',
});

const loginUser = (): void => {
    loginForm.post('/login');
};
</script>

<template>
    <FormLayout>
        <template v-slot:main>
            <form class="p-8" @submit.prevent="loginUser()">
                <div class="space-y-5">
                    <InputField inputName="email" :labelName="$t('label.email')" inputType="email" :placeholder="$t('input.placeholders.email')" v-model="loginForm.email">
                        <Mail class="h-5" />
                    </InputField>

                    <InputField inputName="password" :labelName="$t('label.password')" inputType="password" :placeholder="$t('input.placeholders.password')" v-model="loginForm.password">
                        <Lock class="h-5" />
                    </InputField>
                        
                    <Button class="button">
                        {{ $t('login.sign-in') }}
                    </Button>
                </div>      
            </form>

            <div class="border-t border-popover bg-popover p-6 text-center">
                <p class="text-sm text-popover-foreground">
                    {{ $t('login.have-account') }}
                    <Link href="/register" class="font-medium text-primary hover:text-primary-hover transition-colors">{{ $t('login.actions.create') }}</Link>                       
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