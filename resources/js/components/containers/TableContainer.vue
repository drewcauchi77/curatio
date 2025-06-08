<script setup lang="ts"></script>

<template>
  <div class="overflow-auto">
    <div class="min-w-2xl">
      <slot name="head" />

      <div class="bg-background border-border rounded-t-none border-t-0">
        <slot name="body" />
      </div>
    </div>
  </div>
</template>
