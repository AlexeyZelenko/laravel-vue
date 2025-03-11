<template>
    <Head :title="collection?.name ?? 'Loading...'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Добавить элемент в коллекцию</h1>
            <form @submit.prevent="createItem" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Название элемента</label>
                    <input v-model="form.title" type="text" placeholder="Название элемента" required
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Описание</label>
                    <textarea v-model="form.description" placeholder="Описание"
                              class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                </div>

                <button type="submit" :disabled="form.processing"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    Добавить
                </button>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, useForm, usePage } from "@inertiajs/vue3";
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Collections', href: '/collections' },
    { title: 'Items', href: '/collections/items' },
    { title: 'Create', href: '/collections/items/create' },
];

interface Collection {
    id: number;
    name: string;
    slug: string;
    visibility: string;
    description: string;
}

// Получаем коллекцию, явно указываем тип
const collection = usePage().props.collection as Collection;

const form = useForm({
    title: '',
    description: '',
    image: null,
});

const createItem = async () => {
    console.log(collection);
    if (!collection?.slug) return;
    form.post(`/collections/${collection.slug}/items`);
};
</script>
