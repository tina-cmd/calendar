<template>
  <Head>
    <title>Todo List - About</title>
  </Head>
  <div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100 py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto text-center">
      <h1 class="text-4xl font-bold text-indigo-900 mb-12 animate-fade-in">About Our Team</h1>
      <TransitionGroup 
        name="fade-slide" 
        tag="div" 
        class="grid grid-cols-1 md:grid-cols-2 gap-12 justify-center"
      >
        <div 
          v-for="creator in creators" 
          :key="creator.id" 
          class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-500 hover:scale-105"
        >
          <div class="relative h-64">
            <img
              :src="creator.image"
              :alt="creator.name"
              class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900 to-transparent opacity-70"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
              <h2 class="text-2xl font-bold mb-1">{{ creator.name }}</h2>
              <p class="text-sm text-indigo-200 font-medium">{{ creator.role }}</p>
            </div>
          </div>
          <div class="p-6">
            <p class="text-gray-700 mb-4">{{ creator.bio }}</p>
            <div class="flex justify-center space-x-4">
              <a 
                v-for="link in creator.socialLinks" 
                :key="link.name"
                :href="link.url" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="text-indigo-600 hover:text-indigo-800 transition duration-300 ease-in-out transform hover:scale-110"
              >
                <component :is="link.icon" class="h-6 w-6" />
                <span class="sr-only">{{ link.name }}</span>
              </a>
            </div>
          </div>
        </div>
      </TransitionGroup>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { GithubIcon, TwitterIcon } from 'lucide-vue-next'
import { defineOptions } from 'vue'
import NavLayout from '../Layouts/NavLayout.vue'
import { Head } from '@inertiajs/vue3'

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

onMounted(() => {
  creators.value.forEach((creator, index) => {
    setTimeout(() => {
      creator.show = true
    }, index * 200)
  })
})
</script>

<style scoped>
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.animate-fade-in {
  animation: fadeIn 1s ease-out;
}

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

