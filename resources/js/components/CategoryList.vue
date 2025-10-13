<template>
  <div>
    <h2>Categories</h2>
    <ul>
      <li v-for="category in categories" :key="category.id">
        {{ category.name }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const categories = ref([]);

onMounted(async () => {
  const res = await fetch('/categories');
  categories.value = await res.json();
});
</script>
