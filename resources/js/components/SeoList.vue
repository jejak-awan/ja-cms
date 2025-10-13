<template>
  <div>
    <h2>SEO</h2>
    <ul>
      <li v-for="seo in seos" :key="seo.id">
        {{ seo.name }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const seos = ref([]);

onMounted(async () => {
  const res = await fetch('/seos');
  seos.value = await res.json();
});
</script>
