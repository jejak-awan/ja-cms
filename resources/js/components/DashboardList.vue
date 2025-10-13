<template>
  <div>
    <h2>Dashboard</h2>
    <ul>
      <li v-for="dashboard in dashboards" :key="dashboard.id">
        {{ dashboard.name }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const dashboards = ref([]);

onMounted(async () => {
  const res = await fetch('/dashboards');
  dashboards.value = await res.json();
});
</script>
