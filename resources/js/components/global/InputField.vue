<script setup lang="ts">
import { InputFieldProps } from '@/definitions/interfaces';

const model = defineModel();

defineProps<InputFieldProps>();
</script>

<template>
    <div class="space-y-2">
        <label class="text-foreground mb-2 block text-sm font-medium" :for="inputName">{{ labelName }}</label>

        <div class="relative">
            <div class="text-foreground-light absolute inset-y-0 left-0 flex items-center pl-3">
                <slot></slot>
            </div>

            <input :id="inputName" :type="inputType" :placeholder="placeholder" class="input" v-model="model" />
        </div>
    </div>
</template>
