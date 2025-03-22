<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import CollectionCard from "./Card.vue";

interface Collection {
    id: number;
    name: string;
    slug: string;
    visibility: string;
    description: string;
}

const collections = ref<Collection[]>([]);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Collections', href: '/collections' },
];

const getCollection = async () => {
    try {
        const response = await fetch('/collections', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('Ошибка загрузки данных');

        const data = await response.json();
        collections.value = data.collections;
    } catch (error) {
        console.error('Ошибка при загрузке коллекций:', error);
    }
};


onMounted(() => {
    getCollection();
});
</script>

<template>
    <Head title="Collections" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="collections.length === 0" class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">У вас еще нет коллекций</h3>
                            <p class="mt-1 text-sm text-gray-500">Начните с создания вашей первой коллекции.</p>
                            <div class="mt-6">
                                <Link :href="route('collections.create')" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Создать коллекцию
                                </Link>
                            </div>
                        </div>

                        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <CollectionCard
                                v-for="collection in collections"
                                :key="collection.id"
                                :collection="collection"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
</style>
