<script setup lang="ts">
import { Users, Video, Eye } from 'lucide-vue-next';
import { ChannelInfoProps } from '@/definitions/interfaces';

defineProps<ChannelInfoProps>();
</script>

<template>
    <div class="bg-card border-border mt-4 rounded-lg border p-6 shadow-sm">
        <div class="mb-4 flex items-center space-x-4">
            <img :src="channelInfo.profilePicture" :alt="channelInfo.name" class="border-primary/30 h-16 w-16 rounded-full border-2 object-cover" />

            <div class="flex-1">
                <h3 class="text-foreground text-lg font-semibold">{{ channelInfo.name }}</h3>
                <p class="text-muted-foreground mb-1 text-xs">Channel ID: {{ channelInfo.id }}</p>

                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-1.5">
                        <div class="h-2 w-2 rounded-full bg-green-500"></div>
                        <span class="text-muted-foreground text-xs">Last sync: <strong>Today</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="bg-muted/50 flex items-center space-x-2 rounded-md p-3">
                <Video class="text-primary h-5 w-5" />
                <div>
                    <p class="text-foreground text-sm font-medium">{{ channelInfo.videoCount }}</p>
                    <p class="text-muted-foreground text-xs">Videos</p>
                </div>
            </div>

            <div class="bg-muted/50 flex items-center space-x-2 rounded-md p-3">
                <Users class="text-primary h-5 w-5" />
                <div>
                    <p class="text-foreground text-sm font-medium">{{ channelInfo.subscriberCount }}</p>
                    <p class="text-muted-foreground text-xs">Subscribers</p>
                </div>
            </div>

            <div class="bg-muted/50 flex items-center space-x-2 rounded-md p-3">
                <Eye class="text-primary h-5 w-5" />
                <div>
                    <p class="text-foreground text-sm font-medium">{{ channelInfo.viewCount }}</p>
                    <p class="text-muted-foreground text-xs">Views</p>
                </div>
            </div>
        </div>
    </div>
</template>
