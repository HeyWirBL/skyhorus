<script setup>
import { ref } from 'vue'
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const chartData = ref({
  labels: ['Enero', 'Febrero', 'Marzo'],
  datasets: [{ label: 'Dato Uno', backgroundColor: '#f87979', data: [40, 20, 12] }],
})
const chartOptions = ref({
  responsive: true,
})
</script>

<template>
  <Bar id="my-chart-id" :options="chartOptions" :data="chartData" />
</template>
