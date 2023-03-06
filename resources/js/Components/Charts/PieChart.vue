<script setup>
import { ref } from 'vue'
import { Pie } from 'vue-chartjs'
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'

ChartJS.register(ArcElement, Tooltip, Legend)

const chartData = ref({
  labels: ['Task', 'Bug', 'Spike'],
  datasets: [
    {
      label: 'Task X Bug X Spike',
      backgroundColor: ['#41B883', '#E46651', '#00D8FF'],
      data: [40, 20, 12],
    },
  ],
})
const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
})
</script>

<template>
  <Pie id="my-pie-chart-id" :options="chartOptions" :data="chartData" />
</template>
