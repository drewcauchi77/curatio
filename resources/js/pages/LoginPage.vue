<script setup lang="ts">
import { Link, useForm, InertiaForm } from '@inertiajs/vue3';
import type { LoginForm } from '@/definitions/types';
import { useCurrentYear } from '@/composables/useDates';
import FormLayout from '@/components/layouts/FormLayout.vue';
import InputField from '@/components/global/InputField.vue';
import { Button } from '@/components/ui/button';
import { Mail, Lock } from 'lucide-vue-next';

defineOptions({ hasLayout: false });

const { currentYear } = useCurrentYear();

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
        <template #main>
            <form class="p-8" @submit.prevent="loginUser()">
                <div class="space-y-5">
                    <InputField
                        v-model="loginForm.email"
                        input-name="email"
                        :label-name="$t('label.email')"
                        input-type="email"
                        :placeholder="$t('input.placeholders.email')"
                    >
                        <Mail class="h-5" />
                    </InputField>

                    <InputField
                        v-model="loginForm.password"
                        input-name="password"
                        :label-name="$t('label.password')"
                        input-type="password"
                        :placeholder="$t('input.placeholders.password')"
                    >
                        <Lock class="h-5" />
                    </InputField>

                    <Button class="button">
                        {{ $t('login.sign-in') }}
                    </Button>
                </div>
            </form>

            <div class="border-popover bg-popover border-t p-6 text-center">
                <p class="text-popover-foreground text-sm">
                    {{ $t('login.have-account') }}
                    <Link href="/register" class="text-primary hover:text-primary-hover font-medium transition-colors">{{
                        $t('login.actions.create')
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
