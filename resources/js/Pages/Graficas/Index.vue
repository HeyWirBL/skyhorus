<script setup>
import { Head } from '@inertiajs/vue3'
import BarChart from '@/Components/Charts/BarChart.vue'
import PieChart from '@/Components/Charts/PieChart.vue'
</script>
<template>
  <div>
    <Head title="Dashboard" />
    <div class="grid gap-4 xl:grid-cols-2 2xl:grid-cols-3">
      <!-- Main Widget -->
      <div class="p-4 bg-white border border-gray-200 rounded-md shadow 2xl:col-span-2 sm:p-6">
        <div class="flex items-center justify-between mb-4">
          <div class="flex-shrink-0">
            <h1 class="text-3xl font-bold leading-none">Gráfica</h1>
            <h3 class="text-base font-light">Datasets</h3>
          </div>
        </div>
        <div>
          <BarChart />
        </div>
      </div>
      <!-- End Main Widget -->

      <!-- Tabs Widget -->
      <div class="p-4 bg-white border border-gray-200 rounded-md shadow sm:p-6">
        <h3 class="flex items-center mb-4 font-semibold">Tasks Tracker</h3>
        <div>
          <PieChart />
        </div>
      </div>
      <!-- End Tabs Widget -->
    </div>
  </div>
</template>
