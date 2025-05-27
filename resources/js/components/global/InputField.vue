<script setup lang="ts">
import { InputFieldProps } from '@/definitions/interfaces';

const model = defineModel();

defineProps<InputFieldProps>();
</script>

<template>
    <div class="space-y-2">
        <label class="text-sm font-medium text-foreground block mb-2" :for="inputName">{{ labelName }}</label>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-foreground-light">
                <slot></slot>
            </div>

            <input :id="inputName" :type="inputType" :placeholder="placeholder" class="input" v-model="model" />
        </div>
    </div>
</template>