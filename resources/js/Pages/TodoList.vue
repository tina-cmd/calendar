<template>

  <Head>
    <title>Todo List - Dashboard</title>
  </Head>
  <div class="container mx-auto p-4 md:pt-0 bg-gray-100 min-h-screen">
    <h1 class="text-4xl font-bold mb-8 text-center md:mt-0 mt-1 text-indigo-800">Todo Calendar</h1>

    <!-- Calendar -->
    <div class="bg-white rounded-lg shadow-lg px-2 md:p-6 md:px-12 mx-0 md:mx-24 mb-8 pt-4">
      <div class="flex justify-between items-center mb-6">
        <button @click="previousMonth"
          class="bg-indigo-500 text-white px-4 md:px-4 py-2 rounded-full hover:bg-indigo-600 transition duration-300 ease-in-out transform hover:scale-105">
          <ChevronLeftIcon class="w-5 h-5" />
        </button>
        <h2 class="text-xl md:text-2xl font-semibold text-indigo-800">{{ currentMonthYear }}</h2>
        <button @click="nextMonth"
          class="bg-indigo-500 text-white px-4 md:px-4 py-2 rounded-full hover:bg-indigo-600 transition duration-300 ease-in-out transform hover:scale-105">
          <ChevronRightIcon class="w-5 h-5" />
        </button>
      </div>
      <TransitionGroup name="calendar" tag="div" class="grid grid-cols-7 gap-4">
        <div v-for="day in daysOfWeek" :key="day" class="text-center font-bold text-indigo-600 md:py-2 py-4">
          {{ day }}
        </div>
        <div v-for="date in calendarDates" :key="date.toISOString()" @click="openModal(date)"
          class="border rounded-lg md:p-2 md:py-2 py-4 text-center cursor-pointer hover:bg-indigo-50 transition duration-300 ease-in-out relative transform hover:scale-125"
          :class="{
            'border-blue-600 hover:bg-indigo-50': isToday(date),
            'bg-indigo-100': isSelectedDate(date),
            'opacity-50': !isSameMonth(date, currentDate),
            'bg-gray-200': expiredDate(date), 
          }">
          <span :class="{
            'text-indigo-800 font-semibold': isSameMonth(date, currentDate),
            'text-indigo-800': isToday(date),
            'text-indigo-300': expiredDate(date),
          }">
            {{ date.getDate() }}
          </span>
          <div class="absolute top-1 right-1 w-2 h-2 rounded-full" :class="getTodoStatusColor(date)"></div>
        </div>
      </TransitionGroup>
    </div>

    <!-- Todo Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 w-full">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md transform transition-all duration-300 ease-out"
          :class="{ 'scale-100 opacity-100': showModal, 'scale-95 opacity-0': !showModal }">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-semibold text-indigo-800">
              Todos for {{ selectedDate?.toDateString() }}
            </h3>
            <button @click="closeModal"
              class="text-gray-500 hover:text-gray-700 transition duration-300 ease-in-out transform hover:scale-110">
              <XIcon class="w-6 h-6" />
            </button>
          </div>

          <div class="mb-4">
            <div class="flex flex-col space-y-2">
              <input v-model="newTodoText" placeholder="Add a new todo"
                class="shadow appearance-none border rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out" />
              <div class="flex items-center space-x-2">
                <input v-model="newTodoTime" type="time"
                  class="shadow appearance-none border rounded-lg py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out" />
                <button @click="addTodo"
                  class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-300 ease-in-out transform hover:scale-105">
                  <PlusIcon class="w-5 h-5" />
                </button>
              </div>
            </div>
          </div>

          <TransitionGroup name="list" tag="ul" class="space-y-2">
            <li v-for="todo in todosForSelectedDate" :key="todo.id"
              class="rounded-lg p-3 flex items-center justify-between transform transition duration-300 ease-in-out hover:shadow-md"
              :class="getTodoBackgroundClass(todo)">
              <div class="break-words w-3/4">
                <span :class="{
                  'line-through text-gray-500': todo.status === 'completed' || todo.status === 'expired',
                  'text-gray-500': todo.status === 'expired'
                }">
                  {{ todo.task_name }}
                </span>
                <span v-if="todo.status === 'expired' || todo.status === 'completed'" class="text-gray-500"> {{todo.status === 'expired'? ' expired':' completed'}}</span><br>
                <span class="text-sm text-gray-500">Due time: {{ formatTime(todo.due_time) }}</span>
              </div>
              <div>
                <button @click="toggleTodo(todo)"
                  class="text-indigo-600 hover:text-indigo-800 mr-2 transition duration-300 ease-in-out transform hover:scale-110">
                  <CheckIcon v-if="todo.status === 'completed'" class="w-5 h-5" />
                  <XIcon v-if="todo.status === 'expired'" class="w-5 h-5" />
                  <MinusCircleIcon v-if="todo.status === 'pending'" class="w-5 h-5" />
                </button>
                <button @click="openEditModal(todo)"
                  class="text-yellow-500 hover:text-yellow-700 mr-2 transition duration-300 ease-in-out transform hover:scale-110">
                  <PencilIcon class="w-5 h-5" />
                </button>
                <button @click="openDeleteModal(todo)"
                  class="text-red-500 hover:text-red-700 transition duration-300 ease-in-out transform hover:scale-110">
                  <TrashIcon class="w-5 h-5" />
                </button>
              </div>
            </li>
          </TransitionGroup>
        </div>
      </div>
    </Transition>

    <!-- Edit Modal -->
    <Transition name="modal">
      <div v-if="showEditModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 w-full">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md transform transition-all duration-300 ease-out"
          :class="{ 'scale-100 opacity-100': showEditModal, 'scale-95 opacity-0': !showEditModal }">
          <h3 class="text-2xl font-semibold text-indigo-800 mb-4">Edit Todo</h3>
          <input v-model="editTodoText"
            class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-4 transition duration-300 ease-in-out" />
          <input v-model="editTodoTime" type="time"
            class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-4 transition duration-300 ease-in-out" />
          <div class="flex justify-end space-x-2">
            <button @click="closeEditModal"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
              Cancel
            </button>
            <button @click="confirmEdit"
              class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
              Save
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Delete Confirmation Modal -->
    <Transition name="modal">
      <div v-if="showDeleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 w-full">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md transform transition-all duration-300 ease-out"
          :class="{ 'scale-100 opacity-100': showDeleteModal, 'scale-95 opacity-0': !showDeleteModal }">
          <h3 class="text-2xl font-semibold text-indigo-800 mb-4">Confirm Delete</h3>
          <p class="mb-4">Are you sure you want to delete this todo?</p>
          <div class="flex justify-end space-x-2">
            <button @click="closeDeleteModal"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
              Cancel
            </button>
            <button @click="confirmDelete"
              class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
              Delete
            </button>
          </div>
        </div>
      </div>
    </Transition>


    <!-- Warning Modal -->
    <WarningModal v-if="warning.show" :show="warning.show" :message="warning.message" @close="warning.show = false" />
  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue';
