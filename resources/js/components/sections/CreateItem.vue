<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Plus, ImageIcon } from 'lucide-vue-next';
import { CreateItemProps } from '@/definitions/interfaces';

defineProps<CreateItemProps>();
</script>

<template>
    <div class="bg-card border-border rounded-lg border p-8 text-center">
        <div class="bg-primary/10 mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full">
            <ImageIcon class="text-primary h-6 w-6" />
        </div>

        <h3 class="mb-2 text-lg font-semibold">{{ title }}</h3>

        <p class="text-muted-foreground mb-4">{{ subtitle }}</p>

        <Button as-child>
            <Link :href="buttonLink" class="inline-flex items-center gap-2">
                <Plus class="mr-1 h-4 w-4" />
                <strong>{{ buttonText }}</strong>
            </Link>
        </Button>
    </div>
</template>
