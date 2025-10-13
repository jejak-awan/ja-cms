<template>
  <div>
    <h2>Pages</h2>
    <ul>
      <li v-for="page in pages" :key="page.id">
        {{ page.title }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const pages = ref([]);

onMounted(async () => {
  const res = await fetch('/pages');
  pages.value = await res.json();
});
</script>
