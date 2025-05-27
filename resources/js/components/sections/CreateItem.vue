<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { Plus, ImageIcon } from 'lucide-vue-next';
import { CreateItemProps } from "@/definitions/interfaces";

defineProps<CreateItemProps>();
</script>

<template>
    <div class="bg-card rounded-lg p-8 text-center border border-border">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 mb-4">
            <ImageIcon class="h-6 w-6 text-primary" />
        </div>

        <h3 class="text-lg font-semibold mb-2">{{ title }}</h3>

        <p class="text-muted-foreground mb-4">{{ subtitle }}</p>
        
        <Button as-child>
            <Link :href="buttonLink" class="inline-flex items-center gap-2">
                <Plus class="h-4 w-4 mr-1" />
                <strong>{{ buttonText }}</strong>
            </Link>
        </Button>
    </div>
</template>