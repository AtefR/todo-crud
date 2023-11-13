<script setup>
import {ref, reactive, nextTick} from 'vue';
import DialogModal from './DialogModal.vue';
import InputError from './InputError.vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import TextInput from './TextInput.vue';
import InputLabel from "@/Components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";

const emit = defineEmits(['edited']);

const props = defineProps({
    todo: Object,
    button: {
        type: String,
        default: 'Confirm',
    },
});

const editingTodo = ref(false);

const form = reactive({
    title: props.todo.title,
    description: props.todo.description,
    status: props.todo.status,
    errors: [],
    processing: false,
});

const startEditing = () => {
    editingTodo.value = true;
};

const submitEdit = () => {
    form.processing = true;

    axios.patch(route('todo.update', props.todo), {
        title: form.title,
        status: form.status,
        description: form.description,
    }).then(() => {
        form.processing = false;

        closeModal();
        nextTick().then(() => emit('edited'));

    }).catch(error => {
        form.processing = false;
        console.log(error)
        form.errors = error.response.data.errors;
    });
};

const closeModal = () => {
    editingTodo.value = false;
    form.errors = [];
};
</script>

<template>
    <span>
        <span @click="startEditing">
            <slot/>
        </span>

        <DialogModal :show="editingTodo" @close="closeModal">
            <template #title>
                Edit Todo
            </template>

            <template #content>
                <div class="mt-4">
                    <InputLabel for="title" value="Title"/>

                    <TextInput
                        ref="title"
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder="Title"
                        autocomplete="current-title"
                    />

                    <InputError v-for="error in form.errors['title']" :message="error" class="mt-2"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="status" value="Status"/>

                    <select
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-3/4"
                        v-model="form.status"
                    >
                      <option value="cancelled">cancelled</option>
                      <option value="inprogress">inprogress</option>
                      <option value="completed">completed</option>
                      <option value="hold">hold</option>
                    </select>

                    <InputError v-for="error in form.errors['status']" :message="error" class="mt-2"/>
                </div>

                <div class="mt-4">
                    <InputLabel for="description" value="Description"/>

                    <textarea
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-3/4 h-32"
                        placeholder="Description"
                        v-model="form.description"
                    />

                    <InputError v-for="error in form.errors['description']" :message="error" class="mt-2"/>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="submitEdit"
                >
                    Submit
                </PrimaryButton>
            </template>
        </DialogModal>
    </span>
</template>