import { ChevronLeftIcon, MinusCircleIcon, ChevronRightIcon, CheckIcon, XIcon, PencilIcon, TrashIcon, PlusIcon } from 'lucide-vue-next';
import DOMPurify from 'dompurify';
import NavLayout from '../Layouts/NavLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import { defineProps, onMounted } from 'vue';
import WarningModal from '../Components/WarningModal.vue';

const warning = reactive({
  show: false,
  message: ''
});

const showWarning = (message) => {
  warning.message = message;
  warning.show = true;
};

defineOptions({
  layout: NavLayout
});

const props = defineProps({
  lists: {
    type: Array,
    default: () => []
  }
});

const todos = reactive([...props.lists]);
const currentDate = ref(new Date());
const selectedDate = ref(null);
const newTodoText = ref('');
const newTodoTime = ref('');
const showModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const editingTodo = ref(null);
const editTodoText = ref('');
const editTodoTime = ref('');
const deletingTodo = ref(null);

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

const currentMonthYear = computed(() =>
  currentDate.value.toLocaleString('default', { month: 'long', year: 'numeric' })
);

const calendarDates = computed(() => {
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth();
  const firstDay = new Date(year, month, 1);
  const dates = [];

  for (let i = 1; i <= 42; i++) {
    const date = new Date(year, month, i - firstDay.getDay());
    dates.push(date);
  }

  return dates;
});

const todosForSelectedDate = computed(() => {
  if (!selectedDate.value) return [];
  return todos.filter(todo => isSameDate(todo.due_date, selectedDate.value));
});

