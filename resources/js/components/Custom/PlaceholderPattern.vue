<script setup lang="ts">
import { computed } from 'vue';

const patternId = computed(() => `pattern-${Math.random().toString(36).substring(2, 9)}`);

const benefits = [
    {
        title: 'Active Recall',
        description: 'Strengthen your memory by actively retrieving words and their meanings.',
    },
    {
        title: 'Improved Retention',
        description: 'Repeated exposure reinforces your understanding and helps you retain information longer.',
    },
    {
        title: 'Enhanced Fluency',
        description: 'Build your vocabulary and improve your overall fluency in the target language.',
    },
    {
        title: 'Visual Learning',
        description: 'Flashcards are ideal for visual learners who benefit from seeing words and associated images or definitions.',
    },
];
</script>

<template>
    <div class="relative w-full p-4 sm:p-6 bg-gray-800 rounded-lg shadow-md text-white">
        <svg class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" fill="none">
            <defs>
                <pattern :id="patternId" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                    <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>
                </pattern>
            </defs>
            <rect stroke="none" :fill="`url(#${patternId})`" width="100%" height="100%"></rect>
        </svg>

        <main class="text-gray-200">
            <ul class="list-disc list-inside space-y-2 text-sm sm:text-base">
                <li v-for="(benefit, index) in benefits" :key="index">
                    <strong>{{ benefit.title }}:</strong> {{ benefit.description }}
                </li>
            </ul>
        </main>
    </div>
</template>
