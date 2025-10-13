<template>
  <div>
    <h2>Tags</h2>
    <ul>
      <li v-for="tag in tags" :key="tag.id">
        {{ tag.name }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const tags = ref([]);

onMounted(async () => {
  const res = await fetch('/tags');
  tags.value = await res.json();
});
</script>
