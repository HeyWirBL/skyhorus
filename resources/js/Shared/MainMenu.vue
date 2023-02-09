<script setup>
import { Link } from '@inertiajs/vue3'
</script>
<template>
  <nav>
    <ul>
      <li><Link href="/">Home</Link></li>
      <li><Link href="/dashboard">Dashboard</Link></li>
      <li><Link href="/logout" method="post" as="button">Log Out</Link></li>
    </ul>
  </nav>
</template>
