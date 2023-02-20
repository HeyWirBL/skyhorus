<script setup>
import { Head } from '@inertiajs/vue3'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'
</script>

<template>
  <div>
    <Head title="Perfil de Usuario" />
    <h1 class="mb-4 text-3xl font-bold">Perfil</h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <UpdateProfileInformationForm />
    </div>
    <div class="max-w-3xl mt-8 bg-white rounded-md shadow overflow-hidden">
      <UpdatePasswordForm />
    </div>
  </div>
</template>
