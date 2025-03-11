<template>
    <Head :title="collection.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ collection?.name }}</h2>
                <Link :href="route('collections.items.create', collection?.slug)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Добавить элемент
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="md:w-1/3">
                                <div class="rounded-lg overflow-hidden shadow-md">
                                    <img v-if="collection?.image" :src="'/storage/' + collection?.image" :alt="collection?.name" class="w-full h-auto">
                                    <div v-else class="bg-gray-200 flex items-center justify-center h-48">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="md:w-2/3">
                                <h1 class="text-2xl font-bold text-gray-800 mb-3">{{ collection?.name }}</h1>
                                <p v-if="collection?.description" class="text-gray-600 mb-6">{{ collection?.description }}</p>
                                <div v-if="collection?.created_at" class="flex items-center text-sm text-gray-500">
                                    <span>Создано: {{ formatDate(collection.created_at) }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ items?.length }} элемент{{ items.length === 1 ? '' :
                                        items?.length > 1 && items?.length < 5 ? 'а' : 'ов' }}</span>
                                </div>
                                <div class="mt-6">
                                    <Link :href="route('collections.items.create', collection)" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Добавить элемент
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Элементы коллекции</h3>

<!--                        <p class="text-blue-300">{{items}}</p>-->
                        <div v-if="!items || items?.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">В этой коллекции еще нет элементов</h3>
                            <p class="mt-1 text-sm text-gray-500">Начните с добавления первого элемента в коллекцию.</p>
                            <div class="mt-6">
                                <Link :href="route('collections.items.create', collection)" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Добавить элемент
                                </Link>
                            </div>
                        </div>

                        <div v-else class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <CollectionItemCard
                                v-for="item in items"
                                :key="item.id"
                                :item="item"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import CollectionItemCard from './ItemCard.vue';
import { format } from 'date-fns';
import { ru } from 'date-fns/locale';
import { defineProps, onMounted, ref } from 'vue';
import type {BreadcrumbItem} from "@/types";

interface Collection {
    id: number;
    name: string;
    slug: string;
    visibility: string;
    description: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Collections', href: '/collections' },
    { title: 'Collection', href: '/collections/items' },
    { title: 'Show', href: '/collections/items/show' },
];

const props = defineProps({
    collection: Object
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
        const response = await fetch(`/collections/${collectionId}/items`, {
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

const formatDate = (dateString) => {
    return format(new Date(dateString), 'dd MMMM yyyy', { locale: ru });
};

onMounted(() => {
    getItemsCollection(props.collection?.id);
});
</script>
