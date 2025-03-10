<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import {Button} from "@/components/ui/button";

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
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <main class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <ul class="flex flex-col gap-4 p-4">
                    <li
                        v-for="collection in collections"
                        :key="collection.id"
                        class="flex flex-col gap-1 p-4 border border-sidebar-border/70 dark:border-sidebar-border rounded-xl">
                        <strong>{{ collection.name }}</strong> ({{ collection.visibility }})
                        <div>{{ collection.description }}</div>
                        <button>
                            <a :href="`/collections/${collection.slug}`">details</a>
                        </button>
                    </li>
                </ul>
            </div>
        </main>
    </AppLayout>
</template>

<style scoped>
</style>
