<script setup lang="ts">
import { InputFieldProps } from '@/definitions/interfaces';

defineProps<InputFieldProps>();
const model = defineModel<string>();
</script>

<template>
    <div class="space-y-2">
        <label class="text-foreground mb-2 block text-sm font-medium" :for="inputName">{{ labelName }}</label>

        <div class="relative">
            <div class="text-foreground-light absolute inset-y-0 left-0 flex items-center pl-3">
                <slot></slot>
            </div>

            <input :id="inputName" v-model="model" :type="inputType" :placeholder="placeholder" class="input" />
        </div>
    </div>
</template>
