<script setup lang="ts">
import { Users, Video, Eye } from 'lucide-vue-next';
import { ChannelInfoProps } from '@/definitions/interfaces';

defineProps<ChannelInfoProps>();
</script>

<template>
    <div  class="mt-4 p-6 bg-card border border-border rounded-lg shadow-sm">
        <div class="flex items-center space-x-4 mb-4">
            <img :src="channelInfo.profilePicture" :alt="channelInfo.name" class="w-16 h-16 rounded-full object-cover border-2 border-primary/30" >

            <div class="flex-1">
                <h3 class="text-lg font-semibold text-foreground">{{ channelInfo.name }}</h3>
                <p class="text-xs text-muted-foreground mb-1">Channel ID: {{ channelInfo.id }}</p>
                
                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-1.5">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-xs text-muted-foreground">Last sync: <strong>Today</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="flex items-center space-x-2 p-3 bg-muted/50 rounded-md">
                <Video class="h-5 w-5 text-primary" />
                <div>
                    <p class="text-sm font-medium text-foreground">{{ channelInfo.videoCount }}</p>
                    <p class="text-xs text-muted-foreground">Videos</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-2 p-3 bg-muted/50 rounded-md">
                <Users class="h-5 w-5 text-primary" />
                <div>
                    <p class="text-sm font-medium text-foreground">{{ channelInfo.subscriberCount }}</p>
                    <p class="text-xs text-muted-foreground">Subscribers</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-2 p-3 bg-muted/50 rounded-md">
                <Eye class="h-5 w-5 text-primary" />
                <div>
                    <p class="text-sm font-medium text-foreground">{{ channelInfo.viewCount }}</p>
                    <p class="text-xs text-muted-foreground">Views</p>
                </div>
            </div>
        </div>
    </div>
</template>