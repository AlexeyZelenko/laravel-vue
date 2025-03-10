<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Checkbox } from '@/components/ui/checkbox'
import Textarea from '@/components/ui/textarea/index.vue'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Collections',
        href: '/collections',
    },
    {
        title: 'Create Collection',
        href: '/collections/create',
    },
];

defineProps<{
    errors?: Record<string, string>;
}>();

// Используем useForm из Inertia для управления формой и отправки данных
const form = useForm({
    name: '',
    description: '',
    visibility: true,
    tags: ''
});

const handleSubmit = () => {
    // Отправляем данные на сервер используя post метод
    form.post('/collections', {
        preserveScroll: true
    });
};

const clearForm = () => {
    form.reset();
};
</script>

<template>
    <Head title="Create Collection" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <main class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <form @submit.prevent="handleSubmit">
                    <div class="flex flex-col gap-4 p-4">
                        <div class="flex flex-col gap-4">
                            <Label for="name">Name</Label>
                            <Input id="name" type="text" v-model="form.name" />
                            <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                        </div>
                        <div class="flex flex-col gap-4">
                            <Label for="description">Description</Label>
                            <Textarea id="description" v-model="form.description" />
                            <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                        </div>

                        <div class="flex items-center gap-2">
                            <Checkbox id="visibility" v-model="form.visibility"/>
                            <label
                                for="visibility"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            >
                                Public
                            </label>
                        </div>

                        <div class="flex flex-col gap-4">
                            <Label for="tags">Tags</Label>
                            <Input id="tags" type="text" v-model="form.tags" />
                            <div v-if="form.errors.tags" class="text-red-500 text-sm mt-1">{{ form.errors.tags }}</div>
                        </div>
                        <div class="flex gap-4">
                            <Button
                                type="submit"
                                class="btn text-blue-700 dark:hover:text-blue-600"
                                :disabled="form.processing"
                            >
                                {{ form.processing ? 'Creating...' : 'Create Collection' }}
                            </Button>
                            <Button @click="clearForm" type="button">Clear</Button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </AppLayout>
</template>
