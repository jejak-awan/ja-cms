<template>
  <div>
    <h2>Settings</h2>
    <ul>
      <li v-for="setting in settings" :key="setting.id">
        {{ setting.name }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const settings = ref([]);

onMounted(async () => {
  const res = await fetch('/settings');
  settings.value = await res.json();
});
</script>
