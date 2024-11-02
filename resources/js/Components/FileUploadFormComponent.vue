<script setup>
import { ref } from 'vue';
import axios from "axios";

const emit = defineEmits([
  "fileSubmitted"
]);

const formData = ref({
  name: "",
  age: null,
  city: "",
  country: "",
  file: null,
});

const handleFileUpload = (event) => {
  formData.value.file = event.target.files[0];
};

const submitForm = async () => {
  const queryParams = new URLSearchParams();

  if (formData.value.name) {
    queryParams.append("name", formData.value.name);
  }
  if (formData.value.age) {
    queryParams.append("age", formData.value.age);
  }
  if (formData.value.city) {
    queryParams.append("city", formData.value.city);
  }
  if (formData.value.country) {
    queryParams.append("country", formData.value.country);
  }

  const data = new FormData();
  data.append("file", formData.value.file);

  axios.post("/api/individual/file-upload?" + queryParams, data, {
    headers: { "Content-Type": "multipart/form-data" },
  })
  .then(response => {
    const result = response.data
    emit("fileSubmitted", result);
  })
  .catch(error => {
    alert(error?.response?.data?.message ?? "An error occurred while submitting the file.")
  });
};

</script>

<template>
  <div class="max-w-lg mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Submit File and Filters</h2>
    <form @submit.prevent="submitForm" class="space-y-4">

      <!-- File Upload -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Upload File</label>
        <input
          @change="handleFileUpload"
          type="file"
          class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"
          accept=".csv"
          required
        />
      </div>

      <!-- Name -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input
          v-model="formData.name"
          type="text"
          class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"
        />
      </div>

      <!-- Age -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Age</label>
        <input
          v-model.number="formData.age"
          type="number"
          class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"
        />
      </div>

      <!-- City -->
      <div>
        <label class="block text-sm font-medium text-gray-700">City</label>
        <input
          v-model="formData.city"
          type="text"
          class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"
        />
      </div>

      <!-- Country -->
      <div>
        <label class="block text-sm font-medium text-gray-700">Country</label>
        <input
          v-model="formData.country"
          type="text"
          class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"
        />
      </div>

      <!-- Submit Button -->
      <div>
        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow-md hover:bg-blue-700"
        >
          Submit
        </button>
      </div>
    </form>
  </div>
</template>
