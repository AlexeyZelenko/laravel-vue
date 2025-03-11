<template>
    <div class="flex flex-col justify-between w-full collection-item-card h-full bg-white rounded-lg overflow-hidden">
        <div class="aspect-w-1 aspect-h-1 overflow-hidden">
            <div class="bg-gray-200 flex items-center justify-center h-full w-full py-2 min-h-16 text-xl text-gray-400">
                {{ item.title }}
            </div>
        </div>
        <div v-if="item.description" class="flex justify-center text-gray-600 text-ms mb-6 px-4">
            <p class="break-words whitespace-pre-line text-center word-break overflow-wrap">
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

<style scoped>
.word-break {
    word-break: break-word;
    overflow-wrap: break-word;
}
</style>
