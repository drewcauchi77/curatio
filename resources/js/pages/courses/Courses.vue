<script setup lang="ts">
import { ref, watch } from 'vue';
import { Link, router } from "@inertiajs/vue3";
import { capitalizeFirstLetter, debounce } from '@/helpers/helpers';
import { CoursesIndexProps } from '@/definitions/interfaces';
import PageHeader from '@/components/global/PageHeader.vue';
import CreateItem from '@/components/sections/CreateItem.vue';
import SearchBar from '@/components/filters/SearchBar.vue';
import SortBar from '@/components/filters/SortBar.vue';
import EmptyData from '@/components/sections/EmptyData.vue';
import TableHead from '@/components/tables/TableHead.vue';
import TableRow from '@/components/tables/TableRow.vue';
import TableContainer from '@/components/containers/TableContainer.vue';
import { Button } from "@/components/ui/button";
import { PlusCircle } from 'lucide-vue-next';

const props = defineProps<CoursesIndexProps>();

const searchTerm = ref(props.q);
const order = ref(`${props.orderBy}|${props.order}`);

const getFilteredCourses = (): void => {
    const params = {
        ...(searchTerm.value ? { q: searchTerm.value } : {}),
        ...(order.value ? { 
            orderBy: order.value.split('|')[0],
            order: order.value.split('|')[1]
        } : {}),
    }

    router.get('/courses', params, { preserveState: true, replace: true });
};

watch(searchTerm, debounce(getFilteredCourses, 300));
watch(order, getFilteredCourses);
</script>

<template>
    <div class="space-y-6">
        <PageHeader :back-link="'/dashboard'" :title="$t('courses.title')">
            <Button as-child class="ml-4">
                <Link href="/courses/create" class="inline-flex items-center gap-2">
                    <PlusCircle class="h-4 w-4" />
                    <strong class="hidden sm:block">{{ $t('courses.create-title') }}</strong>
                </Link>
            </Button>
        </PageHeader>

        <div class="container px-2 sm:px-4 mx-auto">
            <CreateItem v-if="(!courses || !courses.data || courses.data.length === 0)"
                :title="$t('courses.no-courses')" 
                :subtitle="$t('courses.create-course')" 
                :button-link="'/courses/create'" 
                :button-text="$t('courses.create-title')" />

            <div v-else class="w-full">
                <div class="flex flex-col sm:flex-row gap-4 mb-4">
                    <SearchBar v-model="searchTerm" :placeholder="$t('courses.placeholders.search')" />
                    <SortBar v-model="order" />
                </div>

                <TableContainer>
                    <template v-slot:head>
                        <TableHead :headings="[
                            $t('courses.title'),
                            $t('general.status'),
                            $t('general.actions')
                        ]" :sizes="[8, 3, 1]" />
                    </template>
                    <template v-slot:body>
                        <EmptyData v-if="courses.data.length === 0" :title="$t('modules.not-found-modules')" />
                        <TableRow v-else v-for="(course, index) in courses.data" :key="course.id" :class="{'bg-muted/50': index % 2 == 1}"
                            :items="[
                                course.title,
                                capitalizeFirstLetter(course.status),
                            ]"
                            :sizes="[8, 3, 1]"
                            :link="`/courses/${course.id}`"
                            :view-title="$t('general.view')" />
                    </template>
                </TableContainer>
            </div>
        </div>
    </div>
</template>