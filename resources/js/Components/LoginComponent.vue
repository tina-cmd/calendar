<template>
  <form @submit.prevent="handleLogin" class="space-y-4">
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <div class="mt-1 relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <MailIcon class="h-5 w-5 text-gray-400" />
        </div>
        <input 
          v-model="form.email" 
          type="email" 
          id="email" 
          required 
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          placeholder="you@example.com"
          maxlength="30"
        >
      </div>
    </div>
    <div>
      <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
      <div class="mt-1 relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <LockIcon class="h-5 w-5 text-gray-400" />
        </div>
        <input 
          v-model="form.password" 
          type="password" 
          id="password" 
          required 
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          placeholder="••••••••"
          maxlength="20"
        >
      </div>
    </div>
    <div class="flex items-center justify-between">
      <div class="flex items-center">
        <input 
          v-model="form.rememberMe" 
          id="remember-me" 
          type="checkbox" 
          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
        >
        <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
      </div>
    </div>
    <div>
      <button 
        type="submit" 
        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Sign in
      </button>
    </div>

    <Transition name="modal">
      <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 w-full">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md transform transition-all duration-300 ease-out">
          <h3 class="text-2xl font-semibold text-yellow-800 mb-4">{{ messageTitle }}</h3>
          <p class="mb-4">{{ message }}</p>
          <div class="flex justify-end">
            <button @click="close()"
              class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
              OK
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { MailIcon, LockIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import { onMounted } from 'vue';
import DOMPurify from 'dompurify';

const form = useForm({
  email: '',
  password: '',
  rememberMe: false,
});

const err = ref(null);
const show = ref(false);
const message = ref(null);
const messageTitle = ref(null);

const showWarning = (text, title) => {
  show.value = true;
  message.value = text;
  messageTitle.value = title;
}

const close = () => {
  show.value = false;
  message.value = '';
  messageTitle.value = '';
}

const handleLogin = () => {
  form.post('/login', {
    onSuccess: () => {
      console.log('Login successful');
    },
    onError: (errors) => {
      err.value = errors.email;
      showWarning(err.value, 'Warning');
    },
  });
  form.reset();
};
</script>
