<template>
  <form @submit.prevent="handleRegister" class="space-y-4">
    <!-- Full Name -->
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
      <div class="mt-1 relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <UserIcon class="h-5 w-5 text-gray-400" />
        </div>
        <input 
          v-model="form.name" 
          @input="sanitizeInput('name'); checkFormFields()"
          type="text" 
          id="name" 
          required 
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          placeholder="John Doe"
          maxlength="30"
        >
      </div>
    </div>

    <!-- Email -->
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <div class="mt-1 relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <MailIcon class="h-5 w-5 text-gray-400" />
        </div>
        <input 
          v-model="form.email" 
          @input="sanitizeInput('email'); checkFormFields()"
          type="email" 
          id="email" 
          required 
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          placeholder="you@example.com"
          maxlength="30"
        >
      </div>
    </div>

    <!-- Password -->
    <div>
      <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
      <div class="mt-1 relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <LockIcon class="h-5 w-5 text-gray-400" />
        </div>
        <input 
          v-model="form.password" 
          @input="sanitizeInput('password'); validatePassword(); checkFormFields()"
          type="password" 
          id="password" 
          required 
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          placeholder="••••••••"
          maxlength="20"
        >
      </div>
      <!-- Password Error Message -->
      <div v-if="passwordError" class="text-sm text-red-500 mt-1">{{ passwordError }}</div>
    </div>

    <!-- Confirm Password -->
    <div>
      <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
      <div class="mt-1 relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <LockIcon class="h-5 w-5 text-gray-400" />
        </div>
        <input 
          v-model="form.passwordConfirmation" 
          @input="sanitizeInput('passwordConfirmation'); checkPasswordsMatch(); checkFormFields()"
          type="password" 
          id="password_confirmation" 
          required 
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          placeholder="••••••••"
          maxlength="20"
        >
      </div>
      <!-- Dynamic confirmation message -->
      <div v-if="passwordConfirmationError" class="text-sm text-red-500 mt-1">{{ passwordConfirmationError }}</div>
      <div v-else-if="passwordsMatch" class="text-sm text-green-500 mt-1">Passwords match</div>
    </div>

    <!-- Submit Button -->
    <div>
      <button
        :disabled="isDisabled"
        id="submit"
        type="submit" 
        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:bg-gray-400 disabled:text-gray-600"
      >
        Create Account
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { UserIcon, MailIcon, LockIcon } from 'lucide-vue-next';
import DOMPurify from 'dompurify'; // Import DOMPurify for sanitization

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const passwordError = ref(null);
const passwordConfirmationError = ref(null);
const passwordsMatch = ref(false);
const isDisabled = ref(true);

// Sanitize input fields
const sanitizeInput = (field) => {
  form[field] = DOMPurify.sanitize(form[field]); // Sanitize input using DOMPurify
};

// Validate password on change
const validatePassword = () => {
  const password = form.password;
  passwordError.value = null;
  const lengthRegex = /.{8,}/;
  const upperCaseRegex = /[A-Z]/;
  const lowerCaseRegex = /[a-z]/;
  const specialCharRegex = /[!@#$%^&*(),.?":{}|<>]/;

  if (!lengthRegex.test(password)) {
    passwordError.value = 'Password must be at least 8 characters long.';
  } else if (!upperCaseRegex.test(password)) {
    passwordError.value = 'Password must contain at least one uppercase letter.';
  } else if (!lowerCaseRegex.test(password)) {
    passwordError.value = 'Password must contain at least one lowercase letter.';
  } else if (!specialCharRegex.test(password)) {
    passwordError.value = 'Password must contain at least one special character.';
  }
};

const areFieldsNotEmpty = () => {
  return form.name && form.email && form.password && form.passwordConfirmation;
};

// Check if passwords match
const checkPasswordsMatch = () => {
  if (form.password !== form.passwordConfirmation) {
    passwordConfirmationError.value = 'Passwords do not match';
    passwordsMatch.value = false;
  } else {
    passwordConfirmationError.value = null;
    passwordsMatch.value = true;
  }
};

// Check all fields for emptiness
const checkFormFields = () => {
  if (areFieldsNotEmpty() && !passwordError.value && !passwordConfirmationError.value) {
    isDisabled.value = false;
  } else {
    isDisabled.value = true;
  }
};

// Handle form submission
const handleRegister = () => {
  // Sanitize all fields before submission
  form.name = DOMPurify.sanitize(form.name);
  form.email = DOMPurify.sanitize(form.email);
  form.password = DOMPurify.sanitize(form.password);
  form.password_confirmation = DOMPurify.sanitize(form.passwordConfirmation);

  if(form.password !== form.password_confirmation) {
    alert('Passwords do not match');
  } else {
    form.post('/register', {
      onSuccess: () => {
        alert('Account created successfully! You can now login.');
      },
    });
  }

  form.reset();
};
</script>
