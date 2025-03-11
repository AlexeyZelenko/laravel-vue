<template>
    <Head :title="collection?.name ?? 'Loading...'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-2xl mx-auto p-6 bg-white shadow-md rounded-lg">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Добавить карточку в коллекцию</h1>
            <form @submit.prevent="createItem" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Слово</label>
                    <input v-model="form.title" type="text" placeholder="Иностранное слово" required
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Перевод слова</label>
                    <input v-model="form.translate_title" type="text" placeholder="Перевод иностранного слова" required
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Предложение</label>
                    <textarea v-model="form.description" placeholder="Иностранное предложение"
                              class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Перевод Предложения</label>
                    <textarea
                        v-model="form.translate_description"
                        placeholder="Перевод предложения"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-black"></textarea>
                </div>
                <div v-if="Object.keys(errors).length" class="text-red-500 text-sm">
                    <p v-for="(messages, field) in errors" :key="field">
                        <strong>{{ field }}:</strong>
                        <span v-for="(message, index) in messages" :key="index">{{ message }}</span>
                    </p>
                </div>


                <button type="submit" :disabled="form.processing"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 text-black">
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
    translate_title: string;
    translate_description: string;
}

// Получаем коллекцию, явно указываем тип
const collection = usePage().props.collection as Collection;

const errors = ref<Record<string, string[]>>({});

const form = useForm({
    title: '',
    description: '',
    image: null,
    translate_title: '',
    translate_description: '',
});

const createItem = async () => {
    console.log('Отправка формы:', form);

    if (!collection?.slug) {
        console.error('Ошибка: отсутствует collection.slug');
        return;
    }

    form.post(`/collections/${collection.slug}/items`, {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Элемент успешно создан!');
            form.reset(); // Очистка формы
            errors.value = {}; // Очистка ошибок
        },
        onError: (errorData) => {
            console.error('Ошибки валидации:', errorData);
            errors.value = errorData;
        }
    });
};

</script>
