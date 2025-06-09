<script setup lang="ts">
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import PageHeader from '@/components/global/PageHeader.vue';
import CreateItem from '@/components/sections/CreateItem.vue';
import SearchBar from '@/components/filters/SearchBar.vue';
import SortBar from '@/components/filters/SortBar.vue';
import StatusBar from '@/components/filters/StatusBar.vue';
import EmptyData from '@/components/sections/EmptyData.vue';
import TableContainer from '@/components/containers/TableContainer.vue';
import TableHead from '@/components/tables/TableHead.vue';
import TableRow from '@/components/tables/TableRow.vue';
import VideoGenerate from '@/components/modals/VideoGenerate.vue';
import { Button } from '@/components/ui/button';
import { Play, PlusCircle } from 'lucide-vue-next';
import { capitalizeFirstLetter, debounce } from '@/helpers/helpers';
import { ModulesIndexProps } from '@/definitions/interfaces';
import Pagination from '@/components/global/Pagination.vue';
import { useFlashMessages } from '@/composables/useFlashMessages';

const props = defineProps<ModulesIndexProps>();

const searchTerm = ref(props.filters.q);
const order = ref(`${props.filters.orderBy}|${props.filters.order}`);
const status = ref(props.filters.status);
const currentPage = ref(props.modules.current_page);

const withModal = ref(props.modal);

useFlashMessages(props.flash, 4000);

const getFilteredModules = (): void => {
    const params = {
        ...(searchTerm.value ? { q: searchTerm.value } : {}),
        ...(order.value
            ? {
                  orderBy: order.value.split('|')[0],
                  order: order.value.split('|')[1],
              }
            : {}),
        ...(status.value ? { status: status.value } : {}),
        ...(currentPage.value ? { page: currentPage.value } : {}),
    };

    router.get('/modules', params, { preserveState: true, replace: true });
};

watch(searchTerm, debounce(getFilteredModules, 300));
watch(order, getFilteredModules);
watch(status, getFilteredModules);
</script>

<template>
    <div class="space-y-6">
        <MetaTags :title="$t('metatags.modules/Modules')"></MetaTags>
        <PageHeader :back-link="'/dashboard'" :title="$t('modules.title')">
            <Button as-child class="bg-[#ff0033] hover:bg-red-600">
                <Link href="/modules/generate" class="inline-flex items-center gap-2">
                    <Play class="h-4 w-4" />
                    <strong class="hidden sm:block">{{ $t('modules.generate-title') }}</strong>
                </Link>
            </Button>

            <Button as-child class="ml-4">
                <Link href="/modules/create" class="inline-flex items-center gap-2">
                    <PlusCircle class="h-4 w-4" />
                    <strong class="hidden sm:block">{{ $t('modules.create-title') }}</strong>
                </Link>
            </Button>
        </PageHeader>

        <div class="container mx-auto px-2 sm:px-4">
            <CreateItem
                v-if="(!modules || !modules.data || modules.data.length === 0) && searchTerm === ''"
                :title="$t('modules.no-modules')"
                :subtitle="$t('modules.create-module')"
                :button-link="'/modules/create'"
                :button-text="$t('modules.create-title')"
            />

            <div v-else class="w-full">
                <div class="mb-4 flex flex-col gap-4 sm:flex-row">
                    <SearchBar v-model="searchTerm" :placeholder="$t('modules.placeholders.search')" />
                    <SortBar v-model="order" />
                </div>

                <StatusBar
                    :current-status="status"
                    :statuses="[
                        { label: 'All', value: 'all', count: counts.all },
                        { label: 'Published', value: 'published', count: counts.published },
                        { label: 'Drafts', value: 'draft', count: counts.draft },
                        { label: 'Trash', value: 'deleted', count: counts.deleted },
                    ]"
                    @status="(s) => (status = s)"
                />

                <TableContainer>
                    <template #head>
                        <TableHead
                            :headings="[$t('modules.title'), $t('modules.description'), $t('general.status'), $t('general.actions')]"
                            :sizes="[3, 5, 3, 1]"
                        />
                    </template>
                    <template #body>
                        <EmptyData v-if="modules.data.length === 0" :title="$t('modules.not-found-modules')" />
                        <TableRow
                            v-for="(module, index) in modules.data"
                            v-else
                            :key="module.id"
                            :class="{ 'bg-muted/50': index % 2 == 1 }"
                            :title="module.title"
                            :items="[module.title, module.description, capitalizeFirstLetter(module.status_slug)]"
                            :sizes="[3, 5, 3, 1]"
                            :link="`/modules/${module.id}`"
                            :view-title="$t('general.view')"
                        />
                    </template>
                </TableContainer>

                <Pagination :pagination="modules" preserve-query></Pagination>
            </div>
        </div>
    </div>

    <VideoGenerate
        v-if="withModal == 'VideoGenerateModal'"
        :title="$t('videos.generate-title')"
        :connection="connection"
        :channel-data="channelData"
    ></VideoGenerate>
</template>
