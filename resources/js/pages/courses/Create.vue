<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputField from '@/components/global/InputField.vue';
import PageHeader from '@/components/global/PageHeader.vue';
import { Button } from "@/components/ui/button";
import { Save, BookOpen, Plus, X, Search, GripVertical } from 'lucide-vue-next';

// Define module interface
interface Module {
    id: number | string;
    title: string;
}

const props = defineProps({
    modules: {
        type: Array as () => Module[],
        default: () => []
    }
});

// Track selected modules
const selectedModules = ref<(string | number)[]>([]);
// Module to be added from dropdown
const currentModule = ref<string | number>('');
// Search term for filtering modules
const searchTerm = ref('');
// Show/hide dropdown
const showDropdown = ref(false);
// For drag and drop functionality
const draggedItem = ref<number | null>(null);
const dragOverItem = ref<number | null>(null);
const isDragging = ref(false);

// Filter modules based on search term
const filteredModules = computed(() => {
    if (!searchTerm.value) return props.modules;
    return props.modules.filter(module => 
        module.title.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

// Get available modules (not already selected)
const availableModules = computed(() => {
    return filteredModules.value.filter(module => 
        !selectedModules.value.includes(module.id)
    );
});

// Add selected module to the list
const addModule = (moduleId: string | number) => {
    if (moduleId && !selectedModules.value.includes(moduleId)) {
        selectedModules.value.push(moduleId);
        courseForm.moduleIds = selectedModules.value;
        currentModule.value = '';
        searchTerm.value = '';
        setTimeout(() => {
            showDropdown.value = false;
        }, 100);
    }
};

// Remove a module from the selected list
const removeModule = (index: number) => {
    selectedModules.value.splice(index, 1);
    courseForm.moduleIds = selectedModules.value;
};

// Focus the search input when dropdown is shown
const searchInput = ref<HTMLInputElement | null>(null);
watch(showDropdown, (newVal) => {
    if (newVal && searchInput.value) {
        setTimeout(() => {
            searchInput.value?.focus();
        }, 50);
    }
});

// Drag and drop handlers
const handleDragStart = (index: number, event: DragEvent) => {
    if (!event.dataTransfer) return;
    
    isDragging.value = true;
    draggedItem.value = index;
    
    // Set data for drag operation
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.dropEffect = 'move';
    event.dataTransfer.setData('text/plain', index.toString());
    
    // Add styling for dragged element
    if (event.target instanceof HTMLElement) {
        setTimeout(() => {
            if (event.target instanceof HTMLElement) {
                event.target.classList.add('dragging');
            }
        }, 0);
    }
};

const handleDragOver = (index: number, event: DragEvent) => {
    event.preventDefault();
    dragOverItem.value = index;
    
    // Add visual feedback for drag over
    const items = document.querySelectorAll('.module-item');
    items.forEach((item, i) => {
        if (i === index) {
            item.classList.add('drag-over');
        } else {
            item.classList.remove('drag-over');
        }
    });
};

const handleDragEnd = (event: DragEvent) => {
    isDragging.value = false;
    draggedItem.value = null;
    dragOverItem.value = null;
    
    // Clean up all drag classes
    const items = document.querySelectorAll('.module-item');
    items.forEach(item => {
        item.classList.remove('dragging', 'drag-over');
    });
};

const handleDrop = (index: number, event: DragEvent) => {
    event.preventDefault();
    
    // Only proceed if we have a valid drag source
    if (draggedItem.value === null || draggedItem.value === index) return;
    
    // Reorder the selected modules
    const items = [...selectedModules.value];
    const draggedValue = items[draggedItem.value];
    
    // Remove dragged item from its position
    items.splice(draggedItem.value, 1);
    
    // Insert at new position
    items.splice(index, 0, draggedValue);
    
    // Update the array
    selectedModules.value = items;
    courseForm.moduleIds = selectedModules.value;
    
    // Clean up
    handleDragEnd(event);
};

const courseForm = useForm({
    title: '',
    moduleIds: []
});

const handleSubmit = () => {
    courseForm.post(`/courses/create`, {
        onSuccess: (data: any): void => {
            console.log('onSuccess', data)
        }
    });
}
</script>

<template>
    <div class="space-y-6">
        <PageHeader back-link="/courses" 
            :title="'Create Course Page'" 
            :has-button="true" 
            :button-title="'Save'">
            <Save class="h-4 w-4" />
        </PageHeader>

        <div class="container px-4 mx-auto">
            <div class="bg-card border border-border rounded-lg p-6">
                <form class="space-y-5" @submit.prevent="handleSubmit()">
                    <InputField input-name="title" label-name="Course Title" input-type="text" :placeholder="$t('courses.placeholders.course-title')" v-model="courseForm.title">
                        <BookOpen class="h-5" />
                    </InputField>

                    <!-- Module selection -->
                    <div class="space-y-3">
                        <label class="text-sm font-medium text-foreground block">Course Modules</label>
                        
                        <!-- Module selector -->
                        <div class="relative">
                            <div class="flex items-center gap-2 w-full p-2 border border-border rounded-lg bg-background cursor-pointer"
                                @click="showDropdown = !showDropdown">
                                <BookOpen class="h-5 w-5 ml-2 text-foreground-light" />
                                <div class="flex-grow text-sm text-muted-foreground">
                                    {{ selectedModules.length ? `${selectedModules.length} module(s) selected` : 'Select modules for this course' }}
                                </div>
                                <div class="pr-2">
                                    <Button variant="ghost" type="button" class="h-8 px-2 hover:bg-muted">
                                        <Plus class="h-5 w-5" />
                                    </Button>
                                </div>
                            </div>
                            
                            <!-- Dropdown for module selection -->
                            <div v-if="showDropdown" 
                                class="absolute z-10 mt-1 w-full bg-popover border border-border rounded-lg shadow-lg overflow-hidden transition-all duration-200 ease-in-out"
                                style="max-height: 300px;">
                                <div class="sticky top-0 p-2 bg-popover border-b border-border">
                                    <div class="relative">
                                        <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                        <input 
                                            ref="searchInput"
                                            v-model="searchTerm" 
                                            type="text" 
                                            placeholder="Search modules..." 
                                            class="w-full py-2 pl-9 pr-3 rounded-md border border-input bg-background text-sm" 
                                            @blur="showDropdown = false"
                                        />
                                    </div>
                                </div>
                                
                                <div class="overflow-y-auto max-h-[250px]">
                                    <div v-if="availableModules.length === 0" class="p-3 text-sm text-center text-muted-foreground">
                                        No modules available
                                    </div>
                                    <div 
                                        v-for="module in availableModules" 
                                        :key="module.id"
                                        class="p-2 hover:bg-accent cursor-pointer transition-colors duration-150 ease-in-out flex items-center"
                                        @click="addModule(module.id)"
                                    >
                                        <span class="ml-2">{{ module.title }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Selected modules list with drag and drop -->
                        <transition-group 
                            name="module-list" 
                            tag="div" 
                            class="mt-3 space-y-2"
                        >
                            <div v-for="(moduleId, index) in selectedModules" :key="moduleId" 
                                class="module-item flex items-center justify-between p-3 bg-muted border border-border rounded-lg shadow-sm hover:shadow-md transition-all duration-200 ease-in-out"
                                draggable="true"
                                @dragstart="handleDragStart(index, $event)"
                                @dragover.prevent="handleDragOver(index, $event)"
                                @dragend="handleDragEnd($event)"
                                @drop="handleDrop(index, $event)">
                                <div class="flex items-center flex-grow">
                                    <div class="mr-2 cursor-move text-muted-foreground hover:text-foreground"
                                        @mousedown="($event.target as HTMLElement).closest('.module-item')?.setAttribute('draggable', 'true')"
                                        @mouseup="($event.target as HTMLElement).closest('.module-item')?.setAttribute('draggable', 'false')">
                                        <GripVertical class="h-4 w-4" />
                                    </div>
                                    <BookOpen class="h-4 w-4 mr-3 text-foreground-light" />
                                    <span class="text-sm font-medium">
                                        {{ props.modules.find(m => m.id === moduleId)?.title || moduleId }}
                                    </span>
                                </div>
                                <Button type="button" variant="ghost" size="sm" @click="removeModule(index)" 
                                    class="h-7 w-7 p-0 rounded-full hover:bg-destructive/10 hover:text-destructive transition-colors duration-150">
                                    <X class="h-4 w-4" />
                                </Button>
                            </div>
                        </transition-group>
                        
                        <!-- Empty state with drag and drop instruction -->
                        <div v-if="selectedModules.length === 0" class="mt-2 p-3 border border-dashed border-border rounded-lg text-sm text-center text-muted-foreground">
                            No modules selected. Add one or more modules to this course.
                        </div>
                        <div v-else-if="selectedModules.length > 1" class="mt-1 text-xs text-muted-foreground flex items-center">
                            <GripVertical class="h-3 w-3 mr-1" />
                            <span>Drag modules to reorder</span>
                        </div>
                    </div>
                    
                    <div class="pt-3">
                        <Button type="submit" class="button inline-flex items-center gap-2">
                            <Save class="h-5 w-5" />
                            {{ $t(`courses.actions.save`) }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.module-list-enter-active,
.module-list-leave-active {
  transition: all 0.3s ease;
}
.module-list-enter-from,
.module-list-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

.module-item {
  transition: all 0.2s ease;
}

.module-item.dragging {
  opacity: 0.5;
  background-color: var(--color-accent);
  transform: scale(1.02);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.module-item.drag-over {
  border-top: 2px solid var(--color-primary);
}
</style>