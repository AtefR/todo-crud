<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import EditTodo from "@/Components/EditTodo.vue";
import {onMounted, ref} from "vue";
import DangerButton from "@/Components/DangerButton.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    todos: Array,
});

onMounted(() => {
    props.todos.forEach((todo) => {
        window.Echo.private('todo.' + todo.id)
            .listen('.todo-created', (e) => {
                props.todos.unshift(e.todo)
            })
            .listen('.todo-updated', (e) => {
                const todoIndex = props.todos.findIndex(x => x.id === e.todo.id)

                props.todos[todoIndex] = e.todo
            })
            .listen('.todo-removed', (e) => {
                const todoIndex = props.todos.findIndex(x => x.id === todo.id)

                props.todos.splice(todoIndex, 1);

                Echo.leave('todo.' + todo.id);
            })
    })
})

const todoDeletionProcessing = ref(false)
const todoBeingDeleted = ref(null);

const confirmTodoDeletion = (token) => {
    todoBeingDeleted.value = token;
};

const deleteApiToken = () => {
    todoDeletionProcessing.value = true;

    axios.delete(route('todo.destroy', todoBeingDeleted.value)).then(() => {
        todoDeletionProcessing.value = false

        todoBeingDeleted.value = null
    }).catch(error => {
        console.log(error)
    });
};

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Current Tasks
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="relative rounded-xl overflow-auto">
                        <div class="shadow-sm overflow-hidden my-8">
                            <table class="border-collapse table-auto w-full text-lg">
                                <thead>
                                <tr>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                        #
                                    </th>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                        User
                                    </th>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                        Title
                                    </th>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                        Description
                                    </th>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                        Status
                                    </th>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                        Created At
                                    </th>
                                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                        &nbsp;
                                    </th>
                                </tr>
                                </thead>

                                <!-- Table Body -->
                                <tbody>

                                <tr v-for="todo in todos">
                                    <td class="border-b border-slate-100 dark:border-neutral-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <div>{{ todo.id }}</div>
                                    </td>

                                    <td class="border-b border-slate-100 dark:border-neutral-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <div>{{ todo.user.name }}</div>
                                    </td>

                                    <td class="border-b border-slate-100 dark:border-neutral-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <div>{{ todo.title }}</div>
                                    </td>


                                    <td class="border-b border-slate-100 dark:border-neutral-700 p-4 pl-8 text-slate-500 dark:text-slate-400 max-w-xl">
                                        <div>{{ todo.description }}</div>
                                    </td>

                                    <td class="border-b border-slate-100 dark:border-neutral-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <div :class="`status-${todo.status}`">{{ todo.status }}</div>
                                    </td>

                                    <td class="border-b border-slate-100 dark:border-neutral-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <div>{{ $dayjs(todo.created_at).fromNow() }}</div>
                                    </td>

                                    <td class="inline-flex gap-1 border-b border-slate-100 dark:border-neutral-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <EditTodo :todo="todo">
                                            <button
                                                class="middle none relative h-10 max-h-[40px] w-10 max-w-[40px] rounded-lg bg-indigo-500 text-center font-sans text-xs font-medium uppercase text-white shadow-md shadow-indigo-500/20 transition-all hover:shadow-lg hover:shadow-indigo-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                                type="button"
                                                data-ripple-light="true"
                                            >
                                            <span
                                                class="absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 transform">
                                                <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M21.1213 2.70705C19.9497 1.53548 18.0503 1.53547 16.8787 2.70705L15.1989 4.38685L7.29289 12.2928C7.16473 12.421 7.07382 12.5816 7.02986 12.7574L6.02986 16.7574C5.94466 17.0982 6.04451 17.4587 6.29289 17.707C6.54127 17.9554 6.90176 18.0553 7.24254 17.9701L11.2425 16.9701C11.4184 16.9261 11.5789 16.8352 11.7071 16.707L19.5556 8.85857L21.2929 7.12126C22.4645 5.94969 22.4645 4.05019 21.2929 2.87862L21.1213 2.70705ZM18.2929 4.12126C18.6834 3.73074 19.3166 3.73074 19.7071 4.12126L19.8787 4.29283C20.2692 4.68336 20.2692 5.31653 19.8787 5.70705L18.8622 6.72357L17.3068 5.10738L18.2929 4.12126ZM15.8923 6.52185L17.4477 8.13804L10.4888 15.097L8.37437 15.6256L8.90296 13.5112L15.8923 6.52185ZM4 7.99994C4 7.44766 4.44772 6.99994 5 6.99994H10C10.5523 6.99994 11 6.55223 11 5.99994C11 5.44766 10.5523 4.99994 10 4.99994H5C3.34315 4.99994 2 6.34309 2 7.99994V18.9999C2 20.6568 3.34315 21.9999 5 21.9999H16C17.6569 21.9999 19 20.6568 19 18.9999V13.9999C19 13.4477 18.5523 12.9999 18 12.9999C17.4477 12.9999 17 13.4477 17 13.9999V18.9999C17 19.5522 16.5523 19.9999 16 19.9999H5C4.44772 19.9999 4 19.5522 4 18.9999V7.99994Z"
                                                      fill="currentColor"/>
                                                </svg>
                                            </span>
                                            </button>
                                        </EditTodo>

                                        <button
                                            class="middle none relative h-10 max-h-[40px] w-10 max-w-[40px] rounded-lg bg-red-500 text-center font-sans text-xs font-medium uppercase text-white shadow-md shadow-red-500/20 transition-all hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                                            type="button"
                                            data-ripple-light="true"
                                            @click="confirmTodoDeletion(todo)"
                                        >
                                            <span
                                                class="absolute top-1/2 left-1/2 -translate-y-1/2 -translate-x-1/2 transform">
                                              <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none"
                                                   xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M3 3L21 21M18 6L17.6 12M17.2498 17.2527L17.1991 18.0129C17.129 19.065 17.0939 19.5911 16.8667 19.99C16.6666 20.3412 16.3648 20.6235 16.0011 20.7998C15.588 21 15.0607 21 14.0062 21H9.99377C8.93927 21 8.41202 21 7.99889 20.7998C7.63517 20.6235 7.33339 20.3412 7.13332 19.99C6.90607 19.5911 6.871 19.065 6.80086 18.0129L6 6H4M16 6L15.4559 4.36754C15.1837 3.55086 14.4194 3 13.5585 3H10.4416C9.94243 3 9.47576 3.18519 9.11865 3.5M11.6133 6H20M14 14V17M10 10V17"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round"/>
                                                </svg>
                                            </span>
                                        </button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <ConfirmationModal :show="todoBeingDeleted != null" @close="todoBeingDeleted = null">
                <template #title>
                    Delete Todo
                </template>

                <template #content>
                    Are you sure you would like to delete this Todo?
                </template>

                <template #footer>
                    <SecondaryButton @click="todoBeingDeleted = null">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': todoDeletionProcessing }"
                        :disabled="todoDeletionProcessing"
                        @click="deleteApiToken"
                    >
                        Delete
                    </DangerButton>
                </template>
            </ConfirmationModal>
        </div>
    </AppLayout>
</template>

<style scoped>
.status-cancelled {
    @apply text-red-500;
}

.status-inprogress {
    @apply text-blue-500;
}

.status-completed {
    @apply text-green-500;
}

.status-hold {
    @apply text-indigo-900;
}
</style>
