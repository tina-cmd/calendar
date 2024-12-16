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
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { MailIcon, LockIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import { onMounted } from 'vue';

const form = useForm({
  email: '',
  password: '',
  rememberMe: false,
});

const err = ref(null);

const handleLogin = () => {
  form.post('/login', {
    onSuccess: () => {
      console.log('Login successful');
    },
    onError: (errors) => {
      err.value = errors.email;
      alert(err.value);
    },
  });
  form.reset();
};
</script>
