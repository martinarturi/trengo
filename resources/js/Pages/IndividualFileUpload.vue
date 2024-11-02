<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import FileFilterFormComponent from '@/Components/FileUploadFormComponent.vue';

const results = ref([])
const successMessage = ref("")

const headers = [
  { value: "first_name", text: "First Name", sortable: true },
  { value: "last_name", text: "Last Name", sortable: true },
  { value: "address", text: "Address", sortable: true },
  { value: "city", text: "City", sortable: true },
  { value: "postal_code", text: "Postal Code", sortable: true },
  { value: "country", text: "Country", sortable: true },
  { value: "date_of_birth", text: "Date of Birth", sortable: true },
  { value: "personal_description", text: "Personal Description" }
];

const render = function(newResults) {
    results.value = newResults
    successMessage.value = "Success"
    setTimeout(function () {
        successMessage.value = ""
    }, 2000)
}

</script>

<template>
    <Head title="File Upload" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50 flex flex-wrap">
        <div class="w-full md:w-1/2 px-4">
            <div class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="mb-1">
                    <img src="/trengo-logo.png" />
                </div>
                <FileFilterFormComponent
                    @fileSubmitted="(results) => render(results)"
                />
                <div class="mt-2"><span style="color:green">&nbsp;{{ successMessage }}</span></div>
            </div>
        </div>

        <div class="w-full md:w-1/2 px-4">
            <div class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div>
                    <h3>Results will come here</h3>
                </div>
                <EasyDataTable
                    :headers="headers"
                    :items="results"
                    :table-height="500"
                />
            </div>
        </div>
    </div>
</template>