onMounted(() => {
  const storedShowModal = localStorage.getItem('showModal');
  const storedSelectedDate = localStorage.getItem('selectedDate');

  if (storedShowModal === 'true' && storedSelectedDate) {
    selectedDate.value = new Date(storedSelectedDate);
    showModal.value = true;
  }

  updateExpiredTasks();
});

const updateExpiredTasks = () => {
  if (expiredID.id.length > 0) {
    console.log(expiredID.id.length);
    expiredID.post('/dashboard/expired');
  } else {
    console.log(expiredID.id.length);
  }
}

const hasExpired = computed(() => {
  console.log(currentDate.value);
  console.log(selectedDate.value);
  return currentDate.value.getDate() > selectedDate.value.getDate();
});

const expiredDate = (date) => {
  const now = new Date();
  return date < now && !isToday(date);
};


function previousMonth() {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1);
}

function nextMonth() {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1);
}

function openModal(date) {
  selectedDate.value = date;
  showModal.value = true;
  localStorage.setItem('selectedDate', date.toISOString());
  localStorage.setItem('showModal', 'true');
}

function closeModal() {
  showModal.value = false;
  localStorage.removeItem('showModal');
  localStorage.removeItem('selectedDate');
}

function isSelectedDate(date) {
  return selectedDate.value && isSameDate(date, selectedDate.value);
}

function isSameDate(date_1, date_2) {
  const date1 = new Date(date_1);
  const date2 = new Date(date_2);
  return (
    date1.getFullYear() === date2.getFullYear() &&
    date1.getMonth() === date2.getMonth() &&
    date1.getDate() === date2.getDate()
  );
}

function isToday(date) {
  const today = new Date();
  return isSameDate(date, today);
}

function isSameMonth(date1, date2) {
  return date1.getFullYear() === date2.getFullYear() && date1.getMonth() === date2.getMonth();
}


const expiredID = useForm({
  id: [],
});


function getTodoStatusColor(date) {
  const todosForDate = todos.filter(todo => isSameDate(todo.due_date, date));

  if (todosForDate.length === 0) return '';

  const now = new Date();
  let expiredCount = 0;

  todosForDate.forEach(todo => {
    const todoDateTime = new Date(todo.due_date);
    todoDateTime.setHours(...todo.due_time.split(':'));

    if (todoDateTime < now) {
      if (!expiredID.id.includes(todo.id) && todo.status !== 'expired' && todo.status !== 'completed') {
        expiredID.id.push(todo.id);
      }
      expiredCount++;
    }
  });

  if (expiredCount === todosForDate.length) return 'bg-red-500'; // All expired
  if (expiredCount > 0) return 'bg-orange-500'; // Some expired
  if (todosForDate.every(todo => todo.status === 'completed')) return 'bg-green-500'; // All completed
  return 'bg-blue-500'; // Not expired or some completed
}


function sanitizeInput(input) {
  return DOMPurify.sanitize(input.trim());
}

function validateTimeFormat(time) {
  return /^([01]\d|2[0-3]):([0-5]\d)$/.test(time);
}

function addTodo() {
  const now = new Date();

  if (hasExpired.value) {
    showWarning('The date has already expired!');
    return;
  }

  const sanitizedText = sanitizeInput(newTodoText.value);
  const sanitizedTime = sanitizeInput(newTodoTime.value);

  if (!sanitizedText) {
    showWarning('Todo text cannot be empty.');
    return;
  }

  if (!selectedDate.value) {
    showWarning('No date selected.');
    return;
  }

  if (!validateTimeFormat(sanitizedTime)) {
    newTodoText.value = '';
    newTodoTime.value = '';
    showWarning('Should add time.');
    return;
  }

  // Validate time
  const [hours, minutes] = sanitizedTime.split(':').map(Number);
  const todoDate = new Date(selectedDate.value);
  todoDate.setHours(hours, minutes, 0);

  if (todoDate < now) {
    showWarning('The selected time has already passed!');
    return;
  }

  const add_date = ref(new Date(selectedDate.value));
  const new_date = new Date(add_date.value);
  new_date.setDate(new_date.getDate() + 1);
  add_date.value = new_date;

  const form = useForm({
    text: sanitizedText,
    date: add_date,
    time: sanitizedTime
  });

  // Adding error handling for the post request
  try {
    form.post('/dashboard/post');
    // Optionally handle success
    console.log('Todo added successfully');
  } catch (error) {
    // Handle errors here
    showWarning('An error occurred while adding the todo. Please try again.');
    console.error('Error posting todo:', error);
  }

  newTodoText.value = '';
  newTodoTime.value = '';
}

