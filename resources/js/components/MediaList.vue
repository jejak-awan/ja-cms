<template>
  <div>
    <h2>Media</h2>
    <ul>
      <li v-for="media in mediaList" :key="media.id">
        {{ media.name }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const mediaList = ref([]);

onMounted(async () => {
  const res = await fetch('/media');
  mediaList.value = await res.json();
});
</script>
