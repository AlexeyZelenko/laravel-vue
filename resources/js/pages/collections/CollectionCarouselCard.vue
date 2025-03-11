<script setup lang="ts">
import type { CarouselApi } from '@/components/ui/carousel'
import { Card, CardContent } from '@/components/ui/card'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import { watchOnce } from '@vueuse/core'
import { ref, defineProps } from 'vue'
import CollectionItemCard from './ItemCard.vue'

interface Item {
    id: number;
    name: string;
    slug: string;
    visibility: string;
    description: string;
    translate_title: string;
    translate_description: string;
}

defineProps({
    items: {
        type: Array as () => Item[],
        required: true
    }
})

const api = ref<CarouselApi>()
const totalCount = ref(0)
const current = ref(0)

function setApi(val: CarouselApi) {
    api.value = val
}

watchOnce(api, (api) => {
    if (!api)
        return

    totalCount.value = api.scrollSnapList().length
    current.value = api.selectedScrollSnap() + 1

    api.on('select', () => {
        current.value = api.selectedScrollSnap() + 1
    })
})
</script>

<template>
    <div class="w-full sm:w-auto">
        <Carousel class="relative w-full max-w-xs" @init-api="setApi">
            <CarouselContent>
                <CarouselItem v-for="(_, index) in items" :key="index">
                    <div class="p-1">
                        <Card>
                            <CardContent class="flex aspect-square items-center justify-center p-2">
                                <CollectionItemCard :item="items[index]" />
                            </CardContent>
                        </Card>
                    </div>
                </CarouselItem>
            </CarouselContent>
            <CarouselPrevious />
            <CarouselNext />
        </Carousel>

        <div class="py-2 text-center text-sm text-muted-foreground">
            Slide {{ current }} of {{ totalCount }}
        </div>
    </div>
</template>
