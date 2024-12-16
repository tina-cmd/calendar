<template>
  <Head>
    <title>Todo List - About</title>
  </Head>
  <div class="min-h-screen bg-gradient-to-br from-cyan-50 to-blue-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
      <div class="bg-white shadow-2xl rounded-3xl overflow-hidden">
        <div class="md:flex">
          <div class="md:flex-shrink-0 bg-gradient-to-br from-cyan-500 to-blue-600 p-8 flex flex-col items-center justify-center text-wrap">
              <div class="relative group">
                  <div class="absolute inset-0 rounded-full bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  </div>
              </div>
              <h2 class="mt-6 w-full md:w-56 text-2xl font-bold text-center text-white text-balance break-words">{{ user.name }}</h2>
              <p class="mt-1 w-full md: text-center text-cyan-200 text-balance break-words">{{ user.email }}</p>
          </div>
          <div class="p-8 md:p-12 flex-grow">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Account Settings</h1>
            <form @submit.prevent="saveChanges" class="space-y-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                  id="name"
                  v-model="user.name"
                  type="text"
                  required
                  maxlength="20"
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 break-words"
                />
              </div>
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                  id="email"
                  v-model="user.email"
                  type="email"
                  required
                  maxlength="20"
                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 break-words"
                />
              </div>
              <div class="flex items-center justify-between space-x-4">
                <button
                  type="submit"
                  class="flex-grow bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-4 py-2 rounded-md hover:from-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors duration-300"
                >
                  <SaveIcon class="w-5 h-5 mr-2 inline" />
                  Save Changes
                </button>
                <button
                  type="button"
                  @click="openDeleteModal"
                  class="flex-grow bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-300"
                >
                  <TrashIcon class="w-5 h-5 mr-2 inline" />
                  Delete Account
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Account Modal -->
    <TransitionRoot appear :show="isDeleteModalOpen" as="template">
      <Dialog as="div" @close="closeDeleteModal" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Delete Account
                </DialogTitle>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete your account? This action cannot be undone.
                  </p>
                </div>

                <div class="mt-4 flex justify-end space-x-4">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                    @click="closeDeleteModal"
                  >
                    Cancel
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                    @click="deleteAccount"
                  >
                    Delete
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { UserIcon, SaveIcon, TrashIcon, CameraIcon } from 'lucide-vue-next';
import NavLayout from '../Layouts/NavLayout.vue';
import { usePage, useForm, Head } from '@inertiajs/vue3';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';

defineOptions({
  layout: NavLayout
});

const { props } = usePage();

const user = useForm({
  name: props.auth.user.name,
  email: props.auth.user.email,
});

const isDeleteModalOpen = ref(false);

const sanitizeInput = (input) => {
  return input.replace(/<\/?[^>]+(>|$)/g, ""); // Basic XSS sanitization (HTML tags removal)
};

const saveChanges = () => {
  user.name = sanitizeInput(user.name); // Sanitize name before submission
  user.email = sanitizeInput(user.email); // Sanitize email before submission

  user.post('/account/update', {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      alert('Changes saved successfully!');
    },
  });
};

const openDeleteModal = () => {
  isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
  isDeleteModalOpen.value = false;
};

const deleteAccount = () => {
  const form = useForm({});

  form.delete('/account/delete');
  // Here you would typically make an API call to delete the user account
  console.log('Deleting account');
  alert('Account deleted successfully!');
  closeDeleteModal();
};
</script>