function toggleTodo(todo) {
  if (hasExpired.value) {
    showWarning("Date has expired!");
    return;
  }
  if(todo.status === 'expired') {
    showWarning('Task has expired.');
    return;
  }

  const form = useForm({
    id: todo.id,
    status: todo.status,
  });

  try {
    form.post('/dashboard/toggle');
    // Optionally handle success
    console.log('Todo toggled successfully');
  } catch (error) {
    // Handle errors here
    showWarning('An error occurred while toggling. Please try again.');
    console.error('Error posting todo:', error);
  }
}

function openEditModal(todo) {
  if (hasExpired.value) {
    showWarning("Date has expired!");
    return;
  }

  if(todo.status === 'expired') {
    showWarning("Can't edit, task has already expired!");
    return;
  }

  if(todo.status === 'completed') {
    showWarning("Can't edit, task was completed!");
    return;
  }

  const [hours, minutes] = todo.due_time.split(':'); // Split by colon and ignore the seconds part

  editingTodo.value = todo;
  editTodoText.value = todo.task_name;
  editTodoTime.value = `${hours}:${minutes}`;
  showEditModal.value = true;
}

function closeEditModal() {
  showEditModal.value = false;
  editingTodo.value = null;
  editTodoText.value = '';
  editTodoTime.value = '';
}

function confirmEdit() {
const now = new Date();

  if (!editingTodo.value) return;

  const sanitizedText = sanitizeInput(editTodoText.value);
  const sanitizedTime = sanitizeInput(editTodoTime.value);

  if (!sanitizedText) {
    showWarning('Todo text cannot be empty.');
    return;
  }

  if (!validateTimeFormat(sanitizedTime)) {
    showWarning('Invalid format!');
    return;
  }

  // Validate time
  const [hours, minutes] = sanitizedTime.split(':').map(Number);
  const todoDate = new Date(selectedDate.value);
  todoDate.setHours(hours, minutes, 0);

  if (todoDate < now) {
    showWarning('The selected time has already passed!');
    return;
  }

  const form = useForm({
    id: editingTodo.value.id,
    text: sanitizedText,
    time: sanitizedTime,
  });

  // Adding error handling for the post request
  try {
    form.put('/dashboard/update');
    // Optionally handle success
    console.log('Task edited successfully');
  } catch (error) {
    // Handle errors here
    showWarning('An error occurred while edit the task. Please try again.');
    console.error('Error posting todo:', error);
  }

  editingTodo.value.task_name = sanitizedText;
  editingTodo.value.due_time = sanitizedTime;

  closeEditModal();
}

function openDeleteModal(todo) {
  deletingTodo.value = todo;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
  deletingTodo.value = null;
}

function confirmDelete() {
  if (!deletingTodo.value) return;

  const form = useForm({
    task: deletingTodo.value,
  });

  // Adding error handling for the post request
  try {
    form.delete('/dashboard/delete');
    // Optionally handle success
    console.log('Task deleted successfully');
  } catch (error) {
    // Handle errors here
    showWarning('An error occurred while deleting the task. Please try again.');
    console.error('Error posting todo:', error);
  }

  const index = todos.findIndex(t => t.id === deletingTodo.value.id);
  if (index !== -1) {
    todos.splice(index, 1);
  }

  closeDeleteModal();
}

function formatTime(time) {
  const [hours, minutes] = time.split(':').map(Number);
  const period = hours >= 12 ? 'PM' : 'AM';
  const formattedHours = hours % 12 || 12; // Convert 0 to 12 for 12-hour format
  return `${formattedHours}:${minutes.toString().padStart(2, '0')} ${period}`;
}

const getTodoBackgroundClass = (todo) => {
  const now = new Date();
  const dueDateTime = new Date(todo.due_date);
  dueDateTime.setHours(...todo.due_time.split(':'));

  if (todo.status === 'completed') {
    return 'bg-green-100'; // Light green
  }
  if (dueDateTime < now) {
    return 'bg-red-100'; // Light red
  }
  if (todo.status === 'pending') {
    return 'bg-blue-100'; // Light blue
  }
  return ''; // Default (no background)
}

</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

.calendar-enter-active,
.calendar-leave-active {
  transition: all 0.5s ease;
}

.calendar-enter-from,
.calendar-leave-to {
  opacity: 0;
  transform: translateY(30px);
}
</style>