<template>
  <Head>
    <title>Todo List - About</title>
  </Head>
  <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <h1 class="text-4xl font-bold text-center text-gray-900 mb-12">About Our Team</h1>
      <transition-group 
        name="fade-slide" 
        tag="div" 
        class="grid grid-cols-1 md:grid-cols-2 gap-8 justify-center"
      >
        <div 
          v-for="creator in creators" 
          :key="creator.id" 
          class="bg-white rounded-lg shadow-lg overflow-hidden transform transition duration-500 hover:scale-105"
        >
          <div class="relative h-64">
            <img
              :src="creator.image"
              :alt="creator.name"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ creator.name }}</h2>
            <p class="text-sm text-indigo-600 font-medium mb-4">{{ creator.role }}</p>
            <p class="text-gray-700 mb-4">{{ creator.bio }}</p>
            <div class="flex space-x-4">
              <a 
                v-for="link in creator.socialLinks" 
                :key="link.name"
                :href="link.url" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="text-gray-600 hover:text-gray-900 transition duration-300 ease-in-out transform hover:scale-110"
              >
                <component :is="link.icon" class="h-6 w-6" />
                <span class="sr-only">{{ link.name }}</span>
              </a>
            </div>
          </div>
        </div>
      </transition-group>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { GithubIcon, LinkedinIcon, TwitterIcon } from 'lucide-vue-next'
import { defineOptions } from 'vue';
import NavLayout from '../Layouts/NavLayout.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({
  layout: NavLayout
})

const creators = ref([
  {
    id: 1,
    name: 'Wendel Rey Salaum',
    role: 'Student',
    image: '/storage/uploads/462582511_1775884056518465_9186066455581054441_n.jpg',
    bio: 'Hi, Im Wendel Rey Salaum',    
    socialLinks: [
      { name: 'GitHub', url: 'https://github.com/deletedgituser/', icon: GithubIcon },
      { name: 'Twitter', url: 'https://twitter.com/', icon: TwitterIcon },
    ],
  },
  {
    id: 2,
    name: 'Cristina Repollo',
    role: 'Student',
    image: '/storage/uploads/467480279_3452704078364510_2742246512889148505_n.jpg',
    bio: 'Hi, Im Cristina Repollo',
    socialLinks: [
      { name: 'GitHub', url: 'https://github.com/tina-cmd/', icon: GithubIcon },
      { name: 'Twitter', url: 'https://twitter.com/', icon: TwitterIcon },
    ],
  },
])
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.5s ease;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(30px);
}
</style>