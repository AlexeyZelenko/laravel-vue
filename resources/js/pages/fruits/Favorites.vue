<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface Fruit {
    id: number;
    name: string;
    family: string;
    nutritions: {
        calories: number;
        fat: number;
        sugar: number;
        carbohydrates: number;
        protein: number;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Fruits', href: '/fruits' },
    { title: 'Favorites', href: '/fruits/favorites' },
];

const fruits = ref<Fruit[]>([]);
const loading = ref(true);
const favorites = ref<Set<number>>(new Set());

const fetchFruits = async () => {
    try {
        const response = await fetch('https://www.fruityvice.com/api/fruit/all');
        const data = await response.json();
        fruits.value = data.filter(fruit => favorites.value.has(fruit.id));
    } catch (error) {
        console.error('Error fetching fruits:', error);
    } finally {
        loading.value = false;
    }
};

const removeFavorite = (fruitId: number) => {
    favorites.value.delete(fruitId);
    localStorage.setItem('fruitFavorites', JSON.stringify(Array.from(favorites.value)));
    fruits.value = fruits.value.filter(fruit => favorites.value.has(fruit.id));
};

onMounted(() => {
    const savedFavorites = localStorage.getItem('fruitFavorites');
    if (savedFavorites) {
        favorites.value = new Set(JSON.parse(savedFavorites));
    }
    fetchFruits();
});
</script>

<template>
    <Head title="Favorite Fruits" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-6">Favorite Fruits</h1>

            <div v-if="loading" class="text-center py-8">
                Loading favorites...
            </div>
            <div v-else-if="fruits.length === 0" class="text-center py-8">
                No favorite fruits yet.
            </div>
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <Card v-for="fruit in fruits" :key="fruit.id">
                    <CardHeader>
                        <CardTitle class="flex justify-between items-center">
                            <span>{{ fruit.name }}</span>
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="removeFavorite(fruit.id)"
                                class="text-yellow-500"
                                style="font-size: 18px"
                            >
                                ★
                            </Button>
                        </CardTitle>
                        <Link
                            :href="`/fruits/family/${fruit.family}`"
                            class="text-sm text-blue-600 hover:underline"
                        >
                            {{ fruit.family }}
                        </Link>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>Calories:</div>
                                <div>{{ fruit.nutritions.calories }}</div>
                                <div>Fat:</div>
                                <div>{{ fruit.nutritions.fat }}g</div>
                                <div>Sugar:</div>
                                <div>{{ fruit.nutritions.sugar }}g</div>
                                <div>Carbohydrates:</div>
                                <div>{{ fruit.nutritions.carbohydrates }}g</div>
                                <div>Protein:</div>
                                <div>{{ fruit.nutritions.protein }}g</div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
```
