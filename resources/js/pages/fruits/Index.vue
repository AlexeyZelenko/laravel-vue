<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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

interface Filters {
    calories: { min: number; max: number };
    fat: { min: number; max: number };
    sugar: { min: number; max: number };
    carbohydrates: { min: number; max: number };
    protein: { min: number; max: number };
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Fruits', href: '/fruits' },
];

const fruits = ref<Fruit[]>([]);
const loading = ref(true);
const filters = ref<Filters>({
    calories: { min: 0, max: 100 },
    fat: { min: 0, max: 10 },
    sugar: { min: 0, max: 20 },
    carbohydrates: { min: 0, max: 30 },
    protein: { min: 0, max: 10 },
});

const favorites = ref<Set<number>>(new Set());

// Load favorites from localStorage
onMounted(() => {
    const savedFavorites = localStorage.getItem('fruitFavorites');
    if (savedFavorites) {
        favorites.value = new Set(JSON.parse(savedFavorites));
    }
});

// Save favorites to localStorage whenever it changes
watch(favorites, (newFavorites) => {
    localStorage.setItem('fruitFavorites', JSON.stringify(Array.from(newFavorites)));
}, { deep: true });

const toggleFavorite = (fruitId: number) => {
    if (favorites.value.has(fruitId)) {
        favorites.value.delete(fruitId);
    } else {
        favorites.value.add(fruitId);
    }
};

const filteredFruits = computed(() => {
    return fruits.value.filter(fruit => {
        const { nutritions } = fruit;
        return (
            nutritions.calories >= filters.value.calories.min &&
            nutritions.calories <= filters.value.calories.max &&
            nutritions.fat >= filters.value.fat.min &&
            nutritions.fat <= filters.value.fat.max &&
            nutritions.sugar >= filters.value.sugar.min &&
            nutritions.sugar <= filters.value.sugar.max &&
            nutritions.carbohydrates >= filters.value.carbohydrates.min &&
            nutritions.carbohydrates <= filters.value.carbohydrates.max &&
            nutritions.protein >= filters.value.protein.min &&
            nutritions.protein <= filters.value.protein.max
        );
    });
});

const fetchFruits = async () => {
    try {
        const response = await fetch('https://www.fruityvice.com/api/fruit/all');
        const data = await response.json();
        fruits.value = data;
    } catch (error) {
        console.error('Error fetching fruits:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchFruits();
});
</script>

<template>
    <Head title="Fruits Catalog" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto p-4">
            <!-- Filters -->
            <Card class="mb-6">
                <CardHeader>
                    <CardTitle>Filters</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="(filter, key) in filters" :key="key" class="space-y-2">
                            <Label :for="`${key}-min`">{{ key.charAt(0).toUpperCase() + key.slice(1) }}</Label>
                            <div class="flex gap-2 items-center">
                                <Input
                                    :id="`${key}-min`"
                                    type="number"
                                    v-model="filter.min"
                                    :placeholder="`Min ${key}`"
                                    class="w-24"
                                />
                                <span>to</span>
                                <Input
                                    :id="`${key}-max`"
                                    type="number"
                                    v-model="filter.max"
                                    :placeholder="`Max ${key}`"
                                    class="w-24"
                                />
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Fruits Grid -->
            <div v-if="loading" class="text-center py-8">
                Loading fruits...
            </div>
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <Card v-for="fruit in filteredFruits" :key="fruit.id">
                    <CardHeader>
                        <CardTitle class="flex justify-between items-center">
                            <span>{{ fruit.name }}</span>
                            <Button
                                variant="ghost"
                                size="sm"
                                @click="toggleFavorite(fruit.id)"
                                :class="{ 'text-yellow-500': favorites.has(fruit.id) }"
                                style="font-size: 18px"
                            >
                                ★
                            </Button>
                        </CardTitle>
                        <Link
                            :href="route('fruits.family', { family: fruit.family})"
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
