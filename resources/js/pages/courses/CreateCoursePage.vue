<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputField from '@/components/global/InputField.vue';
import PageHeader from '@/components/global/PageHeader.vue';
import { Button } from '@/components/ui/button';
import { Save, BookOpen, Plus, X, Search, GripVertical } from 'lucide-vue-next';

// Define module interface
interface Module {
    id: number | string;
    title: string;
}

const props = defineProps({
    modules: {
        type: Array as () => Module[],
        default: () => [],
    },
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
    return props.modules.filter((module) => module.title.toLowerCase().includes(searchTerm.value.toLowerCase()));
});

// Get available modules (not already selected)
const availableModules = computed(() => {
    return filteredModules.value.filter((module) => !selectedModules.value.includes(module.id));
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

const handleDragEnd = () => {
    isDragging.value = false;
    draggedItem.value = null;
    dragOverItem.value = null;

    // Clean up all drag classes
    const items = document.querySelectorAll('.module-item');
    items.forEach((item) => {
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
    handleDragEnd();
};

const courseForm = useForm({
    title: '',
    moduleIds: [],
});

const handleSubmit = () => {
    courseForm.post(`/courses/create`);
};
</script>

<template>
    <div class="space-y-6">
        <PageHeader back-link="/courses" :title="'Create Course Page'" has-button :button-title="'Save'">
            <Save class="h-4 w-4" />
        </PageHeader>

        <div class="container mx-auto px-4">
            <div class="bg-card border-border rounded-lg border p-6">
                <form class="space-y-5" @submit.prevent="handleSubmit()">
                    <InputField
                        v-model="courseForm.title"
                        input-name="title"
                        label-name="Course Title"
                        input-type="text"
                        :placeholder="$t('courses.placeholders.course-title')"
                    >
                        <BookOpen class="h-5" />
                    </InputField>

                    <!-- Module selection -->
                    <div class="space-y-3">
                        <label class="text-foreground block text-sm font-medium">Course Modules</label>

                        <!-- Module selector -->
                        <div class="relative">
                            <div
                                class="border-border bg-background flex w-full cursor-pointer items-center gap-2 rounded-lg border p-2"
                                @click="showDropdown = !showDropdown"
                            >
                                <BookOpen class="text-foreground-light ml-2 h-5 w-5" />
                                <div class="text-muted-foreground flex-grow text-sm">
                                    {{ selectedModules.length ? `${selectedModules.length} module(s) selected` : 'Select modules for this course' }}
                                </div>
                                <div class="pr-2">
                                    <Button variant="ghost" type="button" class="hover:bg-muted h-8 px-2">
                                        <Plus class="h-5 w-5" />
                                    </Button>
                                </div>
                            </div>

                            <div
                                v-if="showDropdown"
                                class="bg-popover border-border absolute z-10 mt-1 max-h-[300px] w-full overflow-hidden rounded-lg border shadow-lg transition-all duration-200 ease-in-out"
                            >
                                <div class="bg-popover border-border sticky top-0 border-b p-2">
                                    <div class="relative">
                                        <Search class="text-muted-foreground absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 transform" />
                                        <input
                                            ref="searchInput"
                                            v-model="searchTerm"
                                            type="text"
                                            placeholder="Search modules..."
                                            class="border-input bg-background w-full rounded-md border py-2 pr-3 pl-9 text-sm"
                                            @blur="showDropdown = false"
                                        />
                                    </div>
                                </div>

                                <div class="max-h-[250px] overflow-y-auto">
                                    <div v-if="availableModules.length === 0" class="text-muted-foreground p-3 text-center text-sm">
                                        No modules available
                                    </div>
                                    <div
                                        v-for="module in availableModules"
                                        :key="module.id"
                                        class="hover:bg-accent flex cursor-pointer items-center p-2 transition-colors duration-150 ease-in-out"
                                        @click="addModule(module.id)"
                                    >
                                        <span class="ml-2">{{ module.title }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selected modules list with drag and drop -->
                        <transition-group name="module-list" tag="div" class="mt-3 space-y-2">
                            <div
                                v-for="(moduleId, index) in selectedModules"
                                :key="moduleId"
                                class="module-item bg-muted border-border flex items-center justify-between rounded-lg border p-3 shadow-sm transition-all duration-200 ease-in-out hover:shadow-md"
                                draggable="true"
                                @dragstart="handleDragStart(index, $event)"
                                @dragover.prevent="handleDragOver(index, $event)"
                                @dragend="handleDragEnd()"
                                @drop="handleDrop(index, $event)"
                            >
                                <div class="flex flex-grow items-center">
                                    <div
                                        class="text-muted-foreground hover:text-foreground mr-2 cursor-move"
                                        @mousedown="($event.target as HTMLElement).closest('.module-item')?.setAttribute('draggable', 'true')"
                                        @mouseup="($event.target as HTMLElement).closest('.module-item')?.setAttribute('draggable', 'false')"
                                    >
                                        <GripVertical class="h-4 w-4" />
                                    </div>
                                    <BookOpen class="text-foreground-light mr-3 h-4 w-4" />
                                    <span class="text-sm font-medium">
                                        {{ props.modules.find((m) => m.id === moduleId)?.title || moduleId }}
                                    </span>
                                </div>
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="sm"
                                    class="hover:bg-destructive/10 hover:text-destructive h-7 w-7 rounded-full p-0 transition-colors duration-150"
                                    @click="removeModule(index)"
                                >
                                    <X class="h-4 w-4" />
                                </Button>
                            </div>
                        </transition-group>

                        <!-- Empty state with drag and drop instruction -->
                        <div
                            v-if="selectedModules.length === 0"
                            class="border-border text-muted-foreground mt-2 rounded-lg border border-dashed p-3 text-center text-sm"
                        >
                            No modules selected. Add one or more modules to this course.
                        </div>
                        <div v-else-if="selectedModules.length > 1" class="text-muted-foreground mt-1 flex items-center text-xs">
                            <GripVertical class="mr-1 h-3 w-3" />
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
    box-shadow:
        0 10px 15px -3px rgba(0, 0, 0, 0.1),
        0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.module-item.drag-over {
    border-top: 2px solid var(--color-primary);
}
</style>
