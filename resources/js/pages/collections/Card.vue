<template>
    <div class="collection-card bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
        <div class="aspect-w-16 aspect-h-9 overflow-hidden">
            <img v-if="collection.image" :src="'/storage/' + collection.image" :alt="collection.title" class="object-cover w-full h-full">
            <div v-else class="bg-gray-200 flex items-center justify-center h-full">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ collection.title }}</h3>
            <p v-if="collection.description" class="text-gray-600 text-sm mb-4 line-clamp-2">
                {{ collection.description }}
            </p>
            <div class="flex flex-col justify-start items-start">
                <div class="text-xs text-gray-500 mb-2">
                    {{ itemCount }} элемент{{ itemCount === 1 ? '' : itemCount > 1 && itemCount < 5 ? 'а' : 'ов' }}
                </div>
                <Link :href="route('collections.show', collection.slug)" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    Просмотр
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {defineProps, ref, computed, onMounted} from 'vue';

const props = defineProps({
    collection: {
        type: Object,
        required: true
    }
});

interface Item {
    id: number;
    title: string;
    description: string;
    image?: string | null;
    created_at: string;
}

const items = ref<Item[]>([]);

const getItemsCollection = async (collectionId: number) => {
    try {
        const response = await fetch(`/collections/${props.collection.id}/items`, {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('Ошибка загрузки данных');

        const data = await response.json();
        items.value = data.items;
        console.log(data.items);
    } catch (error) {
        console.error('Ошибка при загрузке элементов коллекции:', error);
    }
};

const itemCount = computed(() => {
    return items.value?.length || 0;
});

onMounted(() => {
    getItemsCollection(props.collection.id);
});
</script>
