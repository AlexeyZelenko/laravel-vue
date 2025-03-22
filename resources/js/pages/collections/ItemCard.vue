<template>
    <div class="flip-card w-full h-full" :class="{ flipped: isFlipped }">
        <div class="flip-card-inner">
            <div class="flip-card-front flex flex-col justify-between w-full h-full bg-white rounded-lg overflow-hidden">
                <div class="aspect-w-1 aspect-h-1 overflow-hidden">
                    <div class="bg-gray-600 flex items-center justify-center h-full w-full py-2 min-h-16 text-xl text-gray-400 text-center word-break">
                        {{ item.title }}
                    </div>
                </div>
                <div v-if="item.description" class="flex justify-center text-gray-600 text-ms mb-6 px-4">
                    <p class="break-words whitespace-pre-line text-center word-break">
                        {{ item.description }}
                    </p>
                </div>
                <section class="flex flex-col justify-between items-center px-4 pb-4">
                    <button @click="toggleFlip" class="bg-blue-500 text-white px-2 py-2 rounded-lg m-1 hover:bg-blue-600">
                        Показать перевод
                    </button>
                    <Dialog>
                        <DialogTrigger as-child>
                            <Button variant="destructive">Delete Card</Button>
                        </DialogTrigger>
                        <DialogContent>
                            <form class="space-y-6" @submit="deleteCard">
                                <DialogHeader class="space-y-3">
                                    <DialogTitle>Are you sure you want to delete this card?</DialogTitle>
                                    <DialogDescription>
                                        Once this card is deleted, it will be permanently removed.
                                    </DialogDescription>
                                </DialogHeader>

                                <DialogFooter class="gap-2">
                                    <DialogClose as-child>
                                        <Button variant="secondary" @click="closeModal"> Cancel </Button>
                                    </DialogClose>

                                    <Button variant="destructive" :disabled="form.processing">
                                        <button type="submit">Delete Card</button>
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                    <Dialog :open="successModalOpen" @openChange="setSuccessModalOpen">
                        <DialogContent>
                            <DialogHeader>
                                <DialogTitle>Карточка удалена</DialogTitle>
                            </DialogHeader>
                            <p>Карточка удалена успешно</p>
                            <DialogFooter>
                                <DialogClose as-child>
                                    <Button>Закрыть</Button>
                                </DialogClose>
                            </DialogFooter>
                        </DialogContent>
                    </Dialog>
                </section>
            </div>

            <div class="flip-card-back flex flex-col justify-between items-center w-full h-full bg-blue-100 rounded-lg overflow-hidden w-full">
                <div class="aspect-w-1 aspect-h-1 overflow-hidden w-full">
                    <div class="bg-gray-600 flex items-center justify-center h-full w-full py-2 min-h-16 text-xl text-gray-400 text-center word-break w-full">
                        {{ item.translate_title }}
                    </div>
                </div>
                <div v-if="item.translate_description" class="flex justify-center text-gray-600 text-ms mb-6 px-4">
                    <p class="break-words whitespace-pre-line text-center word-break">
                        {{ item.translate_description }}
                    </p>
                </div>
                <button @click="toggleFlip" class="bg-red-500 text-white px-4 py-2 rounded-lg mt-2 hover:bg-red-600 mb-2">
                    Назад
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps } from "vue";
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

const props = defineProps({
    item: {
        type: Object,
        required: true
    }
});

const isFlipped = ref(false);

const toggleFlip = () => {
    isFlipped.value = !isFlipped.value;
};

const form = useForm({});
const successModalOpen = ref(false)

const deleteCard = () => {
    console.log('Удаление карточки:', props.item);
    form.delete(`/collections/items/${props.item.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            setSuccessModalOpen(true)
            closeModal();
        },
        onError: (errors) => {
            console.error('Ошибка при удалении карточки:', errors);
        },
        onFinish: () => form.reset(),
    });
};

const setSuccessModalOpen = (value) => {
    successModalOpen.value = value;
};

const closeModal = () => {
    form.reset();
};
</script>

<style scoped>
/* Контейнер карточки */
.flip-card {
    perspective: 1000px;
}

/* Внутренний контейнер для анимации */
.flip-card-inner {
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
    transition: transform 0.6s ease-in-out;
}

/* Переворот */
.flipped .flip-card-inner {
    transform: rotateY(180deg);
}

/* Лицевая и обратная стороны */
.flip-card-front,
.flip-card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
}

/* Обратная сторона */
.flip-card-back {
    transform: rotateY(180deg);
}

/* Перенос слов */
.word-break {
    word-break: break-word;
    overflow-wrap: break-word;
    hyphens: auto;
    max-width: 100%;
}
</style>
