<template>

  <Head>
    <title>Todo List</title>
  </Head>
  <div>
    <!-- Navbar -->
    <nav class="bg-indigo-600 text-white shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <!-- Brand Logo -->
            <!-- <a href="#" class="flex items-center text-lg font-semibold">
                <HomeIcon class="w-6 h-6 mr-2" />
                Todo List
              </a> -->
            <div class="flex items-center text-xl font-semibold">
              <img class="w-12 h-12 mr-2" src="/storage/uploads/icons8-calendar-94.png" alt="" srcset="">
              Calendar Todo List
            </div>
          </div>
          <div class="hidden md:flex space-x-4 items-center">
            <!-- Desktop Menu -->
            <Link v-for="item in menuItems" :key="item.name" :href="item.href"
              class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition duration-150 ease-in-out">
            <component :is="item.icon" class="w-5 h-5 mr-2" />
            {{ item.name }}
            </Link>
          </div>
          <!-- User Icon -->
          <div class="hidden md:flex items-center">
            <Link href="/dashboard/account"
              class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition duration-150 ease-in-out">
            <UserIcon class="w-5 h-5 mr-2" />
            Account
            </Link>
            <Link @click.prevent="showLogoutModal = true"
              class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition duration-150 ease-in-out">
            <LogOutIcon class="w-5 h-5 mr-2" />
            </Link>
          </div>
          <!-- Mobile Menu Button -->
          <div class="md:hidden flex items-center">
            <button @click="toggleMenu" class="text-white focus:outline-none">
              <MenuIcon v-if="!isMenuOpen" class="w-6 h-6" />
              <XIcon v-else class="w-6 h-6" />
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu -->
      <Transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
        <div v-if="isMenuOpen" class="md:hidden bg-indigo-500">
          <Link v-for="item in menuItems" :key="item.name" :href="item.href"
            class="flex items-center px-4 py-2 text-white hover:bg-indigo-600 transition duration-150 ease-in-out">
          <component :is="item.icon" class="w-5 h-5 mr-2" />
          {{ item.name }}
          </Link>
          <Link href="/dashboard/account"
            class="flex items-center px-4 py-2 text-white hover:bg-indigo-600 transition duration-150 ease-in-out">
          <UserIcon class="w-5 h-5 mr-2" />
          Account
          </Link>
          <Link @click.prevent="showLogoutModal = true"
            class="flex items-center px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition duration-150 ease-in-out">
          <LogOutIcon class="w-5 h-5 mr-2" />
          Logout
          </Link>
        </div>
      </Transition>
    </nav>

    <!-- Slot for Page Content -->
    <main class="py-8">
      <slot></slot>
    </main>

    <!-- Logout Confirmation Modal -->
    <div v-if="showLogoutModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirm Logout</h2>
        <p class="text-sm text-gray-600 mb-6">Are you sure you want to log out?</p>
        <div class="flex justify-end space-x-4">
          <button @click="confirmLogout"
            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-150 ease-in-out">
            Yes, Logout
          </button>
          <button @click="showLogoutModal = false"
            class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-150 ease-in-out">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import { HomeIcon, InfoIcon, BriefcaseIcon, MailIcon, UserIcon, MenuIcon, XIcon, LogOutIcon } from 'lucide-vue-next';

const isMenuOpen = ref(false);
const showLogoutModal = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const menuItems = [
  { name: 'Dashboard', href: '/dashboard/todolist', icon: HomeIcon },
  { name: 'About', href: '/dashboard/about', icon: InfoIcon },
];

const form = useForm({});

const confirmLogout = () => {
  form.post('/dashboard/logout');
};

</script>

<style scoped>
/* Add any additional custom styles here */
</style>                                                                                  