<template>
    <Transition name="modal">
        <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 w-full">
            <div
                class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md transform transition-all duration-300 ease-out">
                <h3 class="text-2xl font-semibold text-yellow-800 mb-4">Warning</h3>
                <p class="mb-4">{{ message }}</p>
                <div class="flex justify-end">
                    <button @click="close"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </Transition>


</template>

<script setup>
const props = defineProps({
    show: Boolean,
    message: {
        type: String,
        default: ''
    }
});
const emits = defineEmits(['close']);
const close = () => emits('close');
</script>