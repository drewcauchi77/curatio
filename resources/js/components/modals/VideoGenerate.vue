<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, Transition } from 'vue';
import { X } from 'lucide-vue-next';
import { useForm, router } from '@inertiajs/vue3';
import { useStatusStore } from '@/store/status.store';
import { Button } from '@/components/ui/button';

const statusStore = useStatusStore();
const isVisible = ref(false);

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  title: {
    type: String,
    default: 'Generate Video'
  }
});

// Close modal with animation
const closeModal = () => {
  isVisible.value = false;
  // Wait for animation to complete before navigating
  setTimeout(() => {
    router.visit('/modules');
  }, 300);
};

// Handle escape key press
const handleKeyDown = (e: KeyboardEvent) => {
  if (e.key === 'Escape') {
    closeModal();
  }
};

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('keydown', handleKeyDown);
  statusStore.setIsBodyScrollable(false);
  // Trigger enter animation after mount
  setTimeout(() => {
    isVisible.value = true;
  }, 50);
});

onBeforeUnmount(() => {
  document.removeEventListener('keydown', handleKeyDown);
  statusStore.setIsBodyScrollable(true);
});

const channelForm = useForm({
    channel: '',
});

const handleSubmit = () => {
    channelForm.post('/modules/generate', {
        onSuccess: () => {
            closeModal();
        }
    });
}
</script>

<template>
  <div class="fixed inset-0 z-50 flex items-center justify-end transition-opacity duration-300 bg-black/50 opacity-100">
    <div class="absolute inset-0" @click="closeModal"></div>
    
    <div 
      class="relative h-full w-full max-w-[97%] sm:max-w-[550px] overflow-y-auto bg-background shadow-xl transition-all duration-300 ease-in-out transform"
      :class="isVisible ? 'translate-x-0' : 'translate-x-full'"
    >
      <div class="sticky top-0 z-10 flex items-center justify-between border-b border-border bg-background p-4">
        <h2 class="text-xl font-semibold text-foreground">{{ title }}</h2>
        <button 
          type="button" 
          @click="closeModal" 
          class="rounded-full p-1 text-foreground-light opacity-80 hover:opacity-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary"
        >
          <X class="h-6 w-6" />
        </button>
      </div>
      
      <div class="p-6">
        <slot>
          <div class="space-y-6">
            <p class="text-foreground">Enter your YouTube channel information below to generate video content.</p>

            <form @submit.prevent="handleSubmit" class="space-y-4">
              <div class="space-y-2">
                <label for="channel" class="text-sm font-medium text-foreground block">YouTube Channel</label>
                <div class="relative">
                  <input 
                    id="channel" 
                    type="text" 
                    placeholder="Enter your YouTube channel" 
                    class="w-full rounded-md border border-input bg-card px-4 py-2 ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" 
                    v-model="channelForm.channel" 
                  />
                </div>
                <p v-if="channelForm.errors.channel" class="text-sm text-destructive mt-1">
                  {{ channelForm.errors.channel }}
                </p>
              </div>
            </form>
          </div>
        </slot>
      </div>
      
      <!-- Modal footer -->
      <div class="sticky bottom-0 border-t border-border bg-background p-4">
        <div class="flex justify-end gap-3">
          <Button
            variant="outline"
            @click="closeModal"
          >
            Cancel
          </Button>
          <Button
            @click="handleSubmit"
            :disabled="channelForm.processing"
          >
            {{ channelForm.processing ? 'Generating...' : 'Generate' }}
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.translate-x-full {
  transform: translateX(100%);
}
</style>