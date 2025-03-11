<template>
    <div class="collection-item-card bg-white rounded-lg shadow-md overflow-hidden">
        <div class="aspect-w-1 aspect-h-1 overflow-hidden">
            <img v-if="item.image" :src="'/storage/' + item.image" :alt="item.title" class="object-cover w-full h-full">
            <div v-else class="bg-gray-200 flex items-center justify-center h-full">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
        <div class="p-3">
            <h4 class="text-md font-medium text-gray-800 mb-1">{{ item.title }}</h4>
            <p v-if="item.description" class="text-gray-600 text-xs line-clamp-2">
                {{ item.description }}
            </p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    item: {
        type: Object,
        required: true
    }
});
</script>
