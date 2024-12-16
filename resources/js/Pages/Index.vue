<template>
    <Head>
      <title>Todo List</title>
    </Head>
    <div class="min-h-screen bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Todo Calendar</h1>
        
        <div class="flex justify-center space-x-4 mb-8">
          <button 
            @click="currentComponent = 'login'" 
            :class="['flex items-center px-4 py-2 rounded-full transition duration-300 ease-in-out', 
                     currentComponent === 'login' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300']"
          >
            <UserIcon class="w-5 h-5 mr-2" />
            Login
          </button>
          <button 
            @click="currentComponent = 'register'" 
            :class="['flex items-center px-4 py-2 rounded-full transition duration-300 ease-in-out', 
                     currentComponent === 'register' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300']"
          >
            <UserPlusIcon class="w-5 h-5 mr-2" />
            Register
          </button>
        </div>
        
        <Transition name="fade" mode="out-in">
            <template v-if="currentComponent === 'login'">
                <LoginComponent />
            </template>
            <template v-else>
                <RegisterComponent />
            </template>
        </Transition>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { UserIcon, UserPlusIcon } from 'lucide-vue-next';
  import LoginComponent from '../Components/LoginComponent.vue';
  import RegisterComponent from '../Components/RegisterComponent.vue';
  import { onMounted, defineProps } from 'vue';
  import { Head } from '@inertiajs/vue3';

  
  const props = defineProps({ 
    warning: {
      type: String,
      default: null,
    },
    success: {
      type: String,
      default: null,
    }
  });

  const currentComponent = ref('login');

  onMounted(() => {
    // if(props.warning != null) {
    //   alert(props.warning);
    // }else if(props.success != null) {
    //   alert(props.success);
    // }
  });

  </script>
  
  <style scoped>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.3s ease;
  }
  
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
  </style>